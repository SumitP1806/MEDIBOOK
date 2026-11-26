<?php
require_once __DIR__ . '/config/app.php';
if (is_logged_in()) redirect(dashboard_path($_SESSION['role']));
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id,name,email,password,role,is_active FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && (int)$user['is_active'] === 1 && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        redirect(dashboard_path($user['role']));
    }
    $error = 'Email or password is incorrect, or the account is inactive.';
}
$pageTitle = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap"><section class="auth-card">
  <span class="eyebrow">WELCOME BACK</span><h1>Sign in to MediBook</h1><p class="muted">Access your appointments and dashboard.</p>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="form-stack">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Email address<input type="email" name="email" required autocomplete="email"></label>
    <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
    <button class="btn btn-primary full" type="submit">Login</button>
  </form>
  <p class="form-note">New patient? <a href="register.php">Create an account</a></p>
  <div class="demo-box"><b>Demo login</b><br>Admin: admin@example.com / password<br>Doctor: doctor@example.com / password<br>Patient: patient@example.com / password</div>
</section></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
