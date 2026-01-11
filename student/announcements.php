<?php
include '../config/conn.php';
$pageTitle = "Announcements";
include 'includes/header.php';

// Fetch announcements from the database
// Assuming table `announcements` with: id, title, description, date_posted
$announcements_query = "SELECT * FROM announcements ORDER BY date_posted DESC";
$result = mysqli_query($conn, $announcements_query);
?>

<div class="container py-5 min-vh-100">
    <div class="dashboard-header mb-4">
        <h1 class="fw-bold text-maroon">Announcements</h1>
        <p class="lead">Stay updated with the latest news and announcements from the dormitory.</p>
        <div class="divider"></div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="text-muted small mb-2"><?= date("F d, Y", strtotime($row['date_posted'])) ?></p>
                    <?php
                    $short_desc = strlen($row['description']) > 150 ? substr($row['description'], 0, 150) . "..." : $row['description'];
                    ?>
                    <p class="card-text"><?= nl2br(htmlspecialchars($short_desc)) ?></p>

                    <?php if (strlen($row['description']) > 150): ?>
                        <button class="btn btn-sm btn-outline-maroon readMoreBtn"
                            data-title="<?= htmlspecialchars($row['title']) ?>"
                            data-description="<?= htmlspecialchars($row['description']) ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#readMoreModal">
                            Read More
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No announcements found.
        </div>
    <?php endif; ?>
</div>

<!-- ===== Read More Modal ===== -->
<div class="modal fade" id="readMoreModal" tabindex="-1" aria-labelledby="readMoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-maroon text-white">
                <h5 class="modal-title" id="readMoreModalLabel"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="readMoreContent">
                <!-- Announcement content will be injected here -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate modal with announcement data
    document.querySelectorAll('.readMoreBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const title = this.dataset.title;
            const description = this.dataset.description;
            document.getElementById('readMoreModalLabel').innerText = title;
            document.getElementById('readMoreContent').innerHTML = description.replace(/\n/g, "<br>");
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create search input dynamically above announcements
        const container = document.querySelector('.container.py-5');
        const searchDiv = document.createElement('div');
        searchDiv.className = 'mb-4';
        searchDiv.innerHTML = `
        <input type="text" id="announcementSearch" class="form-control" placeholder="Search announcements...">
    `;
        container.insertBefore(searchDiv, container.children[1]); // insert below header

        const searchInput = document.getElementById('announcementSearch');
        const cards = Array.from(container.querySelectorAll('.card'));

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let anyVisible = false;

            cards.forEach(card => {
                const title = card.querySelector('.card-title').innerText.toLowerCase();
                const description = card.querySelector('.card-text').innerText.toLowerCase();

                if (title.includes(query) || description.includes(query)) {
                    card.style.display = '';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show "No announcements found" message if nothing matches
            let noResult = container.querySelector('.no-results');
            if (!anyVisible) {
                if (!noResult) {
                    const div = document.createElement('div');
                    div.className = 'alert alert-info text-center no-results';
                    div.innerText = 'No announcements match your search.';
                    container.appendChild(div);
                }
            } else if (noResult) {
                noResult.remove();
            }
        });
    });
</script>


<?php include 'includes/footer.php'; ?>