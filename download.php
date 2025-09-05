<?php
include 'config.php';
$message='';
$download='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $company_id=preg_replace('/\D/','',$_POST['company_id']??'');
    if($company_id){
        $stmt=$conn->prepare("SELECT is_approved FROM companies WHERE company_id=?");
        $stmt->bind_param('i',$company_id);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_assoc();
        $stmt->close();
        if(!$result){
            $message='شناسه نامعتبر است.';
        }elseif($result['is_approved']!=1){
            $message='شناسه شما هنوز تایید نشده است.';
        }else{
            $settings=$conn->query("SELECT download_link FROM settings ORDER BY id DESC LIMIT 1")->fetch_assoc();
            $download=$settings['download_link']??'';
        }
    }
}
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>دانلود فایل</title>
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
            <h2 class="h5 mb-4 text-center">دانلود فایل</h2>
            <?php if($download):?>
                <div class="text-center">
                    <a href="<?php echo $download; ?>" class="btn btn-primary">دانلود فایل</a>
                </div>
            <?php else:?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">شناسه شرکت</label>
                    <input type="text" name="company_id" id="company_id" class="form-control" required maxlength="15">
                </div>
                <button type="submit" class="btn btn-success w-100">ورود</button>
            </form>
            <?php endif;?>
        </div>
    </div>
</div>
<script>
const idInput=document.getElementById('company_id');
if(idInput){idInput.addEventListener('input',function(){this.value=this.value.replace(/\D/g,'').slice(0,15);});}
<?php if($message):?>
Swal.fire({icon:'error',text:'<?php echo $message; ?>'});
<?php endif;?>
</script>
</body>
</html>