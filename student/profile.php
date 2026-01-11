<?php
$pageTitle = "My Profile";
include 'includes/header.php';


?>

<div class="container py-5 min-vh-100">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-maroon mb-4">👤 Edit My Information</h4>

                    <!-- Alert placeholder -->
                    <?php
                    if (isset($_GET['success']) && $_GET['success'] == 'Password changed successfully.') {
                    ?>
                        <div id="alertBox" class="alert alert-success" role="alert">
                            Password updated successfully!
                        </div>
                    <?php
                    } else if (isset($_GET['success']) && $_GET['success'] == 'Profile updated successfully.') {
                    ?>
                        <div id="alertBox" class="alert alert-success" role="alert">
                            Profile updated successfully!
                        </div>
                    <?php
                    } else if (isset($_GET['error'])) {
                    ?>
                        <div id="alertBox" class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php
                    }
                    ?>

                    <form id="profileForm" method="POST" action="../includes/processes.php">
                        <div class="row g-3">

                            <!-- Student ID (view only) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Student ID</label>
                                <input type="text" class="form-control" name="student_id" value="<?= $personalInfoRow['student_id'] ?>" readonly>
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="full_name" class="form-control" value="<?= $personalInfoRow['full_name'] ?>" readonly>
                            </div>

                            <!-- Nickname (view only) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nickname</label>
                                <input type="text" class="form-control" name="nickname" value="<?= $personalInfoRow['nickname'] ?>">
                            </div>

                            <!-- Contact -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Number</label>
                                <input type="text" name="contact" class="form-control" value="<?= $personalInfoRow['contact']; ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= $student['email']; ?>" required>
                            </div>

                            <!-- Address -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Home Address</label>
                                <input type="text" name="address" class="form-control" value="<?= $personalInfoRow['address']; ?>" required>
                            </div>

                            <hr class="my-4">

                            <h5 class="fw-bold text-green mb-3">👪 Family Information <small>(Put <b>N/A</b> if Not Applicable)</small></h5>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control" value="<?= $familyInfoRow['guardian_name']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Guardian Contact</label>
                                <input type="text" name="guardian_contact" class="form-control" value="<?= $familyInfoRow['guardian_contact']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Guardian Relation</label>
                                <input type="text" name="guardian_relation" class="form-control" value="<?= $familyInfoRow['guardian_relation']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Father's Name</label>
                                <input type="text" name="father_name" class="form-control" value="<?= $familyInfoRow['father_name']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Father's Contact</label>
                                <input type="text" name="father_contact" class="form-control" value="<?= $familyInfoRow['father_contact']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control" value="<?= $familyInfoRow['mother_name']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mother's Contact</label>
                                <input type="text" name="mother_contact" class="form-control" value="<?= $familyInfoRow['mother_contact']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Parent Income (Monthly)</label>
                                <input type="text" name="parent_income" class="form-control" value="<?= $familyInfoRow['parent_income']; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Number of Siblings</label>
                                <input type="number" name="siblings" class="form-control" value="<?= $familyInfoRow['siblings']; ?>">
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" name="updateProfile" class="btn btn-gold px-4 fw-semibold">Save Changes</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4">
                    <form action="../includes/processes.php" method="POST">
                        <div class="row g-3">
                            <h5 class="fw-bold text-green mb-3">🔑 Change Password</h5>
                            <input type="hidden" name="student_id" value="<?= $_SESSION['student_id']; ?>">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Current Password</label>
                                <input type="text" name="current_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="text" name="new_password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm New Password</label>
                                <input type="text" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" name="updatePassword" class="btn btn-gold px-4 fw-semibold">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>