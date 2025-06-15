<?php
require_once 'db.php';

// Kullanıcı giriş yapmamışsa ana sayfaya at
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// URL'den hangi etkinliğin katılımcılarını görmek istediğimizi alma
$etkinlik_id = $_GET['etkinlik_id'];

// Önce etkinliğin kendi adını alma ve  başlıkta gösterme
$sql_etkinlik_adi = "SELECT etkinlik_adi FROM etkinlikler WHERE id = ?";
$stmt_etkinlik_adi = mysqli_prepare($conn, $sql_etkinlik_adi);
mysqli_stmt_bind_param($stmt_etkinlik_adi, "i", $etkinlik_id);
mysqli_stmt_execute($stmt_etkinlik_adi);
$sonuc_etkinlik_adi = mysqli_stmt_get_result($stmt_etkinlik_adi);
$etkinlik = mysqli_fetch_assoc($sonuc_etkinlik_adi);
$etkinlik_adi = $etkinlik['etkinlik_adi'];

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Katılımcı Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <!-- Etkinliğin adını başlıkta gösteriyoruz -->
    <h3>"<?php echo htmlspecialchars($etkinlik_adi); ?>" Etkinliğinin Katılımcıları</h3>
    <hr>
    
    <ul class="list-group">
        <?php
        // Esas sorgu: İki tabloyu birleştirerek katılımcıların isimlerini çekme
        // "JOIN" komutu, iki tabloyu ortak bir sütun üzerinden (ID'ler) birleştirir.
        $sql_katilimcilar = "SELECT k.kullanici_adi 
                             FROM katilimlar AS kat
                             JOIN kullanicilar AS k ON kat.kullanici_id = k.id
                             WHERE kat.etkinlik_id = ?";
        
        $stmt_katilimcilar = mysqli_prepare($conn, $sql_katilimcilar);
        mysqli_stmt_bind_param($stmt_katilimcilar, "i", $etkinlik_id);
        mysqli_stmt_execute($stmt_katilimcilar);
        $sonuc_katilimcilar = mysqli_stmt_get_result($stmt_katilimcilar);

        if (mysqli_num_rows($sonuc_katilimcilar) > 0) {
            while ($katilimci = mysqli_fetch_assoc($sonuc_katilimcilar)) {
                echo "<li class='list-group-item'>" . htmlspecialchars($katilimci['kullanici_adi']) . "</li>";
            }
        } else {
            echo "<li class='list-group-item'>Bu etkinliğe henüz katılan kimse yok.</li>";
        }
        ?>
    </ul>
    
    <a href="index.php" class="btn btn-primary mt-3">Geri Dön</a>
</div>
</body>
</html>