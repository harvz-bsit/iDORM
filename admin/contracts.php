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
<style>
    /* Ensure modal scroll works */
    .modal-dialog-scrollable {
        display: flex;
        max-height: 90vh;
        /* limits modal height to viewport */
    }

    .modal-dialog-scrollable .modal-content {
        flex: 1 1 auto;
        overflow: hidden;
        /* content itself does not scroll, modal-body will */
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto;
        /* vertical scroll */
        -webkit-overflow-scrolling: touch;
        /* smooth scrolling on mobile */
        max-height: 70vh;
        /* adjust based on modal header/footer size */
    }
</style>
<div class="container py-5 min-vh-100">
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
        <div class="table-responsive" style="min-height: 300px;">
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
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item edit-contract"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-status="<?= $row['status'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editContractModal">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            </li>
                                            <?php
                                            $studentid = $row['student_id'];
                                            $selectroomnumber = "SELECT room_num FROM room_assignments WHERE student_id = '$studentid'";
                                            $srn_result = mysqli_query($conn, $selectroomnumber);
                                            $srn_row = mysqli_fetch_assoc($srn_result);

                                            $selecthomeaddress = "SELECT address FROM user_personal_information WHERE student_id = '$studentid'";
                                            $sha = mysqli_query($conn, $selecthomeaddress);
                                            $sha_row = mysqli_fetch_assoc($sha);

                                            if ($row['status'] !== "Checked Out") {
                                            ?>
                                                <li>
                                                    <button class="dropdown-item text-primary checkout-contract"
                                                        data-id="<?= $row['id'] ?>"
                                                        data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                                        data-student="<?= $row['student_id'] ?>"
                                                        data-room_num="<?= $srn_row['room_num'] ?>"
                                                        data-address="<?= $sha_row['address'] ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#checkoutModal">
                                                        <i class="bi bi-box-arrow-right"></i> Check-Out
                                                    </button>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li>
                                                    <a href="view_checkout.php?id=<?= $row['id'] ?>" class="dropdown-item text-success" target="_blank">
                                                        <i class="bi bi-eye"></i> View Checkout Form
                                                    </a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            <li>
                                                <button class="dropdown-item text-danger delete-contract"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteContractModal">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
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
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header OUTSIDE form to prevent accidental submit -->
            <div class="modal-header">
                <h5 class="modal-title text-maroon fw-semibold">
                    <i class="bi bi-box-arrow-right"></i>
                    LADIES DORMITORY RESIDENT CHECK-OUT FORM
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Form -->
            <form method="POST" action="../includes/processes.php">

                <input type="hidden" name="contract_id" id="checkout_contract_id">

                <!-- Modal Body -->
                <div class="modal-body p-3">
                    <!-- Resident Info -->
                    <h6 class="fw-bold">Resident Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Full Name</label>
                            <input type="text" class="form-control" id="checkout_name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Room/Unit Number</label>
                            <input type="text" name="checkout_room" id="checkout_room" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Permanent Home Address</label>
                        <textarea name="home_address" id="checkout_address" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Reason for Check-Out</label><br>
                        <input type="checkbox" name="reason[]" value="End of Semester"> End of Semester<br>
                        <input type="checkbox" name="reason[]" value="Graduation"> Graduation / Completion<br>
                        <input type="checkbox" name="reason[]" value="Transfer"> Transfer Institution<br>
                        <input type="checkbox" name="reason[]" value="Violation"> Violation of Rules<br>
                        <input type="checkbox" name="reason[]" value="Personal"> Personal / Family Reasons<br>
                        <input type="checkbox" name="reason[]" value="Other"> Other
                    </div>

                    <hr>

                    <!-- Check-Out Details -->
                    <h6 class="fw-bold">Check-Out Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Official Check-Out Date</label>
                            <input type="date" name="checkout_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Official Check-Out Time</label>
                            <input type="time" name="checkout_time" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>New Contact Number</label>
                            <input type="text" name="new_contact" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <!-- Financial -->
                    <h6 class="fw-bold">Financial Clearance</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Outstanding Fees (PHP)</label>
                            <input type="number" step="0.01" name="outstanding_fees" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Damage Charges (PHP)</label>
                            <input type="number" step="0.01" name="damage_charges" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Total Due (PHP)</label>
                            <input type="number" step="0.01" name="total_due" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <!-- Approval -->
                    <div class="mt-4 p-3 border rounded bg-light">
                        <p class="small text-muted mb-1">
                            This check-out form has been reviewed and approved using <strong>iDORM System</strong>.
                        </p>
                        <p class="mb-0">
                            Approved By: <strong>Cristine Joy O. Pera, MSCJ</strong><br>
                            Dormitory Manager
                        </p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="processCheckout">Confirm Check-Out</button>
                </div>

            </form>

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
                <form method="POST" action="../includes/processes.php">
                    <input type="hidden" name="contract_id" id="delete_contract_id">
                    <button class="btn btn-danger" name="deleteContract">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', e => {
        const btn = e.target.closest('.checkout-contract');
        if (!btn) return;

        checkout_contract_id.value = btn.dataset.id;
        checkout_name.value = btn.dataset.name;
        checkout_room.value = btn.dataset.room_num;
        checkout_address.value = btn.dataset.address;
    });
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