<?php
require_once __DIR__ . '/../config/app.php';
require_role('patient');
$doctorId = (int)($_GET['doctor_id'] ?? $_POST['doctor_id'] ?? 0);
$stmt = $pdo->prepare("SELECT u.id,u.name,d.specialization,d.consultation_fee FROM users u JOIN doctors d ON d.user_id=u.id WHERE u.id=? AND u.role='doctor' AND u.is_active=1");
$stmt->execute([$doctorId]); $doctor = $stmt->fetch();
if (!$doctor) { http_response_code(404); exit('Doctor not found.'); }
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $date = (string)($_POST['appointment_date'] ?? '');
    $time = (string)($_POST['appointment_time'] ?? '');
    $reason = trim((string)($_POST['reason'] ?? ''));
    $validDate = DateTime::createFromFormat('Y-m-d',$date);
    if (!$validDate || $date < date('Y-m-d')) $error='Choose a valid future date.';
    elseif (!preg_match('/^(09|10|11|12|13|14|15|16|17):([0-5][0-9])$/',$time)) $error='Choose an appointment time between 09:00 and 17:59.';
    else {
        try {
            $pdo->beginTransaction();
            $check = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=? AND status IN ('pending','approved')");
            $check->execute([$doctorId,$date,$time]);
            if ((int)$check->fetchColumn()>0) {
                $pdo->rollBack(); $error='This slot is already requested. Please choose another time.';
            } else {
                $ins=$pdo->prepare("INSERT INTO appointments(patient_id,doctor_id,appointment_date,appointment_time,reason,status) VALUES(?,?,?,?,?,'pending')");
                $ins->execute([current_user()['id'],$doctorId,$date,$time,$reason]);
                $pdo->commit(); flash('success','Appointment request submitted. You can track its status on your dashboard.');
                redirect('dashboard.php');
            }
        } catch (Throwable $e) { if($pdo->inTransaction()) $pdo->rollBack(); $error='Unable to book this appointment. Please try again.'; }
    }
}
$pageTitle='Book Appointment';
require __DIR__ . '/../includes/header.php';
?>
<div class="auth-wrap"><section class="auth-card wide"><span class="eyebrow">NEW BOOKING</span><h1>Book an appointment</h1><p class="muted">With <?= e($doctor['name']) ?> · <?= e($doctor['specialization']) ?> · ₹<?= number_format((float)$doctor['consultation_fee'],2) ?></p>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="doctor_id" value="<?= $doctorId ?>">
<label>Appointment date<input type="date" name="appointment_date" min="<?= date('Y-m-d',strtotime('+1 day')) ?>" required></label>
<label>Appointment time<select name="appointment_time" required><option value="">Select time</option><?php for($h=9;$h<18;$h++): foreach([0,30] as $m): $t=sprintf('%02d:%02d',$h,$m); ?><option value="<?= $t ?>"><?= date('g:i A',strtotime($t)) ?></option><?php endforeach; endfor; ?></select></label>
<label>Reason for visit<textarea name="reason" rows="4" maxlength="1000" placeholder="Briefly describe the reason for your appointment"></textarea></label>
<button class="btn btn-primary full">Submit appointment request</button></form></section></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
