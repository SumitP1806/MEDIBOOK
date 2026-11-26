<?php
require_once __DIR__ . '/config/app.php';
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
$countDoctors = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='doctor' AND is_active=1")->fetchColumn();
?>
<section class="hero">
  <div class="hero-copy">
    <span class="eyebrow">CARE THAT FITS YOUR SCHEDULE</span>
    <h1>Book your doctor appointment <span>with ease.</span></h1>
    <p>Find trusted healthcare professionals, choose a convenient time and manage appointments in one simple place.</p>
    <div class="hero-actions">
      <a class="btn btn-primary" href="doctors.php">Find a Doctor</a>
      <a class="btn btn-light" href="register.php">Create Patient Account</a>
    </div>
    <div class="hero-stats">
      <div><strong><?= $countDoctors ?></strong><small>Listed doctors</small></div>
      <div><strong>24/7</strong><small>Online booking</small></div>
      <div><strong>3</strong><small>User roles</small></div>
    </div>
  </div>
  <div class="hero-art"><div class="medical-cross">+</div><div class="hero-card"><span class="avatar">✚</span><div><b>Your health matters</b><small>Appointments made simple</small></div><span class="check">✓</span></div><div class="hero-circle"></div></div>
</section>
<section class="section">
  <div class="section-heading"><div><span class="eyebrow">HOW IT WORKS</span><h2>Healthcare in three simple steps</h2></div></div>
  <div class="grid three">
    <article class="feature-card"><div class="feature-icon">⌕</div><h3>Find a doctor</h3><p>Browse doctors and filter by specialization to find a suitable professional.</p></article>
    <article class="feature-card"><div class="feature-icon">▣</div><h3>Choose a time</h3><p>Select a date and time that works for you and submit your appointment request.</p></article>
    <article class="feature-card"><div class="feature-icon">♡</div><h3>Manage your visit</h3><p>Track appointment status and view your booking history from your dashboard.</p></article>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
