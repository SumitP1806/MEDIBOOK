<?php
require_once __DIR__ . '/../config/app.php';
require_role('doctor');
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('dashboard.php');
verify_csrf();
$id=(int)($_POST['id']??0); $status=(string)($_POST['status']??'');
if(!in_array($status,['approved','rejected','completed'],true)) { flash('error','Invalid status.'); redirect('dashboard.php'); }
$stmt=$pdo->prepare("UPDATE appointments SET status=? WHERE id=? AND doctor_id=? AND status IN ('pending','approved')");
$stmt->execute([$status,$id,current_user()['id']]);
flash($stmt->rowCount()?'success':'error',$stmt->rowCount()?'Appointment status updated.':'Appointment could not be updated.');
redirect('dashboard.php');
