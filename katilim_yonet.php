<?php
require_once 'db.php';

// Kullanıcı giriş yapmamışsa işlem yapma
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Gerekli bilgileri alıyoruz
$etkinlik_id = $_GET['etkinlik_id'];
$aksiyon = $_GET['aksiyon']; // 'katil' veya 'vazgec'
$kullanici_id = $_SESSION['user_id'];

// --- AKSİYONA GÖRE İŞLEM YAP ---

if ($aksiyon == 'katil') {
    // Kullanıcıyı etkinliğe ekle
    $sql = "INSERT INTO katilimlar (etkinlik_id, kullanici_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $etkinlik_id, $kullanici_id);
    mysqli_stmt_execute($stmt);

} elseif ($aksiyon == 'vazgec') {
    // Kullanıcının katılımını sil
    $sql = "DELETE FROM katilimlar WHERE etkinlik_id = ? AND kullanici_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $etkinlik_id, $kullanici_id);
    mysqli_stmt_execute($stmt);
}

// İşlem bittikten sonra kullanıcıyı ana sayfaya geri yönlendir
header("Location: index.php");
exit();
?>