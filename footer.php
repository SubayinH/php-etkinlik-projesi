</div> <!-- .container kapanışı -->

<!-- Bootstrap JS Bundle CDN'i (Popper içerir) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
// Veritabanı bağlantısını kapat
if(isset($conn)) {
    $conn->close();
}
?>