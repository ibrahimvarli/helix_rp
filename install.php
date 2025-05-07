<?php
/**
 * HelixRP - Database Installation Script
 */

// Define application constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'helixrp');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Connect to MySQL server (without database)
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL server successfully.<br>";
} catch (PDOException $e) {
    die("Error connecting to MySQL server: " . $e->getMessage());
}

// Create database if it doesn't exist
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET " . DB_CHARSET . " COLLATE " . DB_CHARSET . "_unicode_ci");
    echo "Database '" . DB_NAME . "' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating database: " . $e->getMessage());
}

// Select database
try {
    $pdo->exec("USE `" . DB_NAME . "`");
    echo "Database '" . DB_NAME . "' selected successfully.<br>";
} catch (PDOException $e) {
    die("Error selecting database: " . $e->getMessage());
}

// Create users table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `created_at` DATETIME NOT NULL,
            `last_login` DATETIME NULL,
            `is_online` TINYINT(1) NOT NULL DEFAULT 0,
            `last_activity` DATETIME NULL,
            `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
            `avatar` VARCHAR(255) NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `username` (`username`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'users' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating users table: " . $e->getMessage());
}

// Create characters table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `characters` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` INT UNSIGNED NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `gender` ENUM('male', 'female', 'other') NOT NULL,
            `race` VARCHAR(50) NOT NULL,
            `appearance` TEXT NULL,
            `created_at` DATETIME NOT NULL,
            `last_activity` DATETIME NULL,
            `money` DECIMAL(10,2) NOT NULL DEFAULT 0,
            `health` TINYINT UNSIGNED NOT NULL DEFAULT 100,
            `energy` TINYINT UNSIGNED NOT NULL DEFAULT 100,
            `hunger` TINYINT UNSIGNED NOT NULL DEFAULT 0,
            `thirst` TINYINT UNSIGNED NOT NULL DEFAULT 0,
            `happiness` TINYINT UNSIGNED NOT NULL DEFAULT 80,
            `intelligence` TINYINT UNSIGNED NOT NULL DEFAULT 50,
            `strength` TINYINT UNSIGNED NOT NULL DEFAULT 50,
            `charisma` TINYINT UNSIGNED NOT NULL DEFAULT 50,
            `age` TINYINT UNSIGNED NOT NULL DEFAULT 18,
            `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            PRIMARY KEY (`id`),
            UNIQUE KEY `user_id` (`user_id`),
            CONSTRAINT `fk_characters_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'characters' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating characters table: " . $e->getMessage());
}

// Create property_types table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `property_types` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `description` TEXT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `size` INT UNSIGNED NOT NULL,
            `bedrooms` TINYINT UNSIGNED NOT NULL DEFAULT 1,
            `bathrooms` TINYINT UNSIGNED NOT NULL DEFAULT 1,
            `max_furniture` INT UNSIGNED NOT NULL DEFAULT 50,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'property_types' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating property_types table: " . $e->getMessage());
}

