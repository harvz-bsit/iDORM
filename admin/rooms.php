    <?php
    $pageTitle = "Rooms Management";
    include 'includes/header.php';
    ?>

    <div class="container py-5 vh-100">
        <!-- Header -->
        <div class="dashboard-header">
            <h1><i class="bi bi-house-door"></i> Manage <span class="text-gold">Rooms</span></h1>
            <p class="lead">View and manage all dorm rooms and occupants</p>
            <div class="divider"></div>
        </div>

        <!-- ======= ROOMS MANAGEMENT ======= -->
        <div class="dashboard-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold text-maroon"><i class="bi bi-door-open"></i> Room List</h5>
                <button class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    <i class="bi bi-plus-circle"></i> Add Room
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Room No.</th>
                            <th scope="col">Capacity</th>
                            <th scope="col">Occupied</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //Fetch rooms
                        $query = "SELECT * FROM rooms";
                        $result = mysqli_query($conn, $query);
                        while ($room = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php echo $room['room_number']; ?></td>
                                <td><?php echo $room['capacity']; ?></td>
                                <td><?php echo $room['occupied']; ?></td>
                                <td><?php
                                    $status = $room['status'];
                                    $badgeClass = ($status == 'Available') ? 'bg-success' : (($status == 'Full') ? 'bg-danger' : 'bg-secondary');
                                    echo "<span class='badge $badgeClass'>$status</span>";
                                    ?></td>
                                <td>
                                    <button class="btn btn-outline-maroon btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewRoomModal" data-room="<?php echo $room['room_number']; ?>"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ======= VIEW ROOM MODAL ======= -->
        <div class="modal fade" id="viewRoomModal" tabindex="-1" aria-labelledby="viewRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title text-maroon fw-semibold"><i class="bi bi-people"></i> Room <span id="room_number"></span> Occupants</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold text-dark">Current Occupants</h6>
                            <button class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal" id="assignModalBtn">
                                <i class="bi bi-person-plus"></i> Assign Student
                            </button>
                        </div>
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="occupants">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-maroon" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======= ASSIGN STUDENT MODAL ======= -->
        <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title text-maroon fw-semibold"><i class="bi bi-person-plus"></i> Assign Student to Room 101</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="assignForm" method="POST" action="../includes/processes.php">
                        <input type="hidden" name="room_id" id="assign_room_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Select Student(s)</label>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Student ID</th>
                                                <th>Full Name</th>
                                                <th>Course</th>
                                                <th>Year</th>
                                            </tr>
                                        </thead>
                                        <tbody id="assignModalBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-maroon" type="button" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-gold" name="assignRoom" type="submit">Assign Selected</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ======= ADD ROOM MODAL ======= -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title text-maroon fw-semibold"><i class="bi bi-plus-circle"></i> Add New Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="../includes/processes.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Room Number</label>
                                <input type="text" class="form-control" id="room_number" name="room_number" placeholder="e.g., 101" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacity</label>
                                <input type="number" class="form-control" id="room_capacity" name="room_capacity" placeholder="e.g., 4" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="room_status" name="room_status" required>
                                    <option value="Available">Available</option>
                                    <option value="Full">Full</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-maroon" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-gold" type="submit" name="room_save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Select all checkboxes at once
        document.addEventListener("DOMContentLoaded", function() {
            const selectAll = document.getElementById("selectAll");
            if (selectAll) {
                selectAll.addEventListener("change", function() {
                    const checkboxes = document.querySelectorAll('input[name="students[]"]');
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });
            }
        });

        // Populate View Room Modal with data
        document.getElementById('viewRoomModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const room = button.getAttribute('data-room');

            document.getElementById('room_number').textContent = room;

            const assBtn = document.getElementById('assignModalBtn');
            assBtn.setAttribute('data-room', room);

            // Fetch occupants via AJAX
            fetch(`includes/get_assigned_students.php?room_number=${room}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('occupants').innerHTML = html;
                });
        });

        document.getElementById('assignModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const room = button.getAttribute('data-room');

            const modalTitle = this.querySelector('.modal-title');
            modalTitle.textContent = `Assign Student to Room ${room}`;

            document.getElementById('assign_room_id').value = room;

            // Fetch unassigned students via AJAX
            fetch(`includes/get_unassigned_students.php?room_id=${room}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('assignModalBody').innerHTML = html;
                });

            // Re-attach "select all" behavior
            const selectAll = document.getElementById("selectAll");
            selectAll.checked = false;
            selectAll.addEventListener("change", function() {
                const checkboxes = document.querySelectorAll('#assignModalBody input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        });
    </script>

    <?php include 'includes/footer.php'; ?>