<?php
require_once 'db.php';

// Kullanıcı giriş yapmamışsa, onu giriş sayfasına atma.
if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['user_id'];
$bugunun_tarihi = date('Y-m-d');


$katildiklarim_idler = [];
$sql_katildiklarim = "SELECT etkinlik_id FROM katilimlar WHERE kullanici_id = ?";
$stmt_katildiklarim = mysqli_prepare($conn, $sql_katildiklarim);
mysqli_stmt_bind_param($stmt_katildiklarim, "i", $kullanici_id);
mysqli_stmt_execute($stmt_katildiklarim);
$sonuc_katildiklarim = mysqli_stmt_get_result($stmt_katildiklarim);
while ($row = mysqli_fetch_assoc($sonuc_katildiklarim)) {
    $katildiklarim_idler[] = $row['etkinlik_id'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Ana Sayfa</a>
       
        <a class="nav-link text-white" href="katildiklarim.php">Katıldığım Etkinlikler</a>
        <div class="ms-auto">
            <span class="navbar-text text-white me-3">
                Hoş geldin, <?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?>!
            </span>
            <a href="cikis.php" class="btn btn-danger">Çıkış Yap</a>
        </div>
      </div>
    </nav>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Tüm Etkinlikler</h1>
            <a href="etkinlik_olustur.php" class="btn btn-success">Yeni Etkinlik Oluştur</a>
        </div>

        <h2 class="mt-5">Gelecek Etkinlikler</h2>
        <hr>
        <table class="table table-striped table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Etkinlik Adı</th>
                    <th>Konum / Tarih</th>
                    <th>Açıklama</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_gelecek = "SELECT * FROM etkinlikler WHERE tarih >= ? ORDER BY tarih ASC";
                $stmt_gelecek = mysqli_prepare($conn, $sql_gelecek);
                mysqli_stmt_bind_param($stmt_gelecek, "s", $bugunun_tarihi);
                mysqli_stmt_execute($stmt_gelecek);
                $sonuc_gelecek = mysqli_stmt_get_result($stmt_gelecek);

                if (mysqli_num_rows($sonuc_gelecek) > 0) {
                    while ($etkinlik = mysqli_fetch_assoc($sonuc_gelecek)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($etkinlik['etkinlik_adi']) . "</td>";
                        echo "<td>" . htmlspecialchars($etkinlik['konum']) . "<br><small>" . date('d.m.Y', strtotime($etkinlik['tarih'])) . "</small></td>";
                        echo "<td>" . htmlspecialchars($etkinlik['aciklama']) . "</td>";
                        echo "<td>";
                        
                        
                        if ($etkinlik['olusturan_kullanici_id'] == $kullanici_id) {
                            echo "<a href='etkinlik_duzenle.php?id=" . $etkinlik['id'] . "' class='btn btn-warning btn-sm mb-1 d-block'>Düzenle</a>";
                            echo "<a href='etkinlik_sil.php?id=" . $etkinlik['id'] . "' class='btn btn-danger btn-sm d-block' onclick='return confirm(\"Emin misin?\")'>Sil</a>";
                        } else {
                            // Başkasının etkinliği ise Katıl/Vazgeç göster
                          
                            if (in_array($etkinlik['id'], $katildiklarim_idler)) {
                                // Eğer katılmışsa "Vazgeç" butonu göster
                                echo "<a href='katilim_yonet.php?etkinlik_id=" . $etkinlik['id'] . "&aksiyon=vazgec' class='btn btn-secondary btn-sm d-block'>Katılmaktan Vazgeç</a>";
                            } else {
                                // Katılmamışsa "Katıl" butonu göster
                                echo "<a href='katilim_yonet.php?etkinlik_id=" . $etkinlik['id'] . "&aksiyon=katil' class='btn btn-primary btn-sm d-block'>Katıl</a>";
                            }
                        }
               
                        echo "<a href='katilimcilar.php?etkinlik_id=" . $etkinlik['id'] . "' class='btn btn-info btn-sm mt-2 d-block'>Katılımcıları Gör</a>";
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Henüz planlanmış bir etkinlik bulunmuyor.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Geçmiş Etkinlikler Bölümü -->
        <h2 class="mt-5">Geçmiş Etkinlikler</h2>
        <hr>
        <table class="table table-striped table-bordered">
            <thead class="table-secondary">
                <tr><th>Etkinlik Adı</th><th>Konum</th><th>Tarih</th><th>Açıklama</th></tr>
            </thead>
            <tbody>
                <?php
                $sql_gecmis = "SELECT * FROM etkinlikler WHERE tarih < ? ORDER BY tarih DESC";
                $stmt_gecmis = mysqli_prepare($conn, $sql_gecmis);
                mysqli_stmt_bind_param($stmt_gecmis, "s", $bugunun_tarihi);
                mysqli_stmt_execute($stmt_gecmis);
                $sonuc_gecmis = mysqli_stmt_get_result($stmt_gecmis);
                if (mysqli_num_rows($sonuc_gecmis) > 0) {
                    while ($etkinlik = mysqli_fetch_assoc($sonuc_gecmis)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($etkinlik['etkinlik_adi']) . "</td>";
                        echo "<td>" . htmlspecialchars($etkinlik['konum']) . "</td>";
                        echo "<td>" . date('d.m.Y', strtotime($etkinlik['tarih'])) . "</td>";
                        echo "<td>" . htmlspecialchars($etkinlik['aciklama']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Henüz tamamlanmış bir etkinlik bulunmuyor.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>