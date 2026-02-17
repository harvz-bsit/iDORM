    <?php
    $pageTitle = "Rooms Management";
    include 'includes/header.php';
    ?>

    <div class="container py-5 min-vh-100">
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

            <div class="table-responsive" style="min-height: 300px;">
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
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewRoomModal"
                                                    data-room="<?= $room['room_number']; ?>"
                                                    data-capacity="<?= $room['capacity']; ?>"
                                                    data-occupied="<?= $room['occupied']; ?>">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item edit-room-btn" data-id="<?= $room['id'] ?>"
                                                    data-room="<?= $room['room_number'] ?>"
                                                    data-capacity="<?= $room['capacity'] ?>"
                                                    data-status="<?= $room['status'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRoomModal">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger delete-room-btn"
                                                    data-id="<?= $room['id'] ?>"
                                                    data-room="<?= $room['room_number'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRoomModal">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
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

    <div class="modal fade" id="editRoomModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title text-maroon fw-semibold">
                        <i class="bi bi-pencil-square"></i> Edit Room
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="../includes/processes.php">
                    <input type="hidden" name="room_id" id="edit_room_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" name="room_number" id="edit_room_number" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" name="capacity" id="edit_capacity" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="Available">Available</option>
                                <option value="Full">Full</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-maroon" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-gold" name="updateRoom" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteRoomModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title text-danger fw-semibold">
                        <i class="bi bi-exclamation-triangle"></i> Delete Room
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="../includes/processes.php">
                    <input type="hidden" name="room_id" id="delete_room_id">

                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete room
                            <strong id="delete_room_number"></strong>?
                        </p>
                        <p class="text-muted small">
                            All room assignments will be removed.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger" name="deleteRoom" type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Edit Room Modal Population
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.edit-room-btn');
            if (!btn) return;

            document.getElementById('edit_room_id').value = btn.dataset.id;
            document.getElementById('edit_room_number').value = btn.dataset.room;
            document.getElementById('edit_capacity').value = btn.dataset.capacity;
            document.getElementById('edit_status').value = btn.dataset.status;
        });

        // Delete Room Modal Population
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-room-btn');
            if (!btn) return;

            document.getElementById('delete_room_id').value = btn.dataset.id;
            document.getElementById('delete_room_number').textContent = btn.dataset.room;
        });


        document.getElementById('viewRoomModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const room = button.getAttribute('data-room');
            const capacity = parseInt(button.getAttribute('data-capacity'));
            const occupied = parseInt(button.getAttribute('data-occupied'));

            document.getElementById('room_number').textContent = room;

            const assignBtn = document.getElementById('assignModalBtn');
            assignBtn.setAttribute('data-room', room);

            // 🚫 Disable assign if full
            if (occupied >= capacity) {
                assignBtn.disabled = true;
                assignBtn.classList.add('disabled');
                assignBtn.innerHTML = `<i class="bi bi-lock"></i> Room Full`;
            } else {
                assignBtn.disabled = false;
                assignBtn.classList.remove('disabled');
                assignBtn.innerHTML = `<i class="bi bi-person-plus"></i> Assign Student`;
            }

            // Load occupants
            fetch(`includes/get_assigned_students.php?room_number=${room}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('occupants').innerHTML = html;
                });
        });

        document.getElementById('occupants').addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-assignment');
            if (!btn) return;

            const studentId = btn.dataset.student;
            const roomNumber = btn.dataset.room;

            fetch(`includes/remove_assignment.php?student_id=${studentId}&room_number=${roomNumber}`)
                .then(() => {
                    fetch(`includes/get_assigned_students.php?room_number=${roomNumber}`)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('occupants').innerHTML = html;
                        });
                });
        });

        document.getElementById('assignModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const room = button.getAttribute('data-room');

            this.querySelector('.modal-title').textContent = `Assign Student to Room ${room}`;
            document.getElementById('assign_room_id').value = room;

            fetch(`includes/get_unassigned_students.php?room_id=${room}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('assignModalBody').innerHTML = html;
                });

            const selectAll = document.getElementById('selectAll');
            selectAll.checked = false;
            selectAll.onchange = function() {
                document
                    .querySelectorAll('#assignModalBody input[type="checkbox"]')
                    .forEach(cb => cb.checked = this.checked);
            };
        });
    </script>


    <?php include 'includes/footer.php'; ?>