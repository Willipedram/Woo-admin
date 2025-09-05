<?php
include 'config.php';
$message = '';
$settings = $conn->query("SELECT expert_name, expert_phone FROM settings ORDER BY id DESC LIMIT 1")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = preg_replace('/\D/', '', $_POST['company_id'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    if ($company_id && $company_name) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stmt = $conn->prepare("INSERT INTO companies (company_id, company_name, ip_address, user_agent) VALUES (?,?,?,?)");
        $stmt->bind_param('isss', $company_id, $company_name, $ip, $ua);
        $stmt->execute();
        $stmt->close();
        $message = "شناسه شما ثبت شد. برای احراز هویت، مدارک حقوقی شرکت را به شماره {$settings['expert_phone']} (کارشناس: {$settings['expert_name']}) ارسال کنید. پس از تایید، امکان دانلود فایل فراهم می‌شود.";
    }
}
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ثبت شناسه</title>
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
    <div class="card shadow-sm mx-auto" style="max-width:500px;">
        <div class="card-body">
            <h2 class="h5 mb-4 text-center">ثبت شناسه شرکت</h2>
            <form method="post" id="registerForm">
                <div class="mb-3">
                    <label class="form-label">شناسه شرکت</label>
                    <input type="text" name="company_id" id="company_id" class="form-control" required maxlength="15">
                </div>
                <div class="mb-3">
                    <label class="form-label">نام شرکت</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">ثبت</button>
            </form>
        </div>
    </div>
</div>
<script>
const idInput=document.getElementById('company_id');
idInput.addEventListener('input',function(){this.value=this.value.replace(/\D/g,'').slice(0,15);});
<?php if($message):?>
Swal.fire({icon:'success',text:'<?php echo $message; ?>'}).then(()=>{window.location='index.php';});
<?php endif;?>
</script>
</body>
</html>