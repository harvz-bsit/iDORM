<!-- ===== FOOTER ===== -->
<footer class="footer text-white pt-5 pb-3 position-relative overflow-hidden">
  <div class="footer-overlay"></div>
  <div class="container position-relative">

    <div class="row gy-4 text-center text-md-start align-items-center">
      <!-- Logo and tagline -->
      <div class="col-md-4">
        <div class="d-flex flex-column align-items-center align-items-md-start">
          <img src="../assets/img/logo.png" alt="IDORM Logo" height="60" class="mb-3">
          <p class="mb-0 small">
            <strong>IDORM</strong> — Your home inside ISPSC Main Campus.<br>
            Safe • Comfortable • Tech-Driven.
          </p>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-md-4">
        <h6 class="fw-bold text-gold mb-3">Quick Links</h6>
        <ul class="list-unstyled small mb-0">
          <li><a href="#" class="footer-link">Dashboard</a></li>
          <li><a href="#" class="footer-link">Payments</a></li>
          <li><a href="#" class="footer-link">Room Info</a></li>
          <li><a href="#" class="footer-link">Announcements</a></li>
          <li><a href="#" class="footer-link">Profile</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-md-4">
        <h6 class="fw-bold text-gold mb-3">Get in Touch</h6>
        <p class="small mb-1"><i class="bi bi-envelope-fill text-gold me-2"></i> idorm@ispsc.edu.ph</p>
        <p class="small mb-1"><i class="bi bi-geo-alt-fill text-gold me-2"></i> San Nicolas, Candon City, Ilocos Sur</p>
        <p class="small mb-0"><i class="bi bi-clock-fill text-gold me-2"></i> Mon–Fri, 8:00 AM – 5:00 PM</p>
      </div>
    </div>

    <hr class="border-light my-4">

    <div class="text-center small">
      <p class="mb-0">© <?php echo date('Y'); ?> <strong>ISPSC Ladies Dormitory</strong> — All Rights Reserved</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("scroll", () => {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
</script>

</body>

</html>