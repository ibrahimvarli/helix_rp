<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Marketplace - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php require_once VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="container">
        <div class="page-header">
            <h1>Property Marketplace</h1>
            <p>Find your dream home in our magical realm</p>
        </div>
        
        <!-- Filter Form -->
        <div class="filter-box">
            <form action="<?= APP_URL ?>/property" method="get" class="filter-form">
                <div class="filter-group">
                    <label for="type">Property Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="house" <?= ($filters['type'] == 'house') ? 'selected' : '' ?>>House</option>
                        <option value="apartment" <?= ($filters['type'] == 'apartment') ? 'selected' : '' ?>>Apartment</option>
                        <option value="cottage" <?= ($filters['type'] == 'cottage') ? 'selected' : '' ?>>Cottage</option>
                        <option value="manor" <?= ($filters['type'] == 'manor') ? 'selected' : '' ?>>Manor</option>
                        <option value="castle" <?= ($filters['type'] == 'castle') ? 'selected' : '' ?>>Castle</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="min_price">Min Price</label>
                    <input type="number" name="min_price" id="min_price" class="form-control" value="<?= $filters['min_price'] ?? '' ?>" min="0">
                </div>
                
                <div class="filter-group">
                    <label for="max_price">Max Price</label>
                    <input type="number" name="max_price" id="max_price" class="form-control" value="<?= $filters['max_price'] ?? '' ?>" min="0">
                </div>
                
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= APP_URL ?>/property" class="btn btn-secondary">Reset</a>
            </form>
        </div>
        
        <!-- Character's Money -->
        <div class="money-display">
            <i class="fas fa-coins"></i> Your Gold: <span class="character-money"><?= number_format($character['money']) ?></span>
        </div>
        
        <!-- Property Grid -->
        <div class="property-grid">
            <?php if (empty($properties)): ?>
                <div class="no-results">
                    <i class="fas fa-home fa-3x"></i>
                    <h3>No properties available</h3>
                    <p>Try adjusting your filters or check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($properties as $property): ?>
                    <div class="property-card">
                        <div class="property-image">
                            <img src="<?= ASSETS_PATH ?>/images/properties/<?= $property['type'] ?>-<?= rand(1, 3) ?>.jpg" alt="<?= $property['name'] ?>">
                        </div>
                        <div class="property-content">
                            <h3 class="property-title"><?= $property['name'] ?></h3>
                            <span class="property-type"><?= ucfirst($property['type']) ?></span>
                            <p class="property-description"><?= substr($property['description'], 0, 100) ?>...</p>
                            <div class="property-price"><?= number_format($property['price']) ?> Gold</div>
                            <div class="property-details">
                                <span><i class="fas fa-ruler-combined"></i> <?= $property['size'] ?? '150' ?> sq m</span>
                                <span><i class="fas fa-map-marker-alt"></i> <?= $property['location'] ?? 'Elvenwood' ?></span>
                            </div>
                            <div class="property-actions">
                                <a href="<?= APP_URL ?>/property/view/<?= $property['id'] ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="page-actions">
            <a href="<?= APP_URL ?>/property/myproperties" class="btn btn-secondary">
                <i class="fas fa-home"></i> My Properties
            </a>
        </div>
    </main>
    
    <?php require_once VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= ASSETS_PATH ?>/js/property.js"></script>
</body>
</html> 