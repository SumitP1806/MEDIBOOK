<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
$stats=[];
foreach(['patients','doctors','appointments'] as $table) $stats[$table]=(int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
$recent=$pdo->query("SELECT a.appointment_date,a.appointment_time,a.status,p.name patient_name,d.name doctor_name FROM appointments a JOIN users p ON p.id=a.patient_id JOIN users d ON d.id=a.doctor_id ORDER BY a.created_at DESC LIMIT 8")->fetchAll();
$pageTitle='Admin Dashboard';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-heading"><div><span class="eyebrow">ADMINISTRATION</span><h1>Admin dashboard</h1><p>Overview of the appointment platform.</p></div><a class="btn btn-primary" href="doctors.php">+ Manage doctors</a></section>
<div class="stats-grid"><div class="stat-card"><small>Patients</small><strong><?= $stats['patients'] ?></strong></div><div class="stat-card"><small>Doctors</small><strong><?= $stats['doctors'] ?></strong></div><div class="stat-card"><small>Appointments</small><strong><?= $stats['appointments'] ?></strong></div></div>
<section class="panel"><div class="panel-heading"><h2>Recent appointments</h2></div><div class="table-wrap"><table><thead><tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th></tr></thead><tbody>
<?php foreach($recent as $r): ?><tr><td><?= e($r['patient_name']) ?></td><td><?= e($r['doctor_name']) ?></td><td><?= e($r['appointment_date']) ?></td><td><?= e(substr($r['appointment_time'],0,5)) ?></td><td><span class="status <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span></td></tr><?php endforeach; ?>
<?php if(!$recent): ?><tr><td colspan="5">No appointments recorded.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
