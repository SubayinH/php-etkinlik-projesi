<?php
// Bu dosya, bir etkinlik kartının HTML kodunu içerir.
?>
<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($row['etkinlik_adi']); ?></h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <?php echo htmlspecialchars($row['konum']); ?> - 
                <?php echo date("d/m/Y", strtotime($row['tarih'])); ?>
            </h6>
            <p class="card-text"><?php echo htmlspecialchars($row['aciklama']); ?></p>
            
            <!-- ÖZELLİK 3: Katılımcı Sayısını Göster -->
            <p class="card-text">
                <span class="badge bg-success"><?php echo $row['katilimci_sayisi']; ?> Kişi Katılıyor</span>
            </p>

            <p class="card-text mt-auto pt-3">
                <small class="text-muted">Oluşturan: <?php echo htmlspecialchars($row['kullanici_adi']); ?></small>
            </p>
            
            <?php 
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_id'] == $row['olusturan_kullanici_id']) {
            ?>
                    <div class="mt-2">
                        <a href="etkinlik_duzenle.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                        <a href="etkinlik_sil.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">Sil</a>
                    </div>
            <?php 
                } else {
                    $mevcut_kullanici_id = $_SESSION['user_id'];
                    $etkinlik_id = $row['id'];

                    $check_stmt = $conn->prepare("SELECT id FROM katilimlar WHERE etkinlik_id = ? AND kullanici_id = ?");
                    $check_stmt->bind_param("ii", $etkinlik_id, $mevcut_kullanici_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    echo '<div class="mt-2">';
                    if ($check_result->num_rows > 0) {
                        echo "<a href='katilim_yonet.php?action=ayril&etkinlik_id=" . $etkinlik_id . "' class='btn btn-outline-danger btn-sm'>Etkinlikten Ayrıl</a>";
                    } else {
                        // Geçmiş etkinliklere katılma butonu olmaması için
                        if ($row['tarih'] >= date('Y-m-d')) {
                           echo "<a href='katilim_yonet.php?action=katil&etkinlik_id=" . $etkinlik_id . "' class='btn btn-primary btn-sm'>Katıl</a>";
                        }
                    }
                    echo '</div>';
                    $check_stmt->close();
                }
            }
            ?>
            <a href="katilimcilar.php?etkinlik_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm mt-2">Katılımcıları Gör</a>
        </div>
    </div>
</div>