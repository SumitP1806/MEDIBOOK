<?php
require_once __DIR__ . '/config/app.php';
$q = trim((string)($_GET['q'] ?? ''));
$sql = "SELECT u.id,u.name,u.email,u.phone,d.specialization,d.qualification,d.experience_years,d.consultation_fee,d.bio
        FROM users u JOIN doctors d ON d.user_id=u.id WHERE u.role='doctor' AND u.is_active=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (u.name LIKE ? OR d.specialization LIKE ?)";
    $params = ["%$q%","%$q%"];
}
$sql .= " ORDER BY u.name";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $doctors = $stmt->fetchAll();
$pageTitle = 'Find Doctors';
require __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">OUR SPECIALISTS</span><h1>Find the right doctor</h1><p>Search by doctor name or medical specialization.</p></section>
<form class="searchbar" method="get"><input name="q" value="<?= e($q) ?>" placeholder="e.g. cardiologist, Dr. Sharma"><button class="btn btn-primary">Search</button></form>
<div class="grid three doctor-grid">
<?php foreach ($doctors as $d): ?>
  <article class="doctor-card"><div class="doctor-avatar">✚</div><span class="tag"><?= e($d['specialization']) ?></span><h3><?= e($d['name']) ?></h3><p class="muted"><?= e($d['qualification']) ?> · <?= (int)$d['experience_years'] ?> years experience</p><p><?= e($d['bio'] ?: 'Dedicated to providing patient-focused care.') ?></p><div class="doctor-meta"><b>₹<?= number_format((float)$d['consultation_fee'],2) ?></b><small>Consultation fee</small></div>
  <?php if (is_logged_in() && current_user()['role']==='patient'): ?><a class="btn btn-primary full" href="patient/book.php?doctor_id=<?= (int)$d['id'] ?>">Book appointment</a><?php elseif (!is_logged_in()): ?><a class="btn btn-primary full" href="login.php">Login to book</a><?php else: ?><span class="muted small">Patient account required to book.</span><?php endif; ?>
  </article>
<?php endforeach; ?>
<?php if (!$doctors): ?><div class="empty-state">No doctors found. Try another search.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
