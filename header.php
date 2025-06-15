<?php
// Bu dosyayı her sayfanın en başına ekleyeceğiz.
// Veritabanı bağlantısını ve oturumları başlatır.
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Sistemi</title>
    <!-- Bootstrap CSS CDN'i -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Etkinlikler</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
    <?php if (isset($_SESSION['user_id'])): // Kullanıcı giriş yapmışsa ?>
      <li class="nav-item">
        <a class="nav-link" href="etkinlik_olustur.php">Yeni Etkinlik Oluştur</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="katildiklarim.php">Katıldığım Etkinlikler</a>
      </li>
      <li class="nav-item">
        <span class="navbar-text me-3">
            Hoş geldin, <?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?>!
        </span>
      </li>
      <li class="nav-item">
        <a class="btn btn-danger" href="cikis.php">Çıkış Yap</a>
      </li>
    <?php else: // Kullanıcı giriş yapmamışsa ?>
      <li class="nav-item">
        <a class="nav-link" href="giris.php">Giriş Yap</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kayit.php">Kayıt Ol</a>
      </li>
    <?php endif; ?>
</ul>
    </div>
  </div>
</nav>

<div class="container mt-4">