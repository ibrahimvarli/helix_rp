<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karakter - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="character-page">
    <?php require_once VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="container">
        <div class="page-header">
            <h1>Karakter Bilgileri</h1>
        </div>
        
        <div class="character-profile">
            <div class="character-profile-header">
                <div class="character-image">
                    <?php
                        $racePicture = $character['race'] ?? 'default';
                        $genderSuffix = $character['gender'] ?? '';
                        $characterImage = $racePicture . ($genderSuffix === 'female' ? '_f' : '');
                    ?>
                    <img src="<?= ASSETS_PATH ?>/images/characters/<?= $characterImage ?>.jpg" alt="<?= $character['name'] ?>">
                </div>
                
                <div class="character-details">
                    <h2 class="character-name"><?= $character['name'] ?></h2>
                    <div class="character-info">
                        <span class="character-race"><?= ucfirst($character['race']) ?></span>
                        <span class="character-level">Seviye <?= $character['age'] - 17 ?></span>
                    </div>
                    <div class="character-bio">
                        <?php if (!empty($character['appearance'])): ?>
                            <p><?= $character['appearance'] ?></p>
                        <?php else: ?>
                            <p>Bu karakter henüz bir biyografi yazmamış.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="character-status">
                        <div class="character-money">
                            <i class="fas fa-coins"></i> <?= number_format($character['money']) ?> Altın
                        </div>
                        <div class="character-creation-date">
                            <i class="fas fa-calendar-alt"></i> Oluşturulma: <?= date('d.m.Y', strtotime($character['created_at'])) ?>
                        </div>
                        <div class="character-last-activity">
                            <i class="fas fa-clock"></i> Son Aktivite: <?= date('d.m.Y H:i', strtotime($character['last_activity'])) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="character-stats-container">
                <h3>Karakter İstatistikleri</h3>
                
                <div class="character-stats">
                    <div class="stat-bar">
                        <div class="stat-label">
                            <i class="fas fa-heart"></i>
                            <span>Sağlık</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar health" style="width: <?= $character['health'] ?>%"></div>
                        </div>
                        <div class="stat-value"><?= $character['health'] ?>/100</div>
                    </div>
                    
                    <div class="stat-bar">
                        <div class="stat-label">
                            <i class="fas fa-bolt"></i>
                            <span>Enerji</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar energy" style="width: <?= $character['energy'] ?>%"></div>
                        </div>
                        <div class="stat-value"><?= $character['energy'] ?>/100</div>
                    </div>
                    
                    <div class="stat-bar">
                        <div class="stat-label">
                            <i class="fas fa-utensils"></i>
                            <span>Açlık</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar hunger" style="width: <?= $character['hunger'] ?>%"></div>
                        </div>
                        <div class="stat-value"><?= $character['hunger'] ?>/100</div>
                    </div>
                    
                    <div class="stat-bar">
                        <div class="stat-label">
                            <i class="fas fa-tint"></i>
                            <span>Susuzluk</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar thirst" style="width: <?= $character['thirst'] ?>%"></div>
                        </div>
                        <div class="stat-value"><?= $character['thirst'] ?>/100</div>
                    </div>
                    
                    <div class="stat-bar">
                        <div class="stat-label">
                            <i class="fas fa-smile"></i>
                            <span>Mutluluk</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar happiness" style="width: <?= $character['happiness'] ?>%"></div>
                        </div>
                        <div class="stat-value"><?= $character['happiness'] ?>/100</div>
                    </div>
                </div>
                
                <div class="character-attributes">
                    <div class="attribute">
                        <div class="attribute-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="attribute-details">
                            <div class="attribute-name">Zeka</div>
                            <div class="attribute-value"><?= $character['intelligence'] ?></div>
                        </div>
                    </div>
                    
                    <div class="attribute">
                        <div class="attribute-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <div class="attribute-details">
                            <div class="attribute-name">Güç</div>
                            <div class="attribute-value"><?= $character['strength'] ?></div>
                        </div>
                    </div>
                    
                    <div class="attribute">
                        <div class="attribute-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="attribute-details">
                            <div class="attribute-name">Karizma</div>
                            <div class="attribute-value"><?= $character['charisma'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="character-sections">
                <div class="section-tabs">
                    <button class="tab-button active" data-tab="inventory">Envanter</button>
                    <button class="tab-button" data-tab="properties">Mülkler</button>
                    <button class="tab-button" data-tab="skills">Beceriler</button>
                    <button class="tab-button" data-tab="quests">Görevler</button>
                </div>
                
                <div class="section-content">
                    <div class="tab-content active" id="inventory">
                        <h3>Envanter</h3>
                        <?php if (empty($inventory)): ?>
                            <div class="empty-section">
                                <i class="fas fa-box-open"></i>
                                <p>Envanteriniz boş. Maceranıza çıkarak eşyalar toplayabilirsiniz!</p>
                            </div>
                        <?php else: ?>
                            <div class="inventory-grid">
                                <?php foreach ($inventory as $item): ?>
                                    <div class="inventory-item">
                                        <div class="item-image">
                                            <img src="<?= ASSETS_PATH ?>/images/items/<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name"><?= $item['name'] ?></div>
                                            <div class="item-quantity">x<?= $item['quantity'] ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-content" id="properties">
                        <h3>Mülkler</h3>
                        <?php if (empty($properties)): ?>
                            <div class="empty-section">
                                <i class="fas fa-home"></i>
                                <p>Henüz bir mülkünüz yok. Pazaryerini ziyaret ederek bir ev satın alabilirsiniz!</p>
                                <a href="<?= APP_URL ?>/property" class="btn btn-primary">Pazaryerine Git</a>
                            </div>
                        <?php else: ?>
                            <div class="properties-grid">
                                <?php foreach ($properties as $property): ?>
                                    <div class="property-card">
                                        <div class="property-image">
                                            <img src="<?= ASSETS_PATH ?>/images/properties/<?= $property['type'] ?>-<?= rand(1, 3) ?>.jpg" alt="<?= $property['name'] ?>">
                                        </div>
                                        <div class="property-content">
                                            <h4 class="property-title"><?= $property['name'] ?></h4>
                                            <span class="property-type"><?= ucfirst($property['type']) ?></span>
                                            <div class="property-details">
                                                <span><i class="fas fa-ruler-combined"></i> <?= $property['size'] ?? '150' ?> m²</span>
                                                <span><i class="fas fa-map-marker-alt"></i> <?= $property['location'] ?? 'Elvenwood' ?></span>
                                            </div>
                                            <div class="property-actions">
                                                <a href="<?= APP_URL ?>/property/manage/<?= $property['id'] ?>" class="btn btn-sm btn-primary">Yönet</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-content" id="skills">
                        <h3>Beceriler</h3>
                        <div class="empty-section">
                            <i class="fas fa-book"></i>
                            <p>Beceri sistemi yakında eklenecek! Takipte kalın.</p>
                        </div>
                    </div>
                    
                    <div class="tab-content" id="quests">
                        <h3>Görevler</h3>
                        <div class="empty-section">
                            <i class="fas fa-scroll"></i>
                            <p>Görev sistemi yakında eklenecek! Takipte kalın.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php require_once VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
</body>
</html> 