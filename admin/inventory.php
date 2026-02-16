<?php
$pageTitle = "Inventory";
include 'includes/header.php';

// Fetch items
$result = mysqli_query($conn, "SELECT * FROM inventory ORDER BY id DESC");
?>

<div class="container py-5 min-vh-100">
    <div class="dashboard-header">
        <h2 class="fw-bold text-maroon">📦 Inventory Management</h2>
        <p class="text-muted">Track dormitory assets and equipment</p>
        <div class="divider"></div>
    </div>

    <!-- Add Item Button -->
    <div class="mb-3 text-end">
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addItemModal">
            + Add Item
        </button>
    </div>

    <!-- Inventory Table -->
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Remarks</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No inventory records yet.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['item_name']) ?></td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td>
                                    <?php
                                    $badge = match ($row['status']) {
                                        'Available' => 'success',
                                        'In Use' => 'primary',
                                        'Damaged' => 'warning',
                                        'Lost' => 'danger',
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['location']) ?></td>
                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-dark" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" onclick="deleteItem(<?= $row['id'] ?>)">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Add Inventory Item</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <div class="mb-2">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option>Available</option>
                            <option>In Use</option>
                            <option>Damaged</option>
                            <option>Lost</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                </form>
                <div id="inventoryAlert" class="alert d-none mt-2"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-maroon" onclick="saveItem()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title">Edit Inventory Item</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editInventoryForm">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-2">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" id="edit_category" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="edit_quantity" class="form-control" min="0" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option>Available</option>
                            <option>In Use</option>
                            <option>Damaged</option>
                            <option>Lost</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" id="edit_location" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" id="edit_remarks" class="form-control"></textarea>
                    </div>
                </form>
                <div id="editInventoryAlert" class="alert d-none mt-2"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-maroon" onclick="updateItem()">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    function saveItem() {
        const form = document.getElementById('inventoryForm');
        const formData = new FormData(form);

        fetch('../includes/processes.php?action=add_inventory', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
    }

    function deleteItem(id) {
        if (!confirm("Delete this item?")) return;

        fetch('../includes/processes.php?action=delete_inventory', {
                method: 'POST',
                body: JSON.stringify({
                    id
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
    }

    function openEditModal(item) {
        document.getElementById('edit_id').value = item.id;
        document.getElementById('edit_item_name').value = item.item_name;
        document.getElementById('edit_category').value = item.category;
        document.getElementById('edit_quantity').value = item.quantity;
        document.getElementById('edit_status').value = item.status;
        document.getElementById('edit_location').value = item.location;
        document.getElementById('edit_remarks').value = item.remarks;

        new bootstrap.Modal(document.getElementById('editItemModal')).show();
    }

    function updateItem() {
        const form = document.getElementById('editInventoryForm');
        const formData = new FormData(form);

        fetch('../includes/processes.php?action=update_inventory', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
    }
</script>

<?php include 'includes/footer.php'; ?>