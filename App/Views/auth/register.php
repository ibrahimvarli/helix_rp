<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang::get('register') ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box register-box">
            <h1><?= $lang::get('welcome') ?> <?= APP_NAME ?></h1>
            <h2><?= $lang::get('register_title') ?></h2>
            
            <?php if (isset($errors['register'])): ?>
                <div class="alert alert-danger">
                    <?= $errors['register'] ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= APP_URL ?>/auth/register" method="post">
                <div class="form-group">
                    <label for="username"><?= $lang::get('username') ?></label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= $username ?? '' ?>" required>
                    <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email"><?= $lang::get('email') ?></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= $email ?? '' ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password"><?= $lang::get('password') ?></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm"><?= $lang::get('confirm_password') ?></label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                    <?php if (isset($errors['password_confirm'])): ?>
                        <div class="invalid-feedback"><?= $errors['password_confirm'] ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block"><?= $lang::get('register') ?></button>
            </form>
            
            <div class="auth-links">
                <p><?= $lang::get('account_exists') ?> <a href="<?= APP_URL ?>/auth/login"><?= $lang::get('login') ?></a></p>
            </div>
        </div>
        
        <div class="auth-info">
            <h2><?= $lang::get('begin_fantasy_life') ?></h2>
            <p><?= $lang::get('register_benefits') ?></p>
            <ul>
                <li><?= $lang::get('create_character_benefit') ?></li>
                <li><?= $lang::get('dream_home') ?></li>
                <li><?= $lang::get('explore_landscapes') ?></li>
                <li><?= $lang::get('make_friends') ?></li>
                <li><?= $lang::get('find_work') ?></li>
                <li><?= $lang::get('learn_skills') ?></li>
                <li><?= $lang::get('join_events') ?></li>
            </ul>
            <div class="auth-image">
                <img src="<?= ASSETS_PATH ?>/images/register-image.jpg" alt="<?= $lang::get('fantasy_life_awaits') ?>">
            </div>
        </div>
    </div>

    <footer class="auth-footer">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. <?= $lang::get('copyright') ?></p>
    </footer>

    <script src="<?= ASSETS_PATH ?>/js/auth.js"></script>
</body>
</html> 