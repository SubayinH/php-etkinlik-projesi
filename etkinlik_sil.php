<?php 
require_once 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

$etkinlik_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Etkinliği silmeden önce bu etkinliğin gerçekten bu kullanıcıya ait olduğunu kontrol et
$stmt = $conn->prepare("DELETE FROM etkinlikler WHERE id = ? AND olusturan_kullanici_id = ?");
$stmt->bind_param("ii", $etkinlik_id, $user_id);

if ($stmt->execute()) {
    // Başarılı olursa ana sayfaya dön
    header("Location: index.php");
    exit();
} else {
    // Başarısız olursa hata göster ve ana sayfaya dön
    echo "Bir hata oluştu. Silme işlemi yapılamadı.";
    header("Refresh: 2; URL=index.php");
    exit();
}
?>