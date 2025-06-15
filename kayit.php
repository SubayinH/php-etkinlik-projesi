<?php require_once 'header.php'; 

// Form gönderilmiş mi diye kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_adi = $_POST['kullanici_adi'];
    $email = $_POST['email'];
    // ŞİFREYİ ASLA DÜZ METİN OLARAK SAKLAMA! MUTLAKA HASH'LE!
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    // SQL Injection'a karşı korunmak için Prepared Statements kullan
    $stmt = $conn->prepare("INSERT INTO kullanicilar (kullanici_adi, email, sifre) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $kullanici_adi, $email, $sifre);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Kayıt başarıyla oluşturuldu! Giriş yapabilirsiniz.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hata: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<h2>Kayıt Ol</h2>
<form action="kayit.php" method="POST">
  <div class="mb-3">
    <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
    <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="mb-3">
    <label for="sifre" class="form-label">Şifre</label>
    <input type="password" class="form-control" id="sifre" name="sifre" required>
  </div>
  <button type="submit" class="btn btn-primary">Kayıt Ol</button>
</form>

<?php require_once 'footer.php'; ?>