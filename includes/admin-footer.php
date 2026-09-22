<?php
/**
 * Footer admin.
 */
?>
    </div><!-- /.admin-content -->
  </div><!-- /.admin-main -->
</div><!-- /.admin-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= url('assets/js/admin.js') ?>"></script>

<?php $flashes = get_flashes(); if ($flashes): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    <?php foreach ($flashes as $f): ?>
    Swal.fire({
      toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true,
      icon: '<?= $f['type'] === 'error' ? 'error' : ($f['type'] === 'warning' ? 'warning' : ($f['type'] === 'info' ? 'info' : 'success')) ?>',
      title: <?= json_encode($f['message']) ?>
    });
    <?php endforeach; ?>
  });
</script>
<?php endif; ?>

</body>
</html>
