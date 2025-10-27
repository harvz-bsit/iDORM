<?php
$pageTitle = "Rooms Management";
include 'includes/header.php';
?>

<div class="container py-5">
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
                    <!-- Example Room Data -->
                    <tr>
                        <td>101</td>
                        <td>4</td>
                        <td>3</td>
                        <td><span class="badge bg-success">Available</span></td>
                        <td>
                            <button class="btn btn-outline-maroon btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewRoomModal"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td>4</td>
                        <td>4</td>
                        <td><span class="badge bg-danger">Full</span></td>
                        <td>
                            <button class="btn btn-outline-maroon btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewRoomModal"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>103</td>
                        <td>4</td>
                        <td>2</td>
                        <td><span class="badge bg-secondary">Under Maintenance</span></td>
                        <td>
                            <button class="btn btn-outline-maroon btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewRoomModal"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ======= VIEW ROOM MODAL ======= -->
    <div class="modal fade" id="viewRoomModal" tabindex="-1" aria-labelledby="viewRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title text-maroon fw-semibold"><i class="bi bi-people"></i> Room 101 Occupants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold text-dark">Current Occupants</h6>
                        <button class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal">
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
                        <tbody>
                            <!-- Example Data -->
                            <tr>
                                <td>2024-001</td>
                                <td>Maria Santos</td>
                                <td>09171234567</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td><button class="btn btn-outline-danger btn-sm"><i class="bi bi-person-dash"></i></button></td>
                            </tr>
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
                <form>
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
                                            <th>Year & Course</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example Available Students -->
                                        <tr>
                                            <td><input type="checkbox" name="students[]" value="2024-005"></td>
                                            <td>2024-005</td>
                                            <td>Clara Bautista</td>
                                            <td>BSN 3A</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="students[]" value="2024-006"></td>
                                            <td>2024-006</td>
                                            <td>Ella Cruz</td>
                                            <td>BSCS 2B</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-maroon" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-gold" type="submit">Assign Selected</button>
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
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" placeholder="e.g., 101" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" placeholder="e.g., 4" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="Available">Available</option>
                                <option value="Full">Full</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-maroon" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-gold" type="submit">Save</button>
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
</script>

<?php include 'includes/footer.php'; ?>