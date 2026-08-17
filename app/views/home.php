<section class="panel">
  <h2>Banding Gmail Sekali Klik</h2>
  <p class="muted">
    Tempel satu atau banyak email (satu per baris), lalu klik
    <strong>Banding Sekarang</strong>. Setiap email akan membuka halaman
    appeal resmi Google secara berurutan dengan jeda otomatis.
  </p>
  <form id="form-banding" class="form-stack" autocomplete="off">
    <?= Csrf::field() ?>
    <label for="emails">Alamat email yang diajukan banding</label>
    <textarea id="emails" name="emails" rows="8" required
      placeholder="contoh1@gmail.com&#10;contoh2@gmail.com" spellcheck="false"></textarea>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary" id="btn-banding">Banding Sekarang</button>
      <span class="muted" id="form-info">Jeda antar-tab: <?= (int) Config::int('BANDING_DELAY_MS', 3000) / 1000 ?> detik.</span>
    </div>
  </form>
</section>

<section class="panel hidden" id="result-panel">
  <h2>Hasil</h2>
  <div class="table-wrap">
    <table class="table" id="result-table">
      <thead>
        <tr><th>#</th><th>Email</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <p class="muted note">
    Catatan: Google memerlukan login pada akun yang bersangkutan untuk
    memulai appeal. Membuka banyak tab sekaligus dapat memicu captcha,
    karena itu jeda otomatis diterapkan. Hindari mengajukan banding
    berulang pada akun yang sama agar tidak memperlambat proses review.
  </p>
</section>

<script>
window.BANDING_CONFIG = {
  delayMs: <?= Config::int('BANDING_DELAY_MS', 3000) ?>,
  maxTabs: <?= Config::int('MAX_TABS', 10) ?>
};
</script>
