<?php
$pageTitle = "Room Information";
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- LEFT COLUMN (Sticky Info) -->
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 100px;">
                <!-- Room Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="text-maroon fw-bold mb-3">🏠 Room Details</h5>
                        <p><strong>Room Number:</strong> 102</p>
                        <p><strong>Type:</strong> Ladies Dorm</p>
                        <p><strong>Capacity:</strong> 4 Occupants</p>
                        <p><strong>Bed Space:</strong> B2</p>
                        <p><strong>Status:</strong> <span class="badge bg-success">Occupied</span></p>
                        <p><strong>Rate:</strong> ₱2,000 / month</p>
                    </div>
                </div>

                <!-- Roommates -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="text-maroon fw-bold mb-3">👭 Roommates</h5>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-fill text-maroon me-2"></i> Maria Santos</span>
                                <span class="text-muted">2025-001</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-fill text-maroon me-2"></i> Angela Dela Cruz</span>
                                <span class="text-muted">2025-034</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-fill text-maroon me-2"></i> Ella Tan</span>
                                <span class="text-muted">2025-052</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (Scrollable content) -->
        <div class="col-lg-8">
            <!-- Amenities -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="text-maroon fw-bold mb-3">🛠️ Room Amenities</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li>• Air Conditioning</li>
                                <li>• Study Desk & Chair</li>
                                <li>• Shared Bathroom</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li>• Wardrobe</li>
                                <li>• Ceiling Fan</li>
                                <li>• Wi-Fi Access</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Request -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="text-maroon fw-bold mb-3">🧰 Request Maintenance</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Issue Description</label>
                            <textarea class="form-control" rows="3" placeholder="Describe the issue (e.g., leaking faucet, broken light)"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-gold px-4">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Complaints -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="text-maroon fw-bold mb-3">📢 Submit a Complaint</h5>
                    <p class="text-muted small mb-3">Your complaint will be reviewed privately by the dorm administration.</p>
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <input type="text" class="form-control" placeholder="e.g., Noise issue, roommate concern">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Details</label>
                            <textarea class="form-control" rows="3" placeholder="Describe your concern in detail..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-gold px-4">Submit Complaint</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>