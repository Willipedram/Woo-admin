<?php
session_start();
include 'config.php';
$admin_token='09128334246';
$alert='';
if(isset($_POST['token'])){
    if($_POST['token']===$admin_token){
        $_SESSION['admin']=true;
    } else {
        $alert='توکن نامعتبر است.';
    }
}
if(!isset($_SESSION['admin'])){
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Vazirmatn',sans-serif;}</style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width:400px;">
        <div class="card-body">
            <h2 class="h5 mb-4 text-center">ورود مدیریت</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">توکن</label>
                    <input type="password" name="token" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">ورود</button>
            </form>
        </div>
    </div>
</div>
<script>
<?php if($alert):?>Swal.fire({icon:'error',text:'<?php echo $alert; ?>'});<?php endif;?>
</script>
</body>
</html>
<?php
exit;
}
// Admin area
if(isset($_POST['save_settings'])){
    $stmt=$conn->prepare("INSERT INTO settings (expert_name, expert_phone, download_link) VALUES (?,?,?)");
    $stmt->bind_param('sss', $_POST['expert_name'], $_POST['expert_phone'], $_POST['download_link']);
    $stmt->execute();
    $stmt->close();
    $alert='تنظیمات ذخیره شد';
}
if(isset($_POST['change_status'])){
    $id=(int)$_POST['company'];
    $status=(int)$_POST['status'];
    $stmt=$conn->prepare("UPDATE companies SET is_approved=? WHERE id=?");
    $stmt->bind_param('ii',$status,$id);
    $stmt->execute();
    $stmt->close();
    $alert='وضعیت شرکت به‌روز شد';
}
$settings=$conn->query("SELECT expert_name, expert_phone, download_link FROM settings ORDER BY id DESC LIMIT 1")->fetch_assoc();
$companies=$conn->query("SELECT * FROM companies ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پنل مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Vazirmatn',sans-serif;}</style>
</head>
<body class="bg-light">
<div class="container py-5">
    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">تنظیمات</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">گزارشات</button></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white" id="adminTabsContent">
        <div class="tab-pane fade show active" id="settings" role="tabpanel">
            <form method="post">
                <input type="hidden" name="save_settings" value="1">
                <div class="mb-3">
                    <label class="form-label">نام کارشناس</label>
                    <input type="text" name="expert_name" class="form-control" value="<?php echo $settings['expert_name']??''; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">شماره تلفن کارشناس</label>
                    <input type="text" name="expert_phone" class="form-control" value="<?php echo $settings['expert_phone']??''; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">لینک دانلود فایل</label>
                    <input type="text" name="download_link" class="form-control" value="<?php echo $settings['download_link']??''; ?>" required>
                </div>
                <button type="submit" class="btn btn-success">ذخیره تنظیمات</button>
            </form>
        </div>
        <div class="tab-pane fade" id="reports" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>شناسه</th><th>نام شرکت</th><th>IP</th><th>User Agent</th><th>وضعیت</th><th>اقدامات</th></tr></thead>
                    <tbody>
                    <?php while($row=$companies->fetch_assoc()):?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['company_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                            <td><?php echo $row['ip_address']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_agent']); ?></td>
                            <td><?php echo $row['is_approved']?'<span class="badge bg-success">تایید</span>':'<span class="badge bg-warning">در انتظار</span>'; ?></td>
                            <td>
                                <form method="post" class="d-inline status-form">
                                    <input type="hidden" name="change_status" value="1">
                                    <input type="hidden" name="company" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="btn btn-sm btn-success">تایید</button>
                                </form>
                                <form method="post" class="d-inline status-form">
                                    <input type="hidden" name="change_status" value="1">
                                    <input type="hidden" name="company" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" class="btn btn-sm btn-danger">رد</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
<?php if($alert):?>Swal.fire({icon:'success',text:'<?php echo $alert; ?>'});<?php endif;?>
const forms=document.querySelectorAll('.status-form');
forms.forEach(f=>{f.addEventListener('submit',function(e){
    e.preventDefault();
    const statusText=this.querySelector('input[name="status"]').value==1?'تایید':'رد';
    Swal.fire({title:'آیا مطمئنید؟',text:'عملیات '+statusText,icon:'warning',showCancelButton:true,confirmButtonText:'بله'}).then(r=>{if(r.isConfirmed){this.submit();}});
});});
</script>
</body>
</html>