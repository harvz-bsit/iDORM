<?php
include 'includes/landing/header.php';
if (isset($_SESSION['student_id'])) {
    header("Location: student/dashboard.php");
} else if (isset($_SESSION['admin'])) {
    header("Location: admin/dashboard.php");
}
?>

<!-- Hero Section -->
<section class="hero position-relative d-flex align-items-center justify-content-center text-center text-white">
    <div class="overlay"></div>
    <div class="content position-relative z-2">
        <img src="assets/img/logo.png" alt="IDORM Logo" class="mb-3" height="100">
        <h1 class="fw-bold display-4 text-gold">Welcome to IDORM</h1>
        <p class="lead text-white">Your home inside Ilocos Sur Polytechnic State College – Main Campus</p>
        <a href="#rooms" class="btn btn-gold mt-3 px-4 py-2 fw-semibold">Explore Rooms</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="container py-5 text-center">
    <h2 class="text-maroon fw-bold mb-4">About the Dormitory</h2>
    <p class="lead mx-auto text-dark" style="max-width: 800px;">
        The ISPSC Dormitory provides <strong>safe, affordable, and comfortable living spaces</strong> for students.
        Managed through the <strong>Integrated Dormitory Management System (IDORM)</strong>, your stay is made
        convenient with technology, security, and care.
    </p>
</section>

<div class="divider my-4 mx-auto"></div>

<!-- Facilities Section -->
<section id="rooms" class="container py-5">
    <h2 class="text-center text-maroon fw-bold mb-5">Facilities & Amenities</h2>
    <div class="row g-4">

        <!-- Lobby -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200" class="card-img-top" alt="Lobby">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Lobby & Reception Area</h5>
                    <p>Warm and welcoming entrance where residents and guests are greeted. Includes a waiting lounge and information desk.</p>
                </div>
            </div>
        </div>

        <!-- Study Room -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1200" class="card-img-top" alt="Study Room">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Study Room</h5>
                    <p>A quiet and comfortable area designed for focused study sessions and group discussions.</p>
                </div>
            </div>
        </div>

        <!-- Pantry -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200" class="card-img-top" alt="Pantry">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Pantry & Dining Area</h5>
                    <p>Equipped with tables, chairs, and basic kitchen facilities for residents to enjoy their meals comfortably.</p>
                </div>
            </div>
        </div>

        <!-- Laundry Room -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200" class="card-img-top" alt="Laundry Room">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Laundry Room</h5>
                    <p>Modern and accessible laundry area with washing machines and drying racks for residents’ convenience.</p>
                </div>
            </div>
        </div>

        <!-- Shared Bedroom -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200" class="card-img-top" alt="Shared Bedroom">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Shared Bedroom</h5>
                    <p>Spacious and well-maintained rooms ideal for female students, offering comfort and community living.</p>
                </div>
            </div>
        </div>

        <!-- Outdoor Area -->
        <div class="col-md-4">
            <div class="card room-card shadow-sm border-0 h-100">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200" class="card-img-top" alt="Outdoor Area">
                <div class="card-body text-center">
                    <h5 class="fw-bold text-maroon">Outdoor Area</h5>
                    <p>A relaxing open space where residents can unwind, enjoy fresh air, and build friendships.</p>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- Pricing Section -->
<section id="pricing" class="text-center text-white py-5 position-relative overflow-hidden">
    <!-- Gradient Background -->
    <div class="pricing-bg"></div>

    <div class="container position-relative">
        <h2 class="fw-bold text-gold mb-3 animate-up">Affordable Rate</h2>
        <h3 class="display-4 fw-bold mb-3 animate-up delay-1">₱750 <span class="fs-4 fw-normal">/ month</span></h3>
        <p class="lead mb-4 animate-up delay-2 text-white">Includes water, electricity, and basic amenities</p>
        <a href="apply.php" class="btn btn-gold fw-semibold px-5 py-2 rounded-pill animate-up delay-3 shadow-sm">
            Apply Now
        </a>
    </div>
</section>


<!-- ===== CONTACT SECTION ===== -->
<section id="contact" class="py-5 position-relative overflow-hidden shadow-lg">
    <div class="contact-overlay"></div>
    <div class="container position-relative">
        <h2 class="text-center text-maroon fw-bold mb-5">Contact Us</h2>

        <div class="row g-4 justify-content-center">
            <!-- Address -->
            <div class="col-md-4">
                <div class="contact-card glass p-4 text-center shadow-sm h-100 fade-in">
                    <i class="bi bi-geo-alt-fill fs-1 text-green mb-3"></i>
                    <h5 class="fw-bold text-maroon">Our Location</h5>
                    <p>ISPSC Main Campus, San Nicolas, Candon City, Ilocos Sur</p>
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-4">
                <div class="contact-card glass p-4 text-center shadow-sm h-100 fade-in delay-1">
                    <i class="bi bi-envelope-fill fs-1 text-green mb-3"></i>
                    <h5 class="fw-bold text-maroon">Email Us</h5>
                    <p><a href="mailto:idorm@ispsc.edu.ph" class="text-dark text-decoration-none">idorm@ispsc.edu.ph</a></p>
                </div>
            </div>

            <!-- Office Hours -->
            <div class="col-md-4">
                <div class="contact-card glass p-4 text-center shadow-sm h-100 fade-in delay-2">
                    <i class="bi bi-clock-fill fs-1 text-green mb-3"></i>
                    <h5 class="fw-bold text-maroon">Office Hours</h5>
                    <p>Monday – Friday<br>8:00 AM – 5:00 PM</p>
                </div>
            </div>
        </div>

        <!-- Optional Message or CTA -->

        <div class="text-center mt-5 fade-in delay-3">
            <p class="mb-3">Have questions about dorm applications?</p>
            <button type="button" class="btn btn-gold px-5 py-2 fw-semibold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#contactModal">
                Get in Touch
            </button>
        </div>
    </div>
</section>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-white text-dark border-0 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-gold" id="contactModalLabel">📩 Contact Us</h5>
                <button type="button" class="btn-close btn-close-maroon" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr class="p-0 m-0">
            <div class="modal-body">
                <form id="contactForm" action="send_mail.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label text-gold">Your Name</label>
                        <input type="text" class="form-control bg-transparent border-dark" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-gold">Your Email</label>
                        <input type="email" class="form-control bg-transparent border-dark" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label text-gold">Message</label>
                        <textarea class="form-control bg-transparent border-dark" id="message" name="message" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-light bg-maroon text-white fw-semibold px-4 rounded-pill">Send Message</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <small class="text-muted">We’ll get back to you as soon as possible.</small>
            </div>
        </div>
    </div>
</div>



<?php include 'includes/landing/footer.php'; ?>