<h4 class="fw-bold py-3 mb-4">Evaluasi Pelatihan</h4>

  <?= $msg ?>

  <div class="card mb-3 p-4">
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label"><strong>Peserta:</strong></label>
        <p><?= htmlspecialchars($competency->trainee_name) ?></p>
      </div>
      <div class="mb-3">
        <label class="form-label"><strong>Pelatihan:</strong></label>
        <p><?= htmlspecialchars($competency->training_title) ?></p>
      </div>
      <div class="mb-3">
        <label class="form-label"><strong>Tanggal:</strong></label>
        <p><?= $competency->training_start ?> s/d <?= $competency->training_end ?></p>
      </div>
    </div>
  </div>

  <div class="card mb-3" style="padding-left: 30px;">
    <div class="card-body">
      <div class="mb-3">
        <p style="text-align:center; font-style: italic; line-height: 10%;"><strong>(Semua data dan informasi dalam evaluasi ini bersifat rahasia dan hanya digunakan untuk keperluan evaluasi pelaksanaan pelatihan) </strong></p>
        <p style="text-align:center; line-height: 100%;"><strong> Dalam rangka pelaksanaan evaluasi pelatihan level 3, kami meminta bantuan Bapak/Ibu untuk mengisi setiap pernyataan di bawah ini sesuai dengan persepsi/pendapat Bapak/Ibu terhadap peserta pelatihan. </strong></p>
        <p style="line-height: 10%;"> A. Isilah dengan memilih pada poin tanggapan yang tersedia dengan penilaian : </p>
        <p style="line-height: 10%;"> 5 :Sangat Baik </p>
        <p style="line-height: 10%;"> 4 :Baik </p>
        <p style="line-height: 10%;"> 3 :Netral </p>
        <p style="line-height: 10%;"> 2 :Kurang </p>
        <p style="line-height: 10%;"> 1 :Sangat Kurang</p>
      </div>
    </div>
  </div>

  <form method="post" class="card p-4">

    <div class="table-responsive mb-3">
      <table class="table table-borderless table-striped">
        <thead>
          <tr>
            <th>Pertanyaan</th>
            <?php for ($s = 1; $s <= 5; $s++): ?>
            <th class="text-center"><?= $s ?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 1; $i <= 8; $i++): ?>
          <tr>
            <td><?= $questions[$i - 1] ?></td>
            <?php for ($s = 1; $s <= 5; $s++): ?>
              <td class="text-center">
                <input type="radio" name="q<?= $i ?>" value="<?= $s ?>"
                  <?= $evaluation && $evaluation->{"q$i"} == $s
                  	? "checked"
                  	: "" ?>
                  <?= $evaluation ? "disabled" : "required" ?>>
              </td>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>

    <div class="mb-3">
      <label class="form-label">Catatan</label>
      <textarea name="notes" class="form-control" <?= $evaluation
      	? "readonly"
      	: "" ?>><?= $evaluation ? $evaluation->notes : "" ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Tanda Tangan</label>
      <div>
      <?php if (!$evaluation): ?>
        <canvas id="signature-pad"></canvas><br>
        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearPad()">Bersihkan</button>
        <input type="hidden" name="signature" id="signature">
      <?php else: ?>
        <?php if ($evaluation->signature): ?>
          <img src="<?= $evaluation->signature ?>" alt="Tanda Tangan" class="img-fluid" style="max-width:400px; border:1px solid #ccc;">
        <?php else: ?>
          <p><em>(Tidak ada tanda tangan)</em></p>
        <?php endif; ?>
      <?php endif; ?>
      </div>
    </div>

    <div class="mb-3">
      <?php if (!$evaluation): ?>
        <button type="submit" class="btn btn-primary">Simpan</button>
      <?php else: ?>
        <a href="<?= site_url(
        	"evaluasi",
        ) ?>" class="btn btn-secondary">Kembali</a>
      <?php endif; ?>
    </div>

  </form>
</div>

<?php if (!$evaluation): ?>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
var canvas = document.getElementById('signature-pad');
var signaturePad = new SignaturePad(canvas);
function clearPad(){ signaturePad.clear(); }
document.querySelector("form").addEventListener("submit", function(){
  if(!signaturePad.isEmpty()){
    document.getElementById("signature").value = signaturePad.toDataURL();
  }
});
</script>
<?php endif; ?>
