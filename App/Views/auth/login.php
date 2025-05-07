<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang::get('login') ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box login-box">
            <h1><?= $lang::get('welcome') ?> <?= APP_NAME ?></h1>
            <h2><?= $lang::get('login_title') ?></h2>
            
            <?php if (isset($errors['login'])): ?>
                <div class="alert alert-danger">
                    <?= $errors['login'] ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= APP_URL ?>/auth/login" method="post">
                <div class="form-group">
                    <label for="username"><?= $lang::get('username_email') ?></label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= $username ?? '' ?>" required>
                    <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password"><?= $lang::get('password') ?></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block"><?= $lang::get('login') ?></button>
            </form>
            
            <div class="auth-links">
                <p><?= $lang::get('no_account') ?> <a href="<?= APP_URL ?>/auth/register"><?= $lang::get('register') ?></a></p>
            </div>
        </div>
        
        <div class="auth-info">
            <h2><?= $lang::get('fantasy_life_awaits') ?></h2>
            <p><?= $lang::get('fantasy_intro') ?></p>
            <ul>
                <li><?= $lang::get('create_customize') ?></li>
                <li><?= $lang::get('build_life') ?></li>
                <li><?= $lang::get('explore_cities') ?></li>
                <li><?= $lang::get('meet_creatures') ?></li>
                <li><?= $lang::get('develop_skills') ?></li>
                <li><?= $lang::get('own_property') ?></li>
                <li><?= $lang::get('form_relationships') ?></li>
            </ul>
            <div class="auth-image">
                <img src="<?= ASSETS_PATH ?>/images/login-image.jpg" alt="Fantasy World">
            </div>
        </div>
    </div>

    <footer class="auth-footer">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. <?= $lang::get('copyright') ?></p>
    </footer>

    <script src="<?= ASSETS_PATH ?>/js/auth.js"></script>
</body>
</html> 