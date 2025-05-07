<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang::get('create_character') ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="character-page">
    <?php require_once VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="container">
        <div class="page-header">
            <h1><?= $lang::get('create_character') ?></h1>
            <p>Fantastik dünyada macerana başlamak için karakterini oluştur</p>
        </div>
        
        <div class="character-container">
            <div class="character-form-container">
                <?php if (isset($errors['create'])): ?>
                    <div class="alert alert-danger">
                        <?= $errors['create'] ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= APP_URL ?>/character/create" method="post" class="character-form">
                    <div class="form-group">
                        <label for="name"><?= $lang::get('character_name') ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= $name ?? '' ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label><?= $lang::get('character_gender') ?></label>
                        <div class="gender-options">
                            <label class="gender-option">
                                <input type="radio" name="gender" value="male" <?= (isset($gender) && $gender == 'male') ? 'checked' : '' ?> required>
                                <div class="gender-icon"><i class="fas fa-mars"></i></div>
                                <span><?= $lang::get('male') ?></span>
                            </label>
                            <label class="gender-option">
                                <input type="radio" name="gender" value="female" <?= (isset($gender) && $gender == 'female') ? 'checked' : '' ?> required>
                                <div class="gender-icon"><i class="fas fa-venus"></i></div>
                                <span><?= $lang::get('female') ?></span>
                            </label>
                            <label class="gender-option">
                                <input type="radio" name="gender" value="other" <?= (isset($gender) && $gender == 'other') ? 'checked' : '' ?> required>
                                <div class="gender-icon"><i class="fas fa-transgender"></i></div>
                                <span><?= $lang::get('other') ?></span>
                            </label>
                        </div>
                        <?php if (isset($errors['gender'])): ?>
                            <div class="invalid-feedback"><?= $errors['gender'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="race"><?= $lang::get('character_race') ?></label>
                        <select id="race" name="race" class="form-control" required>
                            <option value=""><?= $lang::get('character_race') ?> seçin</option>
                            <option value="human" <?= (isset($race) && $race == 'human') ? 'selected' : '' ?>>İnsan</option>
                            <option value="elf" <?= (isset($race) && $race == 'elf') ? 'selected' : '' ?>>Elf</option>
                            <option value="dwarf" <?= (isset($race) && $race == 'dwarf') ? 'selected' : '' ?>>Cüce</option>
                            <option value="orc" <?= (isset($race) && $race == 'orc') ? 'selected' : '' ?>>Ork</option>
                            <option value="halfling" <?= (isset($race) && $race == 'halfling') ? 'selected' : '' ?>>Halfling</option>
                            <option value="gnome" <?= (isset($race) && $race == 'gnome') ? 'selected' : '' ?>>Cüce (Gnome)</option>
                            <option value="dragonborn" <?= (isset($race) && $race == 'dragonborn') ? 'selected' : '' ?>>Ejder Doğumlu</option>
                            <option value="tiefling" <?= (isset($race) && $race == 'tiefling') ? 'selected' : '' ?>>Tiefling</option>
                        </select>
                        <?php if (isset($errors['race'])): ?>
                            <div class="invalid-feedback"><?= $errors['race'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="appearance"><?= $lang::get('character_appearance') ?></label>
                        <textarea id="appearance" name="appearance" class="form-control" rows="4"><?= $appearance ?? '' ?></textarea>
                    </div>
                    
                    <div class="character-starting-stats">
                        <h3>Başlangıç Özellikleri</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-heart"></i></div>
                                <div class="stat-name">Sağlık</div>
                                <div class="stat-value">100</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-bolt"></i></div>
                                <div class="stat-name">Enerji</div>
                                <div class="stat-value">100</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-coins"></i></div>
                                <div class="stat-name">Altın</div>
                                <div class="stat-value"><?= STARTING_MONEY ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-brain"></i></div>
                                <div class="stat-name">Zeka</div>
                                <div class="stat-value">50</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-dumbbell"></i></div>
                                <div class="stat-name">Güç</div>
                                <div class="stat-value">50</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon"><i class="fas fa-smile"></i></div>
                                <div class="stat-name">Karizma</div>
                                <div class="stat-value">50</div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><?= $lang::get('begin_adventure') ?></button>
                </form>
            </div>
            
            <div class="character-preview">
                <h2>Karakter Önizleme</h2>
                <div class="character-avatar">
                    <div class="character-image">
                        <img src="<?= ASSETS_PATH ?>/images/characters/default.jpg" alt="Karakter" id="character-preview-image">
                    </div>
                </div>
                
                <div class="race-info" id="race-info">
                    <h3>Irk Bilgisi</h3>
                    <p>Bir ırk seçin...</p>
                </div>
            </div>
        </div>
    </main>
    
    <?php require_once VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const raceSelect = document.getElementById('race');
            const raceInfo = document.getElementById('race-info');
            const characterImage = document.getElementById('character-preview-image');
            
            // Race information
            const races = {
                'human': {
                    name: 'İnsan',
                    description: 'İnsanlar uyum sağlama ve çok yönlülük konusunda ustadırlar. Farklı yeteneklerde başarı gösterebilirler ve her türlü mesleğe uygundurlar.',
                    bonuses: '+5 Zeka, +5 Karizma'
                },
                'elf': {
                    name: 'Elf',
                    description: 'Elfler uzun ömürlü, zarif ve büyüye yatkın bir ırktır. Doğayla uyum içinde yaşar ve keskin duyulara sahiptirler.',
                    bonuses: '+10 Zeka, -5 Güç'
                },
                'dwarf': {
                    name: 'Cüce',
                    description: 'Cüceler dayanıklı, inatçı ve zanaatkarlıkta ustadırlar. Maden işleme ve taş işçiliğinde benzersiz yeteneklere sahiptirler.',
                    bonuses: '+10 Güç, -5 Karizma'
                },
                'orc': {
                    name: 'Ork',
                    description: 'Orklar güçlü, savaşçı bir ırktır. Fiziksel dayanıklılık ve güç konusunda diğer ırkları geride bırakırlar.',
                    bonuses: '+15 Güç, -10 Zeka'
                },
                'halfling': {
                    name: 'Halfling',
                    description: 'Halflingler küçük boyutlu, çevik ve şanslı bir ırktır. Sosyal becerilerinde ustadırlar ve tehlikelerden kaçınma konusunda yeteneklidirler.',
                    bonuses: '+10 Karizma, -5 Güç'
                },
                'gnome': {
                    name: 'Cüce (Gnome)',
                    description: 'Gnomlar yaratıcı, meraklı ve mekanik icatlarda ustadırlar. Küçük boyutlarına rağmen zeka ve büyü konusunda yeteneklidirler.',
                    bonuses: '+15 Zeka, -5 Güç'
                },
                'dragonborn': {
                    name: 'Ejder Doğumlu',
                    description: 'Ejder doğumlular, ejderha soyundan gelen güçlü savaşçılardır. Nefes silahları ve fiziksel güçleriyle tanınırlar.',
                    bonuses: '+10 Güç, +5 Karizma'
                },
                'tiefling': {
                    name: 'Tiefling',
                    description: 'Tieflingler, şeytani bir soydan gelen gizemli bir ırktır. Büyü yetenekleri ve karizmatik görünüşleriyle bilinirler.',
                    bonuses: '+5 Zeka, +10 Karizma'
                }
            };
            
            // Update race info when race changes
            raceSelect.addEventListener('change', function() {
                const selectedRace = this.value;
                
                if (selectedRace && races[selectedRace]) {
                    const race = races[selectedRace];
                    raceInfo.innerHTML = `
                        <h3>${race.name}</h3>
                        <p>${race.description}</p>
                        <p><strong>Bonuslar:</strong> ${race.bonuses}</p>
                    `;
                    
                    // Update character image
                    characterImage.src = `<?= ASSETS_PATH ?>/images/characters/${selectedRace}.jpg`;
                } else {
                    raceInfo.innerHTML = `
                        <h3>Irk Bilgisi</h3>
                        <p>Bir ırk seçin...</p>
                    `;
                    
                    // Reset to default image
                    characterImage.src = `<?= ASSETS_PATH ?>/images/characters/default.jpg`;
                }
            });
        });
    </script>
</body>
</html> 