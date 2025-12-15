
    <h4 class="fw-bold py-3 mb-4"><?= $title ?></h4>

    <form action="<?= base_url(
    	"training/save/" . ($form->id ?? ""),
    ) ?>" method="post" class="card p-4">

        <!-- Peserta -->
        <div class="mb-3">
            <label class="form-label">Peserta</label>
            <select name="trainee_id" class="form-select" required>
                <option value="">-- pilih peserta --</option>
                <?php foreach ($trainees as $t): ?>
                    <option value="<?= $t->id ?>" <?= isset($form) &&
$form->trainee_id == $t->id
	? "selected"
	: "" ?>>
                        <?= $t->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Judul Pelatihan -->
        <div class="mb-3">
            <label class="form-label">Judul Pelatihan</label>
            <input type="text" name="training_title" class="form-control"
                   value="<?= $form->training_title ?? "" ?>" required>
        </div>

        <!-- Tanggal Mulai -->
        <div class="mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="training_start" class="form-control"
                   value="<?= $form->training_start ?? "" ?>" required>
        </div>

        <!-- Tanggal Selesai -->
        <div class="mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="training_end" class="form-control"
                   value="<?= $form->training_end ?? "" ?>" required>
        </div>

        <!-- Penyelenggara -->
        <div class="mb-3">
            <label class="form-label">Penyelenggara</label>
            <input type="text" name="training_organizer" class="form-control"
                   value="<?= $form->training_organizer ?? "" ?>">
        </div>

        <!-- Evaluator -->
        <div class="mb-3">
            <label class="form-label">Evaluator</label>
            <select name="evaluator_id" id="evaluator" class="form-select" required>
                <option value="">-- pilih evaluator --</option>
                <?php foreach ($evaluators as $e): ?>
                    <option value="<?= $e->id ?>" <?= isset($form) &&
$form->evaluator_id == $e->id
	? "selected"
	: "" ?>>
                        <?= $e->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Jabatan Evaluator -->
        <div class="mb-3">
            <label class="form-label">Jabatan Evaluator</label>
            <select name="evaluator_position" id="evaluator_position" class="form-select">
                <option value="<?= $form->evaluator_position ?? "" ?>">
                    <?= $form->evaluator_position ?? "-- pilih jabatan --" ?>
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>

<script>
document.getElementById("evaluator").addEventListener("change", function () {
    let userId = this.value;
    let target = document.getElementById("evaluator_position");

    target.innerHTML = "<option>Loading...</option>";

    fetch("<?= base_url("training/get_positions?user_id=") ?>" + userId)
        .then(res => res.json())
        .then(data => {
            let html = "<option value=''>-- pilih jabatan --</option>";
            data.forEach(function (p) {
                html += `<option value="${p.jabatan}">${p.jabatan}</option>`;
            });
            target.innerHTML = html;
        });
});
</script>
