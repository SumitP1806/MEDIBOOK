<?php
require_once __DIR__ . '/../config/app.php';
require_role('patient');
$uid = current_user()['id'];
$stmt = $pdo->prepare("SELECT a.*,u.name AS doctor_name,d.specialization FROM appointments a JOIN users u ON u.id=a.doctor_id JOIN doctors d ON d.user_id=u.id WHERE a.patient_id=? ORDER BY a.appointment_date DESC,a.appointment_time DESC");
$stmt->execute([$uid]); $appointments = $stmt->fetchAll();
$pageTitle = 'Patient Dashboard';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading dashboard-heading"><div><span class="eyebrow">PATIENT PORTAL</span><h1>Hello, <?= e(current_user()['name']) ?></h1><p>Manage your healthcare appointments.</p></div><a class="btn btn-primary" href="/online_doctor_appointment_system/doctors.php">+ Book appointment</a></section>
<div class="stats-grid"><div class="stat-card"><small>Total appointments</small><strong><?= count($appointments) ?></strong></div><div class="stat-card"><small>Upcoming</small><strong><?= count(array_filter($appointments,fn($a)=>$a['appointment_date']>=date('Y-m-d') && in_array($a['status'],['pending','approved']))) ?></strong></div><div class="stat-card"><small>Completed</small><strong><?= count(array_filter($appointments,fn($a)=>$a['status']==='completed')) ?></strong></div></div>
<section class="panel"><div class="panel-heading"><h2>My appointments</h2></div>
<?php if (!$appointments): ?><div class="empty-state">You have no appointments yet. <a href="/online_doctor_appointment_system/doctors.php">Find a doctor</a>.</div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Doctor</th><th>Specialization</th><th>Date & time</th><th>Reason</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php foreach($appointments as $a): ?><tr><td><?= e($a['doctor_name']) ?></td><td><?= e($a['specialization']) ?></td><td><?= e($a['appointment_date']) ?><br><?= e(substr($a['appointment_time'],0,5)) ?></td><td><?= e($a['reason']) ?></td><td><span class="status <?= e($a['status']) ?>"><?= e(ucfirst($a['status'])) ?></span></td><td><?php if(in_array($a['status'],['pending','approved']) && $a['appointment_date']>=date('Y-m-d')): ?><form method="post" action="cancel.php" data-confirm="Cancel this appointment?"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><button class="link-danger">Cancel</button></form><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
