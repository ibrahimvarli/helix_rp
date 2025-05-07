<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="<?= APP_URL ?>">
                    <img src="<?= ASSETS_PATH ?>/images/logo.png" alt="<?= APP_NAME ?>" class="logo-image">
                    <span class="logo-text"><?= APP_NAME ?></span>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-menu">
                    <?php if ($currentUser): ?>
                        <li><a href="<?= APP_URL ?>/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Kontrol Paneli</a></li>
                        <li><a href="<?= APP_URL ?>/game" class="nav-link"><i class="fas fa-gamepad"></i> Oyun</a></li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-building"></i> Mülkler</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= APP_URL ?>/property" class="dropdown-item">Pazaryeri</a></li>
                                <li><a href="<?= APP_URL ?>/property/myproperties" class="dropdown-item">Mülklerim</a></li>
                                <li><a href="<?= APP_URL ?>/property/furniture" class="dropdown-item">Mobilya Mağazası</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-users"></i> Sosyal</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= APP_URL ?>/social/friends" class="dropdown-item">Arkadaşlar</a></li>
                                <li><a href="<?= APP_URL ?>/social/messages" class="dropdown-item">Mesajlar</a></li>
                                <li><a href="<?= APP_URL ?>/social/relationships" class="dropdown-item">İlişkiler</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle"><i class="fas fa-briefcase"></i> Kariyer</a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= APP_URL ?>/career/jobs" class="dropdown-item">İş Bul</a></li>
                                <li><a href="<?= APP_URL ?>/career/myjob" class="dropdown-item">İşim</a></li>
                                <li><a href="<?= APP_URL ?>/career/skills" class="dropdown-item">Beceriler</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="user-menu">
                <?php if ($currentUser): ?>
                    <div class="character-status">
                        <?php 
                            // Get character if exists
                            $characterModel = new \App\Models\Character();
                            $character = $characterModel->getByUserId($currentUser['id']);
                            if ($character):
                        ?>
                            <div class="character-stats">
                                <div class="stat-item">
                                    <i class="fas fa-heart"></i>
                                    <div class="progress">
                                        <div class="progress-bar health" style="width: <?= $character['health'] ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-bolt"></i>
                                    <div class="progress">
                                        <div class="progress-bar energy" style="width: <?= $character['energy'] ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-utensils"></i>
                                    <div class="progress">
                                        <div class="progress-bar hunger" style="width: <?= $character['hunger'] ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="dropdown user-dropdown">
                        <a href="#" class="user-dropdown-toggle">
                            <img src="<?= ASSETS_PATH ?>/images/avatars/<?= $currentUser['avatar'] ?? 'default.jpg' ?>" alt="<?= $currentUser['username'] ?>" class="user-avatar">
                            <span class="user-name"><?= $currentUser['username'] ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= APP_URL ?>/profile" class="dropdown-item"><i class="fas fa-user"></i> Profil</a></li>
                            <li><a href="<?= APP_URL ?>/settings" class="dropdown-item"><i class="fas fa-cog"></i> Ayarlar</a></li>
                            <li><a href="<?= APP_URL ?>/auth/logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="<?= APP_URL ?>/auth/login" class="btn btn-sm btn-primary">Giriş Yap</a>
                        <a href="<?= APP_URL ?>/auth/register" class="btn btn-sm btn-secondary">Kayıt Ol</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-toggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </div>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert-container">
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
            <button class="alert-close">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-container">
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <button class="alert-close">&times;</button>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?> 