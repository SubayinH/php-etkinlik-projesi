<?php 
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

$etkinlik_id = $_GET['id'];

// Önce düzenlenecek etkinliğin bilgilerini çekiyoruz
$stmt_select = $conn->prepare("SELECT * FROM etkinlikler WHERE id = ? AND olusturan_kullanici_id = ?");
$stmt_select->bind_param("ii", $etkinlik_id, $_SESSION['user_id']);
$stmt_select->execute();
$result = $stmt_select->get_result();
if ($result->num_rows === 0) {
    // Etkinlik bulunamadı veya kullanıcıya ait değil
    echo "<div class='alert alert-danger'>Bu etkinliği düzenleme yetkiniz yok.</div>";
    exit();
}
$etkinlik = $result->fetch_assoc();
$stmt_select->close();


// Form gönderildiğinde güncelleme işlemini yapma
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $etkinlik_adi = $_POST['etkinlik_adi'];
    $aciklama = $_POST['aciklama'];
    $tarih = $_POST['tarih'];
    $konum = $_POST['konum'];

    $stmt_update = $conn->prepare("UPDATE etkinlikler SET etkinlik_adi = ?, aciklama = ?, tarih = ?, konum = ? WHERE id = ?");
    $stmt_update->bind_param("ssssi", $etkinlik_adi, $aciklama, $tarih, $konum, $etkinlik_id);

    if ($stmt_update->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Hata: " . $stmt_update->error . "</div>";
    }
    $stmt_update->close();
}
?>

<h2>Etkinliği Düzenle</h2>
<form action="etkinlik_duzenle.php?id=<?php echo $etkinlik['id']; ?>" method="POST">
  <div class="mb-3">
    <label for="etkinlik_adi" class="form-label">Etkinlik Adı</label>
    <input type="text" class="form-control" id="etkinlik_adi" name="etkinlik_adi" value="<?php echo htmlspecialchars($etkinlik['etkinlik_adi']); ?>" required>
  </div>
  <div class="mb-3">
    <label for="aciklama" class="form-label">Açıklama</label>
    <textarea class="form-control" id="aciklama" name="aciklama" rows="3" required><?php echo htmlspecialchars($etkinlik['aciklama']); ?></textarea>
  </div>
  <div class="mb-3">
    <label for="tarih" class="form-label">Tarih</label>
    <input type="date" class="form-control" id="tarih" name="tarih" value="<?php echo $etkinlik['tarih']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="konum" class="form-label">Konum</label>
    <input type="text" class="form-control" id="konum" name="konum" value="<?php echo htmlspecialchars($etkinlik['konum']); ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Güncelle</button>
</form>

<?php require_once 'footer.php'; ?>