// Create properties table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `properties` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `property_type_id` INT UNSIGNED NOT NULL,
            `character_id` INT UNSIGNED NULL,
            `location` VARCHAR(100) NOT NULL,
            `purchase_date` DATETIME NULL,
            `purchase_type` ENUM('buy', 'rent') NULL,
            `rent_due_date` DATETIME NULL,
            PRIMARY KEY (`id`),
            KEY `property_type_id` (`property_type_id`),
            KEY `character_id` (`character_id`),
            CONSTRAINT `fk_properties_property_type_id` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`),
            CONSTRAINT `fk_properties_character_id` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'properties' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating properties table: " . $e->getMessage());
}

// Create furniture table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `furniture` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `description` TEXT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `width` INT UNSIGNED NOT NULL,
            `height` INT UNSIGNED NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'furniture' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating furniture table: " . $e->getMessage());
}

// Create property_furniture table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `property_furniture` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `property_id` INT UNSIGNED NOT NULL,
            `furniture_id` INT UNSIGNED NOT NULL,
            `position_x` INT NOT NULL DEFAULT 0,
            `position_y` INT NOT NULL DEFAULT 0,
            `position_z` INT NOT NULL DEFAULT 0,
            `rotation` INT NOT NULL DEFAULT 0,
            `added_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `property_id` (`property_id`),
            KEY `furniture_id` (`furniture_id`),
            CONSTRAINT `fk_property_furniture_property_id` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_property_furniture_furniture_id` FOREIGN KEY (`furniture_id`) REFERENCES `furniture` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'property_furniture' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating property_furniture table: " . $e->getMessage());
}

// Create items table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `items` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `description` TEXT NULL,
            `type` VARCHAR(50) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `effects` TEXT NULL,
            `image` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'items' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating items table: " . $e->getMessage());
}

// Create inventory table
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `inventory` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `character_id` INT UNSIGNED NOT NULL,
            `item_id` INT UNSIGNED NOT NULL,
            `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY `character_id` (`character_id`),
            KEY `item_id` (`item_id`),
            CONSTRAINT `fk_inventory_character_id` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_inventory_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . " COLLATE=" . DB_CHARSET . "_unicode_ci
    ");
    echo "Table 'inventory' created successfully.<br>";
} catch (PDOException $e) {
    die("Error creating inventory table: " . $e->getMessage());
}

