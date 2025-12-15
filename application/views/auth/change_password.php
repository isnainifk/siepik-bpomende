<h4 class="fw-bold py-3 mb-4">Ganti Password</h4>

<?= $msg ?>

<form method="post" class="card p-4" >

    <div class="mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" name="old_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url(
        	"dashboard",
        ) ?>" class="btn btn-secondary">Batal</a>
    </div>

</form>
