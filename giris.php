<?php require_once 'header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    $stmt = $conn->prepare("SELECT id, kullanici_adi, sifre FROM kullanicilar WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Girilen şifre ile veritabanındaki hash'lenmiş şifreyi doğrulama
        if (password_verify($sifre, $user['sifre'])) {
            // Şifre doğru, oturum bilgilerini ayarlama
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
            // Ana sayfaya yönlendir
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Hatalı email veya şifre!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Hatalı email veya şifre!</div>";
    }
    $stmt->close();
}
?>

<h2>Giriş Yap</h2>
<form action="giris.php" method="POST">
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="mb-3">
    <label for="sifre" class="form-label">Şifre</label>
    <input type="password" class="form-control" id="sifre" name="sifre" required>
  </div>
  <button type="submit" class="btn btn-primary">Giriş Yap</button>
</form>

<?php require_once 'footer.php'; ?>