// Insert sample property types
try {
    $pdo->exec("
        INSERT INTO `property_types` (`name`, `type`, `description`, `price`, `size`, `bedrooms`, `bathrooms`) VALUES
        ('Cozy Cottage', 'cottage', 'A small but comfortable cottage with a lovely garden. Perfect for a single character or a couple.', 5000, 80, 1, 1),
        ('Forest Apartment', 'apartment', 'A modern apartment in the heart of the Elvenwood forest. Features a balcony with a stunning view.', 8000, 100, 2, 1),
        ('Merchant House', 'house', 'A spacious house in the merchant district. Great for those who want to establish a business.', 12000, 150, 3, 2),
        ('Lakeside Villa', 'house', 'A beautiful villa by the Crystal Lake with a private dock. Luxurious living at its finest.', 25000, 200, 4, 3),
        ('Mystic Manor', 'manor', 'An old manor with a rich history and possibly a few friendly ghosts. Spacious and prestigious.', 35000, 300, 5, 3),
        ('Royal Castle', 'castle', 'A small castle with towers and a courtyard. The ultimate symbol of wealth and status.', 100000, 500, 10, 5)
    ");
    echo "Sample property types inserted successfully.<br>";
} catch (PDOException $e) {
    echo "Error inserting sample property types: " . $e->getMessage() . "<br>";
}

// Insert sample properties
try {
    $pdo->exec("
        INSERT INTO `properties` (`property_type_id`, `location`) VALUES
        (1, 'Greendale Village'),
        (1, 'Riverside Lane'),
        (2, 'Elvenwood Center'),
        (2, 'Treetop Square'),
        (3, 'Merchant District'),
        (3, 'Harbor Street'),
        (4, 'Crystal Lake Shore'),
        (4, 'Sunset Bay'),
        (5, 'Hillcrest Avenue'),
        (5, 'Mystic Valley'),
        (6, 'Royal Heights')
    ");
    echo "Sample properties inserted successfully.<br>";
} catch (PDOException $e) {
    echo "Error inserting sample properties: " . $e->getMessage() . "<br>";
}

// Insert sample furniture
try {
    $pdo->exec("
        INSERT INTO `furniture` (`name`, `type`, `description`, `price`, `width`, `height`, `image`) VALUES
        ('Simple Bed', 'bedroom', 'A basic but comfortable bed for a good night\'s sleep.', 500, 100, 200, 'simple-bed.jpg'),
        ('Luxury Bed', 'bedroom', 'A large and luxurious bed fit for royalty.', 2000, 150, 200, 'luxury-bed.jpg'),
        ('Wooden Dining Table', 'kitchen', 'A solid wooden dining table that seats four people.', 800, 120, 80, 'wooden-table.jpg'),
        ('Rustic Sofa', 'living_room', 'A comfortable sofa with rustic charm.', 1200, 180, 90, 'rustic-sofa.jpg'),
        ('Modern Sofa', 'living_room', 'A sleek and modern sofa with clean lines.', 1500, 200, 90, 'modern-sofa.jpg'),
        ('Bookshelf', 'living_room', 'A tall bookshelf for your growing collection of books.', 700, 100, 200, 'bookshelf.jpg'),
        ('Wardrobe', 'bedroom', 'A spacious wardrobe for your clothes and accessories.', 900, 120, 200, 'wardrobe.jpg'),
        ('Kitchen Counter', 'kitchen', 'A functional kitchen counter with storage space.', 1000, 180, 60, 'kitchen-counter.jpg'),
        ('Bathtub', 'bathroom', 'A luxurious bathtub for relaxing after a long day.', 1500, 150, 70, 'bathtub.jpg'),
        ('Writing Desk', 'office', 'A comfortable desk for writing and studying.', 600, 120, 60, 'writing-desk.jpg'),
        ('Garden Bench', 'outdoor', 'A sturdy bench for your garden or patio.', 400, 120, 40, 'garden-bench.jpg'),
        ('Fireplace', 'living_room', 'A cozy fireplace to warm your home.', 2000, 100, 120, 'fireplace.jpg'),
        ('Chandelier', 'decoration', 'An elegant chandelier to light up your home.', 1500, 80, 120, 'chandelier.jpg'),
        ('Wall Painting', 'decoration', 'A beautiful painting to decorate your walls.', 300, 60, 40, 'wall-painting.jpg')
    ");
    echo "Sample furniture inserted successfully.<br>";
} catch (PDOException $e) {
    echo "Error inserting sample furniture: " . $e->getMessage() . "<br>";
}

// Insert sample items
try {
    $pdo->exec("
        INSERT INTO `items` (`name`, `description`, `type`, `price`, `effects`, `image`) VALUES
        ('Health Potion', 'Restores 50 health points.', 'consumable', 100, '{\"health\": 50}', 'health-potion.jpg'),
        ('Energy Drink', 'Restores 50 energy points.', 'consumable', 100, '{\"energy\": 50}', 'energy-drink.jpg'),
        ('Bread', 'A loaf of fresh bread that reduces hunger.', 'food', 20, '{\"hunger\": -20}', 'bread.jpg'),
        ('Cheese', 'A wheel of cheese that reduces hunger.', 'food', 30, '{\"hunger\": -25}', 'cheese.jpg'),
        ('Apple', 'A juicy apple that reduces hunger and thirst.', 'food', 10, '{\"hunger\": -10, \"thirst\": -5}', 'apple.jpg'),
        ('Water Bottle', 'A bottle of fresh water that quenches thirst.', 'drink', 15, '{\"thirst\": -30}', 'water-bottle.jpg'),
        ('Book', 'An interesting book that increases intelligence.', 'usable', 50, '{\"intelligence\": 1}', 'book.jpg'),
        ('Weights', 'Exercise weights to increase strength.', 'usable', 80, '{\"strength\": 1}', 'weights.jpg'),
        ('Fancy Clothes', 'Stylish clothes that increase charisma.', 'clothing', 200, '{\"charisma\": 2}', 'fancy-clothes.jpg'),
        ('Basic Tool Set', 'A set of basic tools for repairs and crafting.', 'tool', 150, NULL, 'tool-set.jpg')
    ");
    echo "Sample items inserted successfully.<br>";
} catch (PDOException $e) {
    echo "Error inserting sample items: " . $e->getMessage() . "<br>";
}

echo "<br>Database installation completed successfully!";
echo "<br><br><a href='index.php'>Go to Homepage</a>"; 