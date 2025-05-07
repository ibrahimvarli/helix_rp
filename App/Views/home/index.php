<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $lang::get('app_description') ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="home-page">
    <?php require_once VIEWS_PATH . '/partials/header.php'; ?>
    
    <div class="hero-section">
        <div class="hero-background"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title animate-title"><?= $lang::get('welcome') ?> <span class="highlight"><?= APP_NAME ?></span></h1>
                <p class="hero-subtitle"><?= $lang::get('app_description') ?></p>
                <p class="hero-description">Bir kahraman olarak kendi kaderinizi yaratın, sihirli topraklarda mülkler edinin, dostluklar kurun, aşkı bulun ve efsanevi bir macerada yaşamınızı şekillendirin.</p>
                
                <?php if (!$currentUser): ?>
                    <div class="hero-buttons">
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary btn-lg">
                            <i class="fas fa-dragon"></i> Macerana Başla
                        </a>
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-secondary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> <?= $lang::get('login') ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="hero-buttons">
                        <a href="<?= APP_URL ?>/game" class="btn btn-primary btn-lg">
                            <i class="fas fa-gamepad"></i> Macerana Devam Et
                        </a>
                        <a href="<?= APP_URL ?>/dashboard" class="btn btn-secondary btn-lg">
                            <i class="fas fa-columns"></i> Kontrol Paneli
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="hero-image">
                <img src="<?= ASSETS_PATH ?>/images/hero-character.png" alt="Karakter">
            </div>
        </div>
        <div class="hero-waves">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#f9fafb" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>
    
    <main>
        <section class="features-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-subtitle">SONSUZ OLASILIKLAR</span>
                    <h2 class="section-title">Fantastik Bir Dünyada <span class="highlight">Yeni Bir Hayat</span></h2>
                    <p class="section-description">Helix RP'de kendi hikayenizi yazın. Köylü veya savaşçı, büyücü veya tüccar - kimliğiniz, kararlarınız ve kaderiniz tamamen size bağlı.</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3 class="feature-title">Benzersiz Karakterler</h3>
                        <p class="feature-description">Elflerden cücelere, büyücülerden savaşçılara kadar çeşitli ırklar ve sınıflarla kendi benzersiz kahramanınızı yaratın.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-castle"></i>
                        </div>
                        <h3 class="feature-title">Mülk Sahipliği</h3>
                        <p class="feature-description">Mütevazı kulübelerden görkemli kalelere kadar mülkler edinin, kendi zevkinize göre dekore edin ve güçlendirin.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Derin Sosyal Sistem</h3>
                        <p class="feature-description">Dostluklar kurun, romantik ilişkiler yaşayın, evlenin ve diğer oyuncularla kendi hanedanınızı oluşturun.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-wand-sparkles"></i>
                        </div>
                        <h3 class="feature-title">Beceri Geliştirme</h3>
                        <p class="feature-description">Savaş, zanaatkarlık, büyücülük, iyileştirme ve daha birçok beceriyi geliştirerek ustalaşın.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3 class="feature-title">Dinamik Ekonomi</h3>
                        <p class="feature-description">Ticaret yapın, değerli eşyalar üretin, pazarda alım satım yapın ve servetinizi büyütün.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-dragon"></i>
                        </div>
                        <h3 class="feature-title">Fantastik Dünya</h3>
                        <p class="feature-description">Ejderhalardan antik sihirli eserlere kadar mitik yaratıklarla dolu büyülü bir dünyayı keşfedin.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="world-section">
            <div class="container">
                <div class="world-content-wrapper">
                    <div class="world-image">
                        <img src="<?= ASSETS_PATH ?>/images/world-map.jpg" alt="Fantastik Dünya Haritası">
                        <div class="world-image-overlay"></div>
                        <div class="world-location" style="top: 30%; left: 20%;">
                            <div class="location-marker"></div>
                            <div class="location-name">Elfheim Ormanı</div>
                        </div>
                        <div class="world-location" style="top: 60%; left: 70%;">
                            <div class="location-marker"></div>
                            <div class="location-name">Ejderha Dağları</div>
                        </div>
                        <div class="world-location" style="top: 40%; left: 50%;">
                            <div class="location-marker"></div>
                            <div class="location-name">Krystal Şehri</div>
                        </div>
                    </div>
                    
                    <div class="world-content">
                        <span class="section-subtitle">DÜNYAYI KEŞFET</span>
                        <h2 class="section-title">Büyülü Topraklar</h2>
                        <p class="section-description">Hikayeler, maceralar ve keşiflerle dolu zengin, sürükleyici bir fantezi dünyasına adım atın. Her köşede yeni sırlar, tehlikeler ve hazineler keşfetmeyi bekliyor.</p>
                        
                        <ul class="world-features">
                            <li class="world-feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Büyülü Ormanlar</h4>
                                    <p>Kadim, konuşan ağaçlarla ve gizemli yaratıklarla dolu ormanlar</p>
                                </div>
                            </li>
                            
                            <li class="world-feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Ejderha Dağları</h4>
                                    <p>Ejderhalar, griffinler ve diğer efsanevi yaratıklara ev sahipliği yapan dağlar</p>
                                </div>
                            </li>
                            
                            <li class="world-feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Hareketli Şehirler</h4>
                                    <p>Lonca evleri, pazarlar ve benzersiz mimariye sahip canlı şehirler</p>
                                </div>
                            </li>
                            
                            <li class="world-feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-dungeon"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Antik Kalıntılar</h4>
                                    <p>Güçlü eserler ve unutulmuş bilgelikler barındıran antik yapılar</p>
                                </div>
                            </li>
                        </ul>
                        
                        <a href="<?= APP_URL ?>/world" class="btn btn-primary">Dünya Haritasını Görüntüle</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="community-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-subtitle">TOPLULUK</span>
                    <h2 class="section-title">Büyüyen Bir Dünyada <span class="highlight">Birlikte Yaşayın</span></h2>
                    <p class="section-description">Binlerce oyuncuyla birlikte dinamik bir toplumda yaşayın, etkileşim kurun ve kendi hikayenizi yazın.</p>
                </div>
                
                <div class="testimonials-slider">
                    <div class="testimonial-item">
                        <div class="testimonial-avatar">
                            <img src="<?= ASSETS_PATH ?>/images/avatars/player1.jpg" alt="Oyuncu">
                        </div>
                        <div class="testimonial-content">
                            <p>"Helix RP'de bir büyücü olarak başladığım macera, şimdi kendi büyü akademimi yönetmeme kadar ilerledi. Harika bir topluluk ve sonsuz olasılıklar!"</p>
                            <div class="testimonial-author">
                                <h4>MysticalMage22</h4>
                                <span>8 aydır oyuncu</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-item">
                        <div class="testimonial-avatar">
                            <img src="<?= ASSETS_PATH ?>/images/avatars/player2.jpg" alt="Oyuncu">
                        </div>
                        <div class="testimonial-content">
                            <p>"Başka bir oyuncuyla tanıştım, evlendik ve şimdi birlikte bir taverna işletiyoruz. Gerçek bir hayat simülasyonu, ama daha fantastik ve eğlenceli!"</p>
                            <div class="testimonial-author">
                                <h4>TavernKeeper</h4>
                                <span>1 yıldır oyuncu</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format(rand(2000, 5000)) ?></div>
                        <div class="stat-label">Aktif Oyuncu</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format(rand(500, 1000)) ?></div>
                        <div class="stat-label">Toplam Mülk</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format(rand(50, 200)) ?></div>
                        <div class="stat-label">Farklı Meslek</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format(rand(300, 800)) ?></div>
                        <div class="stat-label">Günlük Macera</div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Kendi Efsanenizi Yazın</h2>
                    <p>Binlerce oyuncunun aktif olduğu bu büyülü dünyada bugün yerinizi alın!</p>
                    
                    <?php if (!$currentUser): ?>
                        <div class="cta-buttons">
                            <a href="<?= APP_URL ?>/auth/register" class="btn btn-primary btn-lg pulse-btn">Ücretsiz Kaydol</a>
                            <span class="or-divider">veya</span>
                            <a href="<?= APP_URL ?>/auth/login" class="btn btn-secondary btn-lg"><?= $lang::get('login') ?></a>
                        </div>
                    <?php else: ?>
                        <div class="cta-buttons">
                            <a href="<?= APP_URL ?>/game" class="btn btn-primary btn-lg pulse-btn">Hemen Oynamaya Başla</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php require_once VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= ASSETS_PATH ?>/js/main.js"></script>
    <script>
        // Hero title animation
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.animate-title').classList.add('active');
            }, 300);
            
            // Simple testimonial slider functionality
            const testimonials = document.querySelectorAll('.testimonial-item');
            let currentIndex = 0;
            
            function showTestimonial(index) {
                testimonials.forEach(item => item.style.display = 'none');
                testimonials[index].style.display = 'flex';
            }
            
            // Show first testimonial initially
            showTestimonial(currentIndex);
            
            // Change testimonial every 5 seconds
            setInterval(() => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                showTestimonial(currentIndex);
            }, 5000);
        });
    </script>
</body>
</html> 