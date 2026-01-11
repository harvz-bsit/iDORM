<?php
$pageTitle = "Contracts Management";
include 'includes/header.php';
include '../config/conn.php';

$query = "
    SELECT c.*, p.full_name
    FROM contracts c
    INNER JOIN user_personal_information p ON c.student_id = p.student_id
    ORDER BY c.signed_at DESC
";
$result = mysqli_query($conn, $query);
?>

<div class="container py-5 vh-100">
    <div class="dashboard-header">
        <h1 class="fw-bold text-maroon">
            <i class="bi bi-file-earmark-text"></i> Contracts
        </h1>
        <p class="lead">Manage signed contracts</p>
        <div class="divider"></div>
    </div>

    <!-- Search -->
    <div class="mb-3">
        <input type="text" id="searchContract" class="form-control"
            placeholder="Search by student name or student ID">
    </div>

    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="contractsTable">
                <thead class="table-maroon text-white">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Contract</th>
                        <th>Signed At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) < 1) {
                        echo "<tr><td colspan='6' class='text-center'>No contracts found.</td></tr>";
                    } else {
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $badge = match ($row['status']) {
                                'Signed' => 'bg-success',
                                'Expired' => 'bg-secondary',
                                default => 'bg-danger'
                            };
                    ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
                                    <small class="text-muted"><?= $row['student_id'] ?></small>
                                </td>
                                <td>
                                    <a href="view_contract.php?id=<?= $row['id'] ?>"
                                        class="btn btn-outline-maroon btn-sm"
                                        target="_blank">
                                        <i class="bi bi-file-earmark-text"></i> View Contract
                                    </a>
                                </td>
                                <td><?= date('F d, Y h:i A', strtotime($row['signed_at'])) ?></td>
                                <td>
                                    <span class="badge <?= $badge ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-outline-warning btn-sm edit-contract"
                                        data-id="<?= $row['id'] ?>"
                                        data-status="<?= $row['status'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editContractModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-outline-danger btn-sm delete-contract"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteContractModal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <p class="text-center text-muted mt-3 d-none" id="noMatchText">
                No matched contracts found.
            </p>
        </div>
    </div>
</div>
<div class="modal fade" id="editContractModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <form method="POST" action="../includes/processes.php">
                <input type="hidden" name="contract_id" id="edit_contract_id">

                <div class="modal-header">
                    <h5 class="modal-title text-maroon fw-semibold">
                        <i class="bi bi-pencil-square"></i> Edit Contract Status
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>

                <div class="modal-body">
                    <label>Status</label>
                    <select class="form-select" name="status" id="edit_status">
                        <option value="Signed">Signed</option>
                        <option value="Expired">Expired</option>
                        <option value="Terminated">Terminated</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-gold" type="submit" name="updateContract">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteContractModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <form method="POST" action="../includes/processes.php">
                <input type="hidden" name="contract_id" id="delete_contract_id">

                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-semibold">
                        <i class="bi bi-exclamation-triangle"></i> Delete Contract
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>Delete contract of <strong id="delete_contract_name"></strong>?</p>
                    <p class="text-muted small">This cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" name="deleteContract">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Search
    document.getElementById('searchContract').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#contractsTable tbody tr');
        let visible = 0;

        rows.forEach(row => {
            if (row.textContent.toLowerCase().includes(value)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noMatchText')
            .classList.toggle('d-none', visible !== 0);
    });

    // Edit modal
    document.addEventListener('click', e => {
        const btn = e.target.closest('.edit-contract');
        if (!btn) return;

        edit_contract_id.value = btn.dataset.id;
        edit_status.value = btn.dataset.status;
    });

    // Delete modal
    document.addEventListener('click', e => {
        const btn = e.target.closest('.delete-contract');
        if (!btn) return;

        delete_contract_id.value = btn.dataset.id;
        delete_contract_name.textContent = btn.dataset.name;
    });
</script>
<?php include 'includes/footer.php'; ?>