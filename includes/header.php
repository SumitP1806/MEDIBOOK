<?php
$pageTitle = $pageTitle ?? 'Online Doctor Appointment System';
$user = current_user();
$flashMessage = take_flash();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | ODA System</title>
<link rel="stylesheet" href="/online_doctor_appointment_system/assets/css/style.css?v=4"><header class="topbar">
  <a class="brand" href="/MEDIBOOK/online_doctor_appointment_system/index.php"><span class="brand-mark">+</span> MediBook</a>
  <nav>
    <a href="/MEDIBOOK/online_doctor_appointment_system/index.php">Home</a>
    <a href="/MEDIBOOK/online_doctor_appointment_system/doctors.php">Find Doctors</a>
    <?php if (is_logged_in()): ?>
      <a href="<?= e(dashboard_path($user['role'])) ?>">Dashboard</a>
      <span class="nav-user"><?= e($user['name']) ?></span>
      <a class="nav-button" href="/MEDIBOOK/online_doctor_appointment_system/logout.php">Logout</a>
    <?php else: ?>
      <a href="/MEDIBOOK/online_doctor_appointment_system/login.php">Login</a>
      <a class="nav-button" href="/MEDIBOOK/online_doctor_appointment_system/register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
<?php if ($flashMessage): ?>
  <div class="alert <?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div>
<?php endif; ?>
