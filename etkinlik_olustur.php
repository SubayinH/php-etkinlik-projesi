<?php
require_once 'db.php';

// Kullanıcı giriş yapmamışsa, onu giriş sayfasına atma.
if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

// Form gönderilmiş mi diye kontrol et
if (isset($_POST['olustur_butonu'])) {
    
    // Formdan gelen verileri alma
    $etkinlik_adi = $_POST['etkinlik_adi'];
    $aciklama = $_POST['aciklama'];
    $tarih = $_POST['tarih'];
    $konum = $_POST['konum'];
    $olusturan_id = $_SESSION['user_id']; // Giriş yapmış kullanıcının ID'si

    // SQL sorgusunu hazırlama
    $sql = "INSERT INTO etkinlikler (etkinlik_adi, aciklama, tarih, konum, olusturan_kullanici_id) VALUES (?, ?, ?, ?, ?)";
    
    // Sorguyu çalıştırmak için hazırlıyoruz. Bu fonksiyon, sorgu hatalıysa 'false' döner.
    $stmt = mysqli_prepare($conn, $sql);
    
 
    // Eğer $stmt 'false' değilse, yani sorgu hazırlama başarılıysa devam et.
    if ($stmt) {
       
        // "s" -> string (metin), "i" -> integer (sayı)
        mysqli_stmt_bind_param($stmt, "ssssi", $etkinlik_adi, $aciklama, $tarih, $konum, $olusturan_id);
    
        // Sorguyu çalıştır
        if (mysqli_stmt_execute($stmt)) {
            // Başarılı olursa ana sayfaya yönlendir
            header("Location: index.php");
            exit();
        } else {
            // Çalıştırma sırasında hata olursa
            $hata = "Etkinlik oluşturulurken bir hata oluştu: " . mysqli_error($conn);
        }
        // İşimiz bittikten sonra statement'ı kapatalım
        mysqli_stmt_close($stmt);
    } else {
        // Eğer mysqli_prepare başarısız olduysa, SQL sorgusunda bir yazım hatası vardır.
        $hata = "SQL sorgusunda bir hata var: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Yeni Etkinlik Oluştur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h2>Yeni Etkinlik Oluştur</h2>
    
    <?php 
    // Eğer bir hata mesajı varsa, onu burada gösterelim.
    if (isset($hata)) { 
        echo "<div class='alert alert-danger'>$hata</div>"; 
    } 
    ?>
    
    <form action="etkinlik_olustur.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Etkinlik Adı</label>
            <input type="text" name="etkinlik_adi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tarih</label>
            <input type="date" name="tarih" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konum</label>
            <input type="text" name="konum" class="form-control" required>
        </div>
        <button type="submit" name="olustur_butonu" class="btn btn-primary">Oluştur</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
</body>
</html>