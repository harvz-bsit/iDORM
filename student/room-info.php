<?php
$pageTitle = "Room Information";
include 'includes/header.php';
?>

<div class="container py-5 min-vh-100">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Room Information</h2>
        <p class="text-muted">Check your room information and roommates.</p>
    </div>
    <div class="row g-4">
        <?php
        $check_approval = "SELECT status FROM application_approvals WHERE student_id = '$student_id' ORDER BY id DESC LIMIT 1";
        $approval_result = mysqli_query($conn, $check_approval);
        $approval_row = mysqli_fetch_assoc($approval_result);

        if ($approval_row && $approval_row['status'] === 'Approved') {
        ?>
            <!-- LEFT COLUMN (Sticky Info: Room Details + Roommates) -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    <!-- Room Details -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="text-maroon fw-bold mb-3">🏠 Room Details</h5>
                            <?php
                            $getroom = "SELECT * FROM room_assignments WHERE student_id = '$student_id' LIMIT 1";
                            $room_result = mysqli_query($conn, $getroom);
                            $room_row = mysqli_fetch_assoc($room_result);
                            if ($room_row) {
                                $room_number = $room_row['room_num'];

                                $getroominfo = "SELECT * FROM rooms WHERE room_number = '$room_number' LIMIT 1";
                                $roominfo_result = mysqli_query($conn, $getroominfo);
                                $roominfo_row = mysqli_fetch_assoc($roominfo_result);

                                $capacity = $roominfo_row['capacity'];
                                $occupied = $roominfo_row['occupied'];
                            ?>
                                <p><strong>Room Number:</strong> <?php echo $room_number; ?></p>
                                <p><strong>Capacity:</strong> <?php echo $capacity; ?> Occupant/s</p>
                                <p><strong>Occupied:</strong> <?php echo $occupied; ?> Occupant/s</p>
                                <p><strong>Rate:</strong> ₱ 750 / month</p>
                            <?php
                            } else {
                                echo "<p class='text-muted'>No room assigned yet.</p>";
                            }
                            ?>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changeRoomModal">
                                Request Change Room
                            </button>
                        </div>
                    </div>

                    <!-- Roommates -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="text-maroon fw-bold mb-3">👭 Roommates</h5>
                            <ul class="list-group list-group-flush small">
                                <?php
                                if ($room_row) {
                                    $getroommates = "
        SELECT sa.student_id, s.full_name 
        FROM room_assignments sa 
        JOIN user_personal_information s ON sa.student_id = s.student_id
        WHERE sa.room_num = '$room_number' AND sa.student_id != '$student_id'
        ORDER BY s.full_name ASC
    ";
                                    $roommates_result = mysqli_query($conn, $getroommates);
                                    if (mysqli_num_rows($roommates_result) == 0) {
                                        echo "<li class='list-group-item text-muted'>No roommates assigned.</li>";
                                    } else {
                                        while ($roommate = mysqli_fetch_assoc($roommates_result)) {
                                            echo "<li class='list-group-item'>
                    <i class='bi bi-person-fill text-maroon me-2'></i> " . htmlspecialchars(ucwords($roommate['full_name'])) . "
                  </li>";
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (Scrollable content: Maintenance + Complaints) -->
            <div class="col-lg-8">
                <!-- Rules and Regulations -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="text-maroon fw-bold mb-3">📜 Dormitory Rules and Regulations</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">1. Maintain cleanliness in your room and common areas.</li>
                            <li class="list-group-item">2. No loud noises after 10 PM to ensure a peaceful environment.</li>
                            <li class="list-group-item">3. Visitors are not allowed in the dormitory rooms.</li>
                            <li class="list-group-item">4. Report any mainte nance issues promptly to the administration.</li>
                            <li class="list-group-item">5. Follow the dormitory's security protocols at all times.</li>
                        </ul>
                    </div>
                </div>
                <!-- Maintenance Request -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="text-maroon fw-bold mb-3">🧰 Request Maintenance</h5>
                        <form id="maintenanceForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Issue Description</label>
                                <textarea class="form-control" name="issue" rows="3" placeholder="Describe the issue (e.g., leaking faucet, broken light)" required></textarea>
                            </div>
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                            <div class="text-end">
                                <button type="button" class="btn btn-gold px-4" id="submitMaintenanceBtn">Submit Request</button>
                            </div>
                        </form>
                        <div id="maintenanceAlert" class="alert d-none mt-2"></div>
                    </div>
                </div>

                <!-- Complaints -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="text-maroon fw-bold mb-3">📢 Submit a Complaint</h5>
                        <form id="complaintForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" class="form-control" name="subject" placeholder="e.g., Noise issue, roommate concern" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Details</label>
                                <textarea class="form-control" name="details" rows="3" placeholder="Describe your concern in detail..." required></textarea>
                            </div>
                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                            <div class="text-end">
                                <button type="button" class="btn btn-gold px-4" id="submitComplaintBtn">Submit Complaint</button>
                            </div>
                        </form>
                        <div id="complaintAlert" class="alert d-none mt-2"></div>
                    </div>
                </div>

            </div>

        <?php
        } else {
        ?>
            <div class="col-12">
                <div class="alert alert-warning text-center" role="alert">
                    Your application is not approved yet. Payment details will be available once approved.
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<!-- Request Change Room Modal -->
<div class="modal fade" id="changeRoomModal" tabindex="-1" aria-labelledby="changeRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="changeRoomModalLabel">Request Room Change</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="changeRoomForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select a Room</label>
                        <select class="form-select" name="new_room" required>
                            <option value="" disabled selected>Choose a room</option>
                            <?php
                            // Fetch rooms that are not full
                            $rooms_query = "SELECT room_number, capacity, occupied 
                                            FROM rooms 
                                            WHERE occupied < capacity 
                                            ORDER BY room_number ASC";
                            $rooms_result = mysqli_query($conn, $rooms_query);
                            while ($room = mysqli_fetch_assoc($rooms_result)) {
                                echo "<option value='{$room['room_number']}'>Room {$room['room_number']} ({$room['occupied']}/{$room['capacity']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
                <div id="changeRoomAlert" class="alert d-none mt-3"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-maroon" id="submitChangeRoomBtn">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('submitChangeRoomBtn').addEventListener('click', function() {
        const form = document.getElementById('changeRoomForm');
        const formData = new FormData(form);

        fetch('../includes/process_change_room.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertDiv = document.getElementById('changeRoomAlert');
                alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
                if (data.status === 'success') {
                    alertDiv.classList.add('alert', 'alert-success');
                    alertDiv.textContent = data.message;
                    form.reset();
                    // Close modal after a short delay
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('changeRoomModal'));
                        modal.hide();
                        alertDiv.classList.add('d-none');
                    }, 1500);
                } else {
                    alertDiv.classList.add('alert', 'alert-danger');
                    alertDiv.textContent = data.message;
                }
            })
            .catch(err => console.error(err));
    });
