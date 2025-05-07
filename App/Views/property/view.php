<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $property['name'] ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php require_once VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="container">
        <?php if (isset($_SESSION['property_error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['property_error'] ?>
                <button class="alert-close">&times;</button>
            </div>
            <?php unset($_SESSION['property_error']); ?>
        <?php endif; ?>
        
        <div class="property-detail">
            <div class="property-detail-header">
                <div class="property-detail-image">
                    <img src="<?= ASSETS_PATH ?>/images/properties/<?= $property['type'] ?>-<?= rand(1, 3) ?>.jpg" alt="<?= $property['name'] ?>">
                </div>
                
                <div class="property-detail-info">
                    <h1><?= $property['name'] ?></h1>
                    <span class="property-type"><?= ucfirst($property['type']) ?></span>
                    
                    <div class="property-price-display" data-price="<?= $property['price'] ?>">
                        <?= number_format($property['price']) ?> Gold
                    </div>
                    
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?= $property['location'] ?? 'Elvenwood' ?>
                    </div>
                    
                    <div class="property-size">
                        <i class="fas fa-ruler-combined"></i> 
                        <?= $property['size'] ?? '150' ?> sq m
                    </div>
                    
                    <?php if (!$property['character_id'] && $character['money'] >= $property['price']): ?>
                        <form action="<?= APP_URL ?>/property/buy/<?= $property['id'] ?>" method="post" class="purchase-form">
                            <div class="purchase-options">
                                <div class="form-group">
                                    <label class="purchase-type-label">
                                        <input type="radio" name="purchase_type" value="buy" checked>
                                        <span>Buy</span>
                                    </label>
                                    
                                    <label class="purchase-type-label">
                                        <input type="radio" name="purchase_type" value="rent">
                                        <span>Rent</span>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-shopping-cart"></i> Purchase Property
                            </button>
                        </form>
                    <?php elseif (!$property['character_id']): ?>
                        <div class="not-enough-money">
                            <i class="fas fa-coins"></i>
                            <p>You don't have enough money to purchase this property</p>
                            <p>Your Gold: <strong><?= number_format($character['money']) ?></strong></p>
                        </div>
                    <?php elseif ($isOwner): ?>
                        <div class="owner-actions">
                            <p class="owner-message">You are the owner of this property</p>
                            <div class="action-buttons">
                                <a href="<?= APP_URL ?>/property/manage/<?= $property['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Manage Property
                                </a>
                                <a href="<?= APP_URL ?>/property/sell/<?= $property['id'] ?>" class="btn btn-danger">
                                    <i class="fas fa-dollar-sign"></i> Sell Property
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="property-owned">
                            <i class="fas fa-lock"></i>
                            <p>This property is already owned</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="property-detail-content">
                <h2>About this property</h2>
                <div class="property-description">
                    <p><?= $property['description'] ?></p>
                </div>
                
                <div class="property-features">
                    <h3>Features</h3>
                    <div class="features-list">
                        <?php 
                            // Sample features (in a real app, these would come from the database)
                            $features = [
                                'bedrooms' => rand(1, 5),
                                'bathrooms' => rand(1, 3),
                                'garden' => (rand(0, 1) == 1),
                                'fireplace' => (rand(0, 1) == 1),
                                'balcony' => (rand(0, 1) == 1),
                                'basement' => (rand(0, 1) == 1),
                                'garage' => (rand(0, 1) == 1),
                                'pool' => (rand(0, 1) == 1),
                            ];
                            
                            foreach ($features as $feature => $value):
                                if ($feature == 'bedrooms' || $feature == 'bathrooms'):
                        ?>
                            <div class="feature-item">
                                <i class="fas fa-<?= ($feature == 'bedrooms') ? 'bed' : 'bath' ?>"></i>
                                <?= ucfirst($feature) ?>: <?= $value ?>
                            </div>
                                <?php elseif ($value): ?>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <?= ucfirst($feature) ?>
                            </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-actions">
            <a href="<?= APP_URL ?>/property" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Marketplace
            </a>
        </div>
    </main>
    
    <?php require_once VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= ASSETS_PATH ?>/js/property.js"></script>
</body>
</html> 