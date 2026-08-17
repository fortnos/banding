<section class="panel">
  <div class="panel-head">
    <h2>Riwayat Banding</h2>
    <div class="filter">
      <a class="chip<?= $filter === '' ? ' chip-active' : '' ?>" href="riwayat.php">Semua</a>
      <a class="chip<?= $filter === 'pending' ? ' chip-active' : '' ?>" href="riwayat.php?status=pending">Menunggu</a>
      <a class="chip<?= $filter === 'opened' ? ' chip-active' : '' ?>" href="riwayat.php?status=opened">Dibuka</a>
      <a class="chip<?= $filter === 'failed' ? ' chip-active' : '' ?>" href="riwayat.php?status=failed">Gagal</a>
    </div>
  </div>

  <?php if (!$appeals): ?>
    <p class="muted">Belum ada data banding.</p>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Email</th>
            <th>Status</th>
            <th>Dibuka</th>
            <th>IP</th>
            <th>Diajukan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($appeals as $row): ?>
          <tr>
            <td><?= e($row['email']) ?></td>
            <td><span class="badge badge-<?= e($row['status']) ?>"><?= e($row['status']) ?></span></td>
            <td><?= $row['opened_at'] ? e($row['opened_at']) : '&mdash;' ?></td>
            <td><?= e($row['ip_address']) ?></td>
            <td><?= e($row['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p class="muted">Menampilkan 100 data terbaru.</p>
  <?php endif; ?>
</section>
