<div class="d-flex justify-content-between align-items-center py-3 mb-4">
    <h4 class="fw-bold m-0">Evaluasi Saya</h4>
</div>

<div class="card">
  <h5 class="card-header">
  <form method="get" action="<?= site_url("evaluasi") ?>">
      <div class="row">

        <!-- Filter Tahun -->
        <div class="col-md-3 mb-3">
          <label class="form-label">Tahun</label>
          <select name="year" class="form-select">
            <option value="">Semua</option>
            <?php foreach ($years as $y): ?>
              <option value="<?= $y->y ?>" <?= $this->input->get("year") ==
$y->y
	? "selected"
	: "" ?>>
                <?= $y->y ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Filter Status -->
        <div class="col-md-3 mb-3">
          <label class="form-label">Status Penilaian</label>
          <select name="status" class="form-select">
            <option value="">Semua</option>
            <option value="sudah" <?= $this->input->get("status") == "sudah"
            	? "selected"
            	: "" ?>>
              Sudah Dinilai
            </option>
            <option value="belum" <?= $this->input->get("status") == "belum"
            	? "selected"
            	: "" ?>>
              Belum Dinilai
            </option>
          </select>
        </div>

        <!-- Tombol Submit -->
        <div class="col-md-3 mb-3 d-flex align-items-end">
          <button class="btn btn-primary w-100">
            <i class="bx bx-filter"></i> Terapkan Filter
          </button>
        </div>

        <!-- Tombol Reset -->
        <div class="col-md-3 mb-3 d-flex align-items-end">
          <a href="<?= site_url("evaluasi") ?>" class="btn btn-secondary w-100">
            <i class="bx bx-reset"></i> Reset
          </a>
        </div>

      </div>
  </form>
  </h5>
  <div class="table-responsive text-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Peserta</th>
          <th>Judul Pelatihan</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no = $offset + 1; ?>
      <?php foreach ($list as $row): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row->trainee_name) ?></td>
          <td><?= htmlspecialchars($row->training_title) ?></td>
          <td><?= formatTanggal(
          	$row->training_start,
          	$row->training_end,
          ) ?></td>
          <td><?= $row->sudah_dinilai > 0
          	? "<span class='badge bg-label-success me-1'>Sudah Menilai</span>"
          	: "<span class='badge bg-label-danger me-1'>Belum Menilai</span>" ?></td>
          <td>
          <a href="<?= site_url("evaluation/" . $row->id) ?>"
             class="btn btn-sm btn-primary" title="Nilai">
             Nilai
          </a>
          </td>
        </tr>
      <?php endforeach; ?>

      </tbody>
    </table>
    <!-- ============ Pagination ============ -->
    <nav aria-label="Page navigation" class="ms-4 mt-4">
      <ul class="pagination justify-content-start">

        <!-- First -->
        <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
          <a class="page-link"
             href="<?= site_url(
             	"training?page=1&year=" .
             		$this->input->get("year") .
             		"&status=" .
             		$this->input->get("status"),
             ) ?>">
             <i class="tf-icon bx bx-chevrons-left"></i>
          </a>
        </li>

        <!-- Prev -->
        <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
          <a class="page-link"
             href="<?= site_url(
             	"training?page=" .
             		($page - 1) .
             		"&year=" .
             		$this->input->get("year") .
             		"&status=" .
             		$this->input->get("status"),
             ) ?>">
             <i class="tf-icon bx bx-chevron-left"></i>
          </a>
        </li>

        <!-- Number Loop -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $page == $i ? "active" : "" ?>">
            <a class="page-link"
               href="<?= site_url(
               	"training?page=" .
               		$i .
               		"&year=" .
               		$this->input->get("year") .
               		"&status=" .
               		$this->input->get("status"),
               ) ?>">
               <?= $i ?>
            </a>
          </li>
        <?php endfor; ?>

        <!-- Next -->
        <li class="page-item <?= $page >= $total_pages ? "disabled" : "" ?>">
          <a class="page-link"
             href="<?= site_url(
             	"training?page=" .
             		($page + 1) .
             		"&year=" .
             		$this->input->get("year") .
             		"&status=" .
             		$this->input->get("status"),
             ) ?>">
             <i class="tf-icon bx bx-chevron-right"></i>
          </a>
        </li>

        <!-- Last -->
        <li class="page-item <?= $page >= $total_pages ? "disabled" : "" ?>">
          <a class="page-link"
             href="<?= site_url(
             	"training?page=" .
             		$total_pages .
             		"&year=" .
             		$this->input->get("year") .
             		"&status=" .
             		$this->input->get("status"),
             ) ?>">
             <i class="tf-icon bx bx-chevrons-right"></i>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</div>
