<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="<?= ASSETS_PATH ?>/images/logo.png" alt="<?= APP_NAME ?>" class="footer-logo-image">
                <span class="footer-logo-text"><?= APP_NAME ?></span>
                <p class="footer-tagline">Fantezi ve macera dünyasına adım atın</p>
            </div>
            
            <div class="footer-links">
                <div class="footer-section">
                    <h3>Oyun</h3>
                    <ul>
                        <li><a href="<?= APP_URL ?>/about">Hakkında</a></li>
                        <li><a href="<?= APP_URL ?>/features">Özellikler</a></li>
                        <li><a href="<?= APP_URL ?>/guide">Oyun Rehberi</a></li>
                        <li><a href="<?= APP_URL ?>/world">Dünya Haritası</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Topluluk</h3>
                    <ul>
                        <li><a href="<?= APP_URL ?>/community">Topluluk Merkezi</a></li>
                        <li><a href="<?= APP_URL ?>/forum">Forumlar</a></li>
                        <li><a href="<?= APP_URL ?>/events">Etkinlikler</a></li>
                        <li><a href="<?= APP_URL ?>/rankings">Sıralamalar</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Destek</h3>
                    <ul>
                        <li><a href="<?= APP_URL ?>/faq">SSS</a></li>
                        <li><a href="<?= APP_URL ?>/support">Yardım Merkezi</a></li>
                        <li><a href="<?= APP_URL ?>/contact">Bize Ulaşın</a></li>
                        <li><a href="<?= APP_URL ?>/report">Hata Bildir</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Yasal</h3>
                    <ul>
                        <li><a href="<?= APP_URL ?>/terms">Kullanım Koşulları</a></li>
                        <li><a href="<?= APP_URL ?>/privacy">Gizlilik Politikası</a></li>
                        <li><a href="<?= APP_URL ?>/cookies">Çerez Politikası</a></li>
                        <li><a href="<?= APP_URL ?>/rules">Oyun Kuralları</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
            </div>
            
            <div class="copyright">
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. Tüm hakları saklıdır.
            </div>
        </div>
    </div>
</footer>

<script src="<?= ASSETS_PATH ?>/js/main.js"></script>
<script>
    // Close alert messages
    document.addEventListener('DOMContentLoaded', function() {
        const alertCloseButtons = document.querySelectorAll('.alert-close');
        alertCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.parentNode;
                const alertContainer = alert.parentNode;
                alertContainer.removeChild(alert);
            });
        });
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-container .alert');
            alerts.forEach(alert => {
                const alertContainer = alert.parentNode;
                alertContainer.removeChild(alert);
            });
        }, 5000);
    });
</script> 