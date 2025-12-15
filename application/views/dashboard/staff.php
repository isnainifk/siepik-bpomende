<div class="col-lg-12 mb-4 order-0">
  <div class="card">
    <div class="d-flex align-items-end row">
      <div class="col-sm-7">
        <div class="card-body">
          <h5 class="card-title text-primary">Selamat Datang, <?= $this->session->userdata(
          	"user",
          )->name ?> 👋🏻</h5>
          <p class="mb-0">
          Anda login sebagai <?= $this->session->userdata("user")->role ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">

  <?php
  $this->load->view("components/dashboard_card", [
  	"title" => "Total Penugasan",
  	"value" => $stats["total_kompetensi"],
  	"bg" => "bg-label-warning",
  	"icon" => "bx-book-content",
  	"color" => "text-warning",
  ]);

  $this->load->view("components/dashboard_card", [
  	"title" => "Sudah Dinilai",
  	"value" => $stats["sudah_dinilai"],
  	"bg" => "bg-label-success",
  	"icon" => "bx-check-circle",
  	"color" => "text-success",
  ]);

  $this->load->view("components/dashboard_card", [
  	"title" => "Belum Dinilai",
  	"value" => $stats["belum_dinilai"],
  	"bg" => "bg-label-danger",
  	"icon" => "bx-time-five",
  	"color" => "text-danger",
  ]);
  ?>

</div>

<div class="card">
  <h5 class="card-header">Pelatihan Belum Dinilai</h5>
  <div class="table-responsive text-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Peserta</th>
          <th>Pelatihan</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
      <?php $no = $offset + 1; ?>
      <?php foreach ($belum_dinilai_list as $row): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row->trainee_name) ?></td>
          <td><?= htmlspecialchars($row->training_title) ?></td>
          <td><?= htmlspecialchars(
          	$row->training_start,
          ) ?> s/d <?= htmlspecialchars($row->training_end) ?></td>
          <td>
            <!-- Edit / Nilai -->
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
    <nav aria-label="Page navigation" class="ms-4">
      <ul class="pagination justify-content-start">

        <!-- First -->
        <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
          <a class="page-link"
             href="<?= site_url(
             	"dashboard?page=1&year=" .
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
             	"dashboard?page=" .
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
               	"dashboard?page=" .
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
             	"dashboard?page=" .
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
             	"dashboard?page=" .
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
<!--/ Basic Bootstrap Table -->
