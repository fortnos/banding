(function () {
  'use strict';

  var form = document.getElementById('form-banding');
  if (!form) return;

  var cfg = window.BANDING_CONFIG || { delayMs: 3000, maxTabs: 10 };
  var btn = document.getElementById('btn-banding');
  var info = document.getElementById('form-info');
  var panel = document.getElementById('result-panel');
  var tbody = document.querySelector('#result-table tbody');

  var statusLabel = { pending: 'Menunggu', opened: 'Dibuka', failed: 'Gagal' };

  function cell(text) {
    var td = document.createElement('td');
    td.textContent = text;
    return td;
  }

  function addRow(no, email, status) {
    var tr = document.createElement('tr');
    tr.appendChild(cell(String(no)));
    tr.appendChild(cell(email));
    var statusTd = document.createElement('td');
    var badge = document.createElement('span');
    badge.className = 'badge badge-' + status;
    badge.textContent = statusLabel[status] || status;
    statusTd.appendChild(badge);
    tr.appendChild(statusTd);
    var actionTd = document.createElement('td');
    actionTd.textContent = '\u2014';
    tr.appendChild(actionTd);
    tbody.appendChild(tr);
    return tr;
  }

  function updateRow(no, status) {
    var tr = tbody.querySelectorAll('tr')[no - 1];
    if (!tr) return;
    var badge = tr.querySelector('.badge');
    if (!badge) return;
    badge.className = 'badge badge-' + status;
    badge.textContent = statusLabel[status] || status;
  }

  function post(data) {
    var body = new URLSearchParams(data);
    return fetch('banding.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: body.toString()
    }).then(function (res) {
      return res.json();
    });
  }

  function sleep(ms) {
    return new Promise(function (resolve) {
      setTimeout(resolve, ms);
    });
  }

  form.addEventListener('submit', function (ev) {
    ev.preventDefault();
    if (btn.disabled) return;

    var csrf = form.querySelector('input[name="csrf_token"]').value;
    var emails = document.getElementById('emails').value;
    if (!emails.trim()) return;

    btn.disabled = true;
    btn.textContent = 'Memproses...';
    panel.classList.remove('hidden');
    tbody.innerHTML = '';
    info.textContent = 'Menyimpan data...';

    post({ action: 'submit', csrf_token: csrf, emails: emails })
      .then(function (res) {
        if (res.error) throw new Error(res.error);
        var rows = res.emails;
        if (rows.length === 0) throw new Error('Tidak ada email yang diproses.');
        if (rows.length > cfg.maxTabs) {
          info.textContent = 'Terlalu banyak email (' + rows.length + '), maksimal ' + cfg.maxTabs + ' dibuka; sisanya ditandai gagal.';
        } else {
          info.textContent = 'Membuka halaman appeal...';
        }
        rows.forEach(function (item, i) {
          addRow(i + 1, item.email, 'pending');
        });

        var total = Math.min(rows.length, cfg.maxTabs);
        var chain = Promise.resolve();
        for (var i = 0; i < total; i++) {
          (function (item, idx) {
            chain = chain.then(function () {
              updateRow(idx + 1, 'opened');
              window.open(item.url, '_blank', 'noopener');
              post({ action: 'status', csrf_token: csrf, id: item.id, status: 'opened' }).catch(function () {});
              if (idx < total - 1) return sleep(cfg.delayMs);
            });
          })(rows[i], i);
        }
        return chain.then(function () {
          var failed = rows.slice(total);
          failed.forEach(function (item, i) {
            updateRow(total + i + 1, 'failed');
            post({ action: 'status', csrf_token: csrf, id: item.id, status: 'failed' }).catch(function () {});
          });
          info.textContent = 'Selesai. Selesaikan login di tab yang terbuka untuk memulai appeal tiap akun.';
        });
      })
      .catch(function (err) {
        info.textContent = 'Terjadi kesalahan: ' + err.message;
      })
      .then(function () {
        btn.disabled = false;
        btn.textContent = 'Banding Sekarang';
      });
  });
})();
