<?php
$pageTitle = "Manage Announcements";
include 'includes/header.php';
?>

<div class="container py-5 min-vh-100">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-maroon">Announcements</h2>
        <p class="text-muted">Create, edit, and manage dormitory announcements.</p>
    </div>

    <!-- Add New Announcement Button -->
    <div class="text-end mb-4">
        <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
            <i class="bi bi-plus-lg me-1"></i> Add Announcement
        </button>
    </div>

    <!-- Announcements Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Date Posted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM announcements ORDER BY date_posted DESC";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) == 0) {
                            echo '<tr><td colspan="5" class="text-center text-muted">No announcements found.</td></tr>';
                        } else {
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['date_posted'])); ?></td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editAnnouncementModal"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                            data-message="<?php echo htmlspecialchars($row['description']); ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="../includes/processes.php" class="d-inline">
                                            <input type="hidden" name="announcement_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_announcement" class="btn btn-outline-danger btn-sm me-1"><i class="bi bi-trash"></i></button>
                                        </form>
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

<!-- Add Announcement Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="../includes/processes.php">
                <div class="modal-header bg-maroon text-white">
                    <h5 class="modal-title">Add Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_announcement" class="btn btn-maroon">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="../includes/processes.php">
                <input type="hidden" name="announcement_id" id="editAnnouncementId">
                <div class="modal-header bg-maroon text-white">
                    <h5 class="modal-title">Edit Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" class="form-control" name="title" id="editAnnouncementTitle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" name="message" id="editAnnouncementMessage" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_announcement" class="btn btn-maroon">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Populate edit modal with existing data
    var editModal = document.getElementById('editAnnouncementModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        document.getElementById('editAnnouncementId').value = button.getAttribute('data-id');
        document.getElementById('editAnnouncementTitle').value = button.getAttribute('data-title');
        document.getElementById('editAnnouncementMessage').value = button.getAttribute('data-message');
    });
</script>

<?php include 'includes/footer.php'; ?>