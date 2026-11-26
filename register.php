<?php
require_once __DIR__ . '/config/app.php';
if (is_logged_in()) redirect(dashboard_path($_SESSION['role']));
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter a valid name and email. Password must be at least 8 characters.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,password,role) VALUES (?,?,?,?, 'patient')");
            $stmt->execute([$name,$email,$phone,password_hash($password, PASSWORD_DEFAULT)]);
            flash('success','Registration successful. Please log in.');
            redirect('login.php');
        } catch (PDOException $e) {
            $error = 'This email address is already registered.';
        }
    }
}
$pageTitle = 'Patient Registration';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap"><section class="auth-card">
  <span class="eyebrow">JOIN MEDIBOOK</span><h1>Create patient account</h1><p class="muted">Register to search doctors and request appointments.</p>
  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="form-stack">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Full name<input name="name" required maxlength="120"></label>
    <label>Email address<input type="email" name="email" required></label>
    <label>Phone number<input name="phone" maxlength="20"></label>
    <label>Password<input type="password" name="password" minlength="8" required></label>
    <button class="btn btn-primary full" type="submit">Create account</button>
  </form>
  <p class="form-note">Already registered? <a href="login.php">Login</a></p>
</section></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
