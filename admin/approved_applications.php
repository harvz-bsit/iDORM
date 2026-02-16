<?php
$pageTitle = "Approved Applications";
include 'includes/header.php';
include '../config/conn.php';

// Fetch pending applicants
$result = $conn->query("SELECT * FROM application_approvals WHERE status='Approved'");
?>

<div class="container py-5 vh-100">
    <div class="dashboard-header">
        <h1 class="fw-bold text-maroon">Approved Applications</h1>
        <p class="lead">Review approved applications.</p>
        <div class="divider"></div>
    </div>

    <div class="my-4 d-flex justify-content-end">
        <!-- Buttons for pending and rejected table  -->
        <a href="applications.php" class="btn btn-primary me-2">
            <i class="bi bi-hourglass-split"></i> Pending
        </a>
        <a href="rejected_applications.php" class="btn btn-danger">
            <i class="bi bi-x-circle"></i> Rejected
        </a>
    </div>

    <div class="mb-3">

        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">Search</span>
            <input type="text" id="searchInput" class="form-control" placeholder="name, email, course, or year...">
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow-sm p-4">
        <div class="table-responsive" style="min-height: 300px;">
            <table class="table table-hover align-middle">
                <thead class="table-maroon text-white">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;

                    if ($result->num_rows < 1) {
                        echo '<tr><td colspan="8" class="text-center text-muted">No approved applications found.</td></tr>';
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $student_id = $row['student_id'];

                            // Fetch personal information
                            $result_info = mysqli_query($conn, "SELECT * FROM user_personal_information WHERE student_id='$student_id'");
                            $row_info = mysqli_fetch_assoc($result_info);

                            // Fetch email
                            $email_result = mysqli_query($conn, "SELECT email FROM users WHERE student_id='$student_id'");
                            $email_row = mysqli_fetch_assoc($email_result);

                            // Fetch Educational Background
                            $edu_result = mysqli_query($conn, "SELECT * FROM user_educational_background WHERE student_id='$student_id'");
                            $edu_row = mysqli_fetch_assoc($edu_result);
                    ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($row_info['full_name']) ?></td>
                                <td><?= htmlspecialchars($email_row['email']) ?></td>
                                <td><?= htmlspecialchars($edu_row['course']) ?></td>
                                <td><?= htmlspecialchars($edu_row['year_level']) ?></td>
                                <td><?= date("F d, Y", strtotime($row['application_date'])) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($row['status'] === 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-maroon viewBtn"
                                        data-id="<?= $student_id ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== Modal for Applicant Details ===== -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="viewModalLabel"><i class="bi bi-person-lines-fill"></i> Applicant Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center text-muted py-4">
                    <div class="spinner-border text-maroon" role="status"></div>
                    <p class="mt-2">Loading applicant details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" id="approveBtn">Approve</button>
                <button class="btn btn-danger" id="rejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.viewBtn').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.dataset.id;
            document.getElementById('modalContent').innerHTML = `
            <div class="text-center text-muted py-4">
                <div class="spinner-border text-maroon" role="status"></div>
                <p class="mt-2">Loading applicant details...</p>
            </div>
        `;
            document.getElementById('approveBtn').setAttribute('data-id', studentId);
            document.getElementById('rejectBtn').setAttribute('data-id', studentId);

            fetch('fetch_applicant.php?id=' + studentId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = data;
                });
        });
    });

    // Handle Approve / Reject Actions
    ['approveBtn', 'rejectBtn'].forEach(btnId => {
        document.getElementById(btnId).addEventListener('click', function() {
            const studentId = this.dataset.id;
            const action = btnId === 'approveBtn' ? 'approve' : 'reject';
            const confirmText = action === 'approve' ? 'approve this applicant' : 'reject this applicant';

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${confirmText}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'approve' ? '#198754' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${action}`,
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../includes/approval_action.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                action,
                                student_id: studentId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                }
            });
        });
    });
</script>
<script>
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('table tbody');

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        let visibleCount = 0;

        Array.from(tableBody.rows).forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show "No matching applicants" row if nothing is visible
        let noMatchRow = document.getElementById('noMatchRow');

        if (!noMatchRow) {
            noMatchRow = document.createElement('tr');
            noMatchRow.id = 'noMatchRow';
            noMatchRow.innerHTML = `<td colspan="8" class="text-center text-muted">No matching applicants found.</td>`;
            tableBody.appendChild(noMatchRow);
        }

        noMatchRow.style.display = visibleCount === 0 ? '' : 'none';
    });
</script>


<?php include 'includes/footer.php'; ?>