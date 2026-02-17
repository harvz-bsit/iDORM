<?php
$pageTitle = "Manage Requests";
include 'includes/header.php';
?>
<style>
    /* Force inactive tab text color */
    .nav-tabs .nav-link {
        color: #800000 !important;
        /* Maroon, use !important to override Bootstrap */
    }

    /* Force active tab text and background */
    .nav-tabs .nav-link.active {
        color: #fff !important;
        /* White text */
        background-color: #800000 !important;
        /* Maroon background */
        border-color: #800000 #800000 #fff !important;
    }

    /* Optional: Hover state for inactive tabs */
    .nav-tabs .nav-link:hover {
        color: #b30000 !important;
        /* Slightly brighter maroon */
    }
</style>
<div class="container py-5 min-vh-100">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Student Requests</h2>
        <p class="text-muted">Manage maintenance requests, complaints, and passlip requests.</p>
    </div>

    <!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs mb-4" id="requestsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">Maintenance Requests</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="complaints-tab" data-bs-toggle="tab" data-bs-target="#complaints" type="button" role="tab">Complaints</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="passlips-tab" data-bs-toggle="tab" data-bs-target="#passlips" type="button" role="tab">Passlip Requests</button>
        </li>
    </ul>

    <div class="tab-content" id="requestsTabContent">
        <!-- Maintenance Requests Tab -->
        <div class="tab-pane fade show active" id="maintenance" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-maroon mb-3">Maintenance Requests</h5>
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Room</th>
                                    <th>Issue</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT mr.*, u.full_name, ra.room_num 
                                          FROM maintenance_requests mr
                                          JOIN user_personal_information u ON mr.student_id = u.student_id
                                          JOIN room_assignments ra ON ra.student_id = u.student_id
                                          ORDER BY mr.id DESC";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) == 0) {
                                    echo '<tr><td colspan="6" class="text-center text-muted">No maintenance requests found.</td></tr>';
                                } else {
                                    while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['room_num']; ?></td>
                                            <td><?php echo $row['issue']; ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'Pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Resolved</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($row['requested_at'])); ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'Pending'): ?>
                                                    <form method="POST" action="../includes/processes.php" class="d-inline">
                                                        <input type="hidden" name="maintenance_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" name="resolve_maintenance" class="btn btn-sm btn-success">Mark Resolved</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaints Tab -->
        <div class="tab-pane fade" id="complaints" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-maroon mb-3">Complaints</h5>
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Subject</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT c.*, u.full_name 
                                          FROM complaints c
                                          JOIN user_personal_information u ON c.student_id = u.student_id
                                          ORDER BY c.id DESC";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) == 0) {
                                    echo '<tr><td colspan="6" class="text-center text-muted">No complaints found.</td></tr>';
                                } else {
                                    while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['subject']; ?></td>
                                            <td><?php echo $row['details']; ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'Pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Addressed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($row['submitted_at'])); ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'Pending'): ?>
                                                    <form method="POST" action="../includes/processes.php" class="d-inline">
                                                        <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" name="resolve_complaint" class="btn btn-sm btn-success">Mark Addressed</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passlip Requests Tab -->
        <div class="tab-pane fade" id="passlips" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-maroon mb-3">Passlip Requests</h5>
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Departure</th>
                                    <th>Return</th>
                                    <th>Destination</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT p.*, u.full_name 
                                          FROM passlips p
                                          JOIN user_personal_information u ON p.student_id = u.student_id
                                          ORDER BY p.id DESC";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) == 0) {
                                    echo '<tr><td colspan="8" class="text-center text-muted">No passlips found.</td></tr>';
                                } else {
                                while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['departure_date'] . ' ' . $row['departure_time']; ?></td>
                                        <td><?php echo $row['return_date'] . ' ' . $row['return_time']; ?></td>
                                        <td><?php echo $row['destination']; ?></td>
                                        <td><?php echo $row['purpose']; ?></td>
                                        <td>
                                            <?php if ($row['status'] === 'Pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($row['status'] === 'Approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php if ($row['status'] === 'Pending'): ?>
                                                <form method="POST" action="../includes/processes.php" class="d-inline">
                                                    <input type="hidden" name="passlip_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="approve_passlip" class="btn btn-sm btn-success">Approve</button>
                                                    <button type="submit" name="reject_passlip" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; 
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>