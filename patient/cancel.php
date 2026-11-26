<?php
require_once __DIR__ . '/../config/app.php';
require_role('patient');
if ($_SERVER['REQUEST_METHOD']!=='POST') redirect('dashboard.php');
verify_csrf();
$id=(int)($_POST['id']??0);
$stmt=$pdo->prepare("UPDATE appointments SET status='cancelled' WHERE id=? AND patient_id=? AND status IN ('pending','approved') AND appointment_date>=CURDATE()");
$stmt->execute([$id,current_user()['id']]);
flash($stmt->rowCount()?'success':'error',$stmt->rowCount()?'Appointment cancelled.':'Appointment could not be cancelled.');
redirect('dashboard.php');
