<?php
require_once __DIR__ . '/../config/app.php';
require_role('admin');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $name=trim((string)($_POST['name']??'')); $email=strtolower(trim((string)($_POST['email']??'')));
 $phone=trim((string)($_POST['phone']??'')); $password=(string)($_POST['password']??'');
 $spec=trim((string)($_POST['specialization']??'')); $qual=trim((string)($_POST['qualification']??''));
 $exp=max(0,(int)($_POST['experience_years']??0)); $fee=max(0,(float)($_POST['consultation_fee']??0));
 if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<8||$spec==='') $error='Enter valid details. Password must be at least 8 characters.';
 else {
  try{$pdo->beginTransaction();$s=$pdo->prepare("INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?, 'doctor')");$s->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]);$id=(int)$pdo->lastInsertId();$d=$pdo->prepare("INSERT INTO doctors(user_id,specialization,qualification,experience_years,consultation_fee) VALUES(?,?,?,?,?)");$d->execute([$id,$spec,$qual,$exp,$fee]);$pdo->commit();flash('success','Doctor account created.');redirect('doctors.php');}
  catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='Could not create doctor. Email may already exist.';}
 }
}
$doctors=$pdo->query("SELECT u.id,u.name,u.email,u.is_active,d.specialization FROM users u JOIN doctors d ON d.user_id=u.id ORDER BY u.name")->fetchAll();
$pageTitle='Manage Doctors';require __DIR__.'/../includes/header.php';
?>
<section class="page-heading"><div><span class="eyebrow">ADMINISTRATION</span><h1>Manage doctors</h1><p>Create doctor accounts and manage directory access.</p></div></section>
<div class="admin-layout"><section class="panel"><div class="panel-heading"><h2>Add a doctor</h2></div><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-stack compact"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Full name<input name="name" required></label><label>Email<input type="email" name="email" required></label><label>Phone<input name="phone"></label><label>Temporary password<input type="password" name="password" minlength="8" required></label><label>Specialization<input name="specialization" required placeholder="e.g. Cardiologist"></label><label>Qualification<input name="qualification" placeholder="e.g. MBBS, MD"></label><label>Experience (years)<input type="number" name="experience_years" min="0" value="0"></label><label>Consultation fee (₹)<input type="number" name="consultation_fee" min="0" step="0.01" value="500"></label><button class="btn btn-primary">Create doctor</button></form></section>
<section class="panel"><div class="panel-heading"><h2>Doctor directory</h2></div><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Specialization</th><th>Account</th><th>Action</th></tr></thead><tbody><?php foreach($doctors as $d): ?><tr><td><?= e($d['name']) ?></td><td><?= e($d['email']) ?></td><td><?= e($d['specialization']) ?></td><td><?= (int)$d['is_active']?'Active':'Inactive' ?></td><td><form method="post" action="toggle_doctor.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>"><button class="link-button"><?= (int)$d['is_active']?'Deactivate':'Activate' ?></button></form></td></tr><?php endforeach; ?></tbody></table></div></section></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
