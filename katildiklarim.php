<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$kullanici_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Katıldığım Etkinlikler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">Ana Sayfa</a>
        <a class="nav-link text-white active" href="katildiklarim.php">Katıldığım Etkinlikler</a>
        <div class="ms-auto">
            <span class="navbar-text text-white me-3">
                Hoş geldin, <?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?>!
            </span>
            <a href="cikis.php" class="btn btn-danger">Çıkış Yap</a>
        </div>
      </div>
    </nav>
<div class="container mt-5">
    <h2>Katıldığım Etkinlikler</h2>
    <hr>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Etkinlik Adı</th>
                <th>Konum</th>
                <th>Tarih</th>
                <th>Aksiyon</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // JOIN kullanarak, katilimlar tablosu üzerinden etkinlikler tablosundaki bilgileri çekiyoruz.
        $sql = "SELECT e.* 
                FROM etkinlikler AS e
                JOIN katilimlar AS kat ON e.id = kat.etkinlik_id
                WHERE kat.kullanici_id = ? AND e.tarih >= CURDATE()
                ORDER BY e.tarih ASC";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $kullanici_id);
        mysqli_stmt_execute($stmt);
        $sonuc = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($sonuc) > 0) {
            while ($etkinlik = mysqli_fetch_assoc($sonuc)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($etkinlik['etkinlik_adi']) . "</td>";
                echo "<td>" . htmlspecialchars($etkinlik['konum']) . "</td>";
                echo "<td>" . date('d.m.Y', strtotime($etkinlik['tarih'])) . "</td>";
                echo "<td><a href='katilim_yonet.php?etkinlik_id=" . $etkinlik['id'] . "&aksiyon=vazgec' class='btn btn-secondary btn-sm'>Vazgeç</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>Henüz hiçbir etkinliğe katılmadınız.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>