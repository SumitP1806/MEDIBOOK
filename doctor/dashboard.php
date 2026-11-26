<?php
require_once __DIR__ . '/../config/app.php';
require_role('doctor');
$uid=current_user()['id'];
$stmt=$pdo->prepare("SELECT a.*,p.name AS patient_name,p.phone AS patient_phone FROM appointments a JOIN users p ON p.id=a.patient_id WHERE a.doctor_id=? ORDER BY a.appointment_date DESC,a.appointment_time DESC");
$stmt->execute([$uid]); $appointments=$stmt->fetchAll();
$pageTitle='Doctor Dashboard';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading"><div><span class="eyebrow">DOCTOR PORTAL</span><h1>Welcome, <?= e(current_user()['name']) ?></h1><p>Review and manage patient appointment requests.</p></div></section>
<div class="stats-grid"><div class="stat-card"><small>Total requests</small><strong><?= count($appointments) ?></strong></div><div class="stat-card"><small>Pending</small><strong><?= count(array_filter($appointments,fn($a)=>$a['status']==='pending')) ?></strong></div><div class="stat-card"><small>Approved</small><strong><?= count(array_filter($appointments,fn($a)=>$a['status']==='approved')) ?></strong></div></div>
<section class="panel"><div class="panel-heading"><h2>Appointment requests</h2></div>
<?php if(!$appointments): ?><div class="empty-state">No appointment requests yet.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Patient</th><th>Contact</th><th>Date & time</th><th>Reason</th><th>Status</th><th>Update</th></tr></thead><tbody>
<?php foreach($appointments as $a): ?><tr><td><?= e($a['patient_name']) ?></td><td><?= e($a['patient_phone']) ?></td><td><?= e($a['appointment_date']) ?><br><?= e(substr($a['appointment_time'],0,5)) ?></td><td><?= e($a['reason']) ?></td><td><span class="status <?= e($a['status']) ?>"><?= e(ucfirst($a['status'])) ?></span></td><td><?php if($a['status']==='pending' || $a['status']==='approved'): ?><form method="post" action="update_appointment.php" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><select name="status"><option value="approved">Approve</option><option value="rejected">Reject</option><?php if($a['status']==='approved'): ?><option value="completed">Complete</option><?php endif; ?></select><button class="btn btn-small btn-primary">Save</button></form><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
