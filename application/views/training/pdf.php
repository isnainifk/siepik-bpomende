<style>
  body { font-family:"Bookman Old Style", serif; font-size: 12px; }
  h2 { text-align:center; margin-bottom:1px; line-height: 10%; }
  table { border-collapse: collapse; width: 100%; margin-top:10px; }
  table, th, td {font-size:14px ; border: none; padding: 3px; vertical-align: top; }
  th { background:#eee; }
  .ttd { margin-top:40px; text-align:center; }
</style>

<h2>FORMULIR EVALUASI LEVEL 3</h2>
<h2>EVALUASI PERUBAHAN SIKAP DAN PERILAKU PESERTA PELATIHAN</h2>


<h3>Evaluator Peserta Pelatihan</h3>
<table>
  <tr><td>Nama</td>
      <td>:</td>
      <td><?= $data->evaluator_name ?></td></tr>
  <tr><td>Jabatan</td>
      <td>:</td>
      <td><?= $data->evaluator_jabatan ?></td></tr>
  <tr><td>Unit Kerja</td>
      <td>:</td>
      <td><?= $data->evaluator_unit ?></td></tr>
</table>

<h3>Identitas Peserta Pelatihan yang dievaluasi</h3>
<table>
  <tr><td>Nama Peserta Pelatihan</td>
      <td>:</td>
      <td><?= $data->trainee_name ?></td></tr>
  <tr><td>Judul Pelatihan</td>
      <td>:</td>
      <td><?= $data->training_title ?></td></tr>
  <tr><td>Tanggal Pelatihan </td>
      <td>:</td>
      <td><?= formatTanggal(
    $data->training_start,
    $data->training_end,
  ) ?></td></tr>
  <tr><td>Penyelenggara Pelatihan</td>
      <td>:</td>
      <td><?= $data->training_organizer ?></td></tr>
</table>

<h3>A. Hasil Evaluasi</h3>
<table style="border: 1px solid black;">
  <tr>
    <th style="border: 1px solid black;">No</th>
    <th style="border: 1px solid black;">Pertanyaan</th>
    <th style="border: 1px solid black;">Nilai</th>
  </tr>

<?php foreach ($questions as $i => $qtext): ?>
  <tr>
    <td style="text-align:center; border: 1px solid black;"><?= $i ?></td>
    <td style="border: 1px solid black;"><?= $qtext ?></td>
    <td style="text-align:center ; border: 1px solid black;">
      <?= $eval ? $eval->{"q$i"} : "-" ?>
    </td>
  </tr>
<?php endforeach; ?>

</table>

<h3>B. Evaluasi penguasaan kompetensi dan perubahan sikap/perilaku peserta
pelatihan setelah mengikuti pelatihan </h3>
<p style="font-size:14px ;"><?= $eval->notes ?? "-" ?></p>

<!-- <p><strong>Tanggal Evaluasi:</strong> <?= $eval->evaluated_at ?? "-" ?></p> -->

<?php if (!empty($eval->signature)): ?>
<div class="sttd">
    <p style="font-size:14px ;"
    ><strong>Evaluator</strong></p>
    <img src="<?= $eval->signature ?>" style="width:200px; height:auto;"><br>
    <span style="font-size:14px ;"><?= $data->evaluator_name ?></span>
</div>
<?php endif; ?>