</script>
<script>
    // Maintenance Request Submission
    document.getElementById('submitMaintenanceBtn').addEventListener('click', function() {
        const form = document.getElementById('maintenanceForm');
        const formData = new FormData(form);

        fetch('../includes/processes.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertDiv = document.getElementById('maintenanceAlert');
                alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');

                if (data.status === 'success') {
                    alertDiv.classList.add('alert', 'alert-success');
                    alertDiv.textContent = data.message;
                    form.reset();
                } else {
                    alertDiv.classList.add('alert', 'alert-danger');
                    alertDiv.textContent = data.message;
                }
            })
            .catch(err => console.error(err));
    });

    // Complaint Submission
    document.getElementById('submitComplaintBtn').addEventListener('click', function() {
        const form = document.getElementById('complaintForm');
        const formData = new FormData(form);

        fetch('../includes/processes.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertDiv = document.getElementById('complaintAlert');
                alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');

                if (data.status === 'success') {
                    alertDiv.classList.add('alert', 'alert-success');
                    alertDiv.textContent = data.message;
                    form.reset();
                } else {
                    alertDiv.classList.add('alert', 'alert-danger');
                    alertDiv.textContent = data.message;
                }
            })
            .catch(err => console.error(err));
    });
</script>


<?php include 'includes/footer.php'; ?>