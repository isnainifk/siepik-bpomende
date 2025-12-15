<div class="d-flex justify-content-between align-items-center py-3 mb-4">
    <h4 class="fw-bold m-0">Manajemen User</h4>

    <a href="<?= site_url("user/form") ?>" class="btn btn-primary">
        <i class="bx bx-plus-circle"></i> Tambah User
    </a>
</div>

<div class="card">
    <h5 class="card-header">
        <form method="get" action="<?= site_url("user") ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text"
                           name="search"
                           value="<?= htmlspecialchars($search) ?>"
                           class="form-control"
                           placeholder="Cari user ...">
                </div>

                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary w-100">
                        <i class="bx bx-search"></i> Cari
                    </button>
                </div>

                <div class="col-md-3 mb-3">
                    <a href="<?= site_url(
                    	"user",
                    ) ?>" class="btn btn-secondary w-100">
                        <i class="bx bx-reset"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </h5>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>UserID</th>
                    <th>Role</th>
                    <th>Unit Kerja</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = $offset + 1; ?>
                <?php foreach ($list as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($u->name) ?></td>
                        <td><?= htmlspecialchars($u->userid) ?></td>
                        <td>
                            <?php switch ($u->role) {
                            	case "admin":
                            		echo "<span class='badge bg-label-primary me-1'>Admin</span>";
                            		break;
                            	case "kepegawaian":
                            		echo "<span class='badge bg-label-danger me-1'>Kepegawaian</span>";
                            		break;
                            	default:
                            		echo "<span class='badge bg-label-success me-1'>Staff</span>";
                            		break;
                            } ?>
                        </td>
                        <td><?= htmlspecialchars($u->unit_kerja) ?></td>
                        <td><?= htmlspecialchars($u->jabatans ?? "-") ?></td>
                        <td>
                            <a href="<?= site_url("user/form/" . $u->id) ?>"
                               class="btn btn-sm btn-warning mb-1">
                                <i class="bx bx-edit"></i>
                            </a>

                            <a href="#"
                               onclick="confirmDelete('<?= site_url(
                               	"user/delete/" . $u->id,
                               ) ?>')"
                               class="btn btn-sm btn-danger">
                                <i class="bx bx-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav class="ms-4 mt-4">
            <ul class="pagination">

                <!-- First -->
                <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                    <a class="page-link" href="<?= site_url(
                    	"user?page=1&search=$search",
                    ) ?>">
                        <i class="bx bx-chevrons-left"></i>
                    </a>
                </li>

                <!-- Prev -->
                <li class="page-item <?= $page <= 1 ? "disabled" : "" ?>">
                    <a class="page-link" href="<?= site_url(
                    	"user?page=" . ($page - 1) . "&search=$search",
                    ) ?>">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>

                <!-- Number -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $page == $i ? "active" : "" ?>">
                        <a class="page-link"
                           href="<?= site_url(
                           	"user?page=" . $i . "&search=$search",
                           ) ?>">
                           <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Next -->
                <li class="page-item <?= $page >= $total_pages
                	? "disabled"
                	: "" ?>">
                    <a class="page-link"
                       href="<?= site_url(
                       	"user?page=" . ($page + 1) . "&search=$search",
                       ) ?>">
                       <i class="bx bx-chevron-right"></i>
                    </a>
                </li>

                <!-- Last -->
                <li class="page-item <?= $page >= $total_pages
                	? "disabled"
                	: "" ?>">
                    <a class="page-link"
                       href="<?= site_url(
                       	"user?page=" . $total_pages . "&search=$search",
                       ) ?>">
                       <i class="bx bx-chevrons-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
