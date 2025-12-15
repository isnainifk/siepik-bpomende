<h4 class="fw-bold py-3 mb-4"><?= $title ?></h4>

<form action="<?= base_url("user/save/" . ($form->id ?? "")) ?>"
      method="post" class="card p-4">

    <!-- Nama -->
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control"
               value="<?= $form->name ?? "" ?>" required>
    </div>

    <!-- UserID -->
    <div class="mb-3">
        <label class="form-label">UserID</label>
        <input type="text" name="userid" class="form-control"
               value="<?= $form->userid ?? "" ?>" required>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label class="form-label">
            Password <?= isset($form) ? "(kosongkan jika tidak diganti)" : "" ?>
        </label>
        <input type="password" name="password" class="form-control">
    </div>

    <!-- Role -->
    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" id="role" class="form-select" required>
            <option value="">-- pilih role --</option>
            <option value="admin"
                <?= isset($form) && $form->role === "admin"
                	? "selected"
                	: "" ?>>
                Admin
            </option>
            <option value="kepegawaian"
                <?= isset($form) && $form->role === "kepegawaian"
                	? "selected"
                	: "" ?>>
                Kepegawaian
            </option>
            <option value="staff"
                <?= isset($form) && $form->role === "staff"
                	? "selected"
                	: "" ?>>
                Staff
            </option>
        </select>
    </div>

    <!-- Unit Kerja -->
    <div class="mb-3">
        <label class="form-label">Unit Kerja</label>
        <input type="text" name="unit_kerja" class="form-control"
               value="<?= $form->unit_kerja ?? "" ?>">
    </div>

    <!-- Jabatan (Dynamic Like Training Example) -->
    <div class="mb-3">
        <label class="form-label">Jabatan</label>

        <?php if (!empty($jabatans)): ?>
            <?php foreach ($jabatans as $j): ?>
                <div class="input-group mb-2">
                    <input type="text" name="jabatan[]" value="<?= $j->jabatan ?>" class="form-control">
                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">X</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <input type="text" name="jabatan[]" class="form-control mb-2">
        <?php endif; ?>

        <div id="moreJabatan"></div>

        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addJabatan()">+ Tambah Jabatan</button>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">Simpan</button>
        <a href="<?= base_url("user") ?>" class="btn btn-secondary">Kembali</a>
    </div>

</form>

<script>
function addJabatan() {
    let div = document.createElement("div");
    div.className = "input-group mb-2";
    div.innerHTML = `
        <input type="text" name="jabatan[]" class="form-control">
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">X</button>
    `;
    document.getElementById("moreJabatan").appendChild(div);
}
</script>
