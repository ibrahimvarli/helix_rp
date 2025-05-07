<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang::get('error') ?> <?= $code ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="error-page">
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <?php if ($code == 404): ?>
                    <i class="fas fa-map-signs"></i>
                <?php elseif ($code == 403): ?>
                    <i class="fas fa-ban"></i>
                <?php elseif ($code == 500): ?>
                    <i class="fas fa-exclamation-triangle"></i>
                <?php else: ?>
                    <i class="fas fa-exclamation-circle"></i>
                <?php endif; ?>
            </div>
            
            <h1 class="error-code"><?= $lang::get('error') ?> <?= $code ?></h1>
            <p class="error-message"><?= $message ?></p>
            
            <div class="error-actions">
                <a href="<?= APP_URL ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i> <?= $lang::get('go_home') ?>
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> <?= $lang::get('go_back') ?>
                </a>
            </div>
        </div>
        
        <div class="error-image">
            <?php if ($code == 404): ?>
                <img src="<?= ASSETS_PATH ?>/images/errors/lost-adventurer.jpg" alt="<?= $lang::get('error_404') ?>">
            <?php elseif ($code == 403): ?>
                <img src="<?= ASSETS_PATH ?>/images/errors/forbidden-door.jpg" alt="<?= $lang::get('error_403') ?>">
            <?php elseif ($code == 500): ?>
                <img src="<?= ASSETS_PATH ?>/images/errors/broken-crystal.jpg" alt="<?= $lang::get('error_500') ?>">
            <?php else: ?>
                <img src="<?= ASSETS_PATH ?>/images/errors/general-error.jpg" alt="<?= $lang::get('error') ?>">
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="error-footer">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. <?= $lang::get('copyright') ?></p>
    </footer>
</body>
</html> 