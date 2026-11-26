<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('doctors.php');
verify_csrf();
$id=(int)($_POST['id']??0);
$stmt=$pdo->prepare("UPDATE users SET is_active=IF(is_active=1,0,1) WHERE id=? AND role='doctor'");
$stmt->execute([$id]);
flash($stmt->rowCount()?'success':'error',$stmt->rowCount()?'Doctor account updated.':'Doctor account not found.');
redirect('doctors.php');
