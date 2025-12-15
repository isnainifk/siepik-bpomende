      </div> <!-- layout-container -->
    </div> <!-- layout-wrapper -->

    <script src="<?= base_url(
    	"assets/vendor/libs/jquery/jquery.js",
    ) ?>"></script>
    <script src="<?= base_url("assets/vendor/js/bootstrap.js") ?>"></script>
    <script src="<?= base_url(
    	"assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js",
    ) ?>"></script>
    <script src="<?= base_url("assets/vendor/js/menu.js") ?>"></script>
    <script src="<?= base_url(
    	"assets/vendor/libs/apex-charts/apexcharts.js",
    ) ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url("assets/js/main.js") ?>"></script>
    <script>
    function confirmDelete(url) {
        Swal.fire({
            title: "Hapus Data?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
    </script>
    <script>
    <?php if ($this->session->flashdata("success")): ?>
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "<?= $this->session->flashdata("success") ?>",
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if ($this->session->flashdata("error")): ?>
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "<?= $this->session->flashdata("error") ?>",
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>
    </script>
  </body>
</html>
