<?php
declare(strict_types=1);

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $path): never {
    header('Location: ' . $path);
    exit;
}
function is_logged_in(): bool {
    return isset($_SESSION['user_id'], $_SESSION['role']);
}
function current_user(): array {
    return [
        'id' => (int)($_SESSION['user_id'] ?? 0),
        'name' => (string)($_SESSION['name'] ?? ''),
        'email' => (string)($_SESSION['email'] ?? ''),
        'role' => (string)($_SESSION['role'] ?? ''),
    ];
}
function require_login(): void {
    if (!is_logged_in()) redirect('/online_doctor_appointment_system/login.php');
}
function require_role(string $role): void {
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('Invalid request token. Please go back and try again.');
    }
}
function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function take_flash(): ?array {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}
function dashboard_path(string $role): string {
    return match ($role) {
        'admin' => '/online_doctor_appointment_system/admin/dashboard.php',
        'doctor' => '/online_doctor_appointment_system/doctor/dashboard.php',
        default => '/online_doctor_appointment_system/patient/dashboard.php',
    };
}
