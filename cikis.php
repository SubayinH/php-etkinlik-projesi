<?php
session_start(); // Oturumu başlat
session_unset(); // Tüm oturum değişkenlerini sil
session_destroy(); // Oturumu yok et

// Kullanıcıyı giriş sayfasına yönlendir
header("Location: giris.php");
exit();
?>