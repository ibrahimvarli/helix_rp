-- HelixRP Veritabanı Şeması
-- Fantastik MMORPG Yaşam Simülasyonu için MySQL veritabanı yapısı

-- Veritabanını oluştur
CREATE DATABASE IF NOT EXISTS helixrp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE helixrp;

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    is_admin TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    verification_token VARCHAR(100) DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(100) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    language VARCHAR(10) DEFAULT 'tr'
) ENGINE=InnoDB;

-- Karakterler tablosu
CREATE TABLE IF NOT EXISTS characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    race VARCHAR(30) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    appearance TEXT,
    created_at DATETIME NOT NULL,
    last_activity DATETIME NOT NULL,
    money DECIMAL(10,2) DEFAULT 1000.00,
    health INT DEFAULT 100,
    energy INT DEFAULT 100,
    hunger INT DEFAULT 0,
    thirst INT DEFAULT 0,
    happiness INT DEFAULT 80,
    intelligence INT DEFAULT 50,
    strength INT DEFAULT 50,
    charisma INT DEFAULT 50,
    age INT DEFAULT 18,
    status VARCHAR(20) DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Eşya türleri tablosu
CREATE TABLE IF NOT EXISTS item_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- Eşya tablosu
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    type VARCHAR(30) NOT NULL,
    effects TEXT,
    value DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    item_type_id INT,
    attributes JSON,
    FOREIGN KEY (item_type_id) REFERENCES item_types (id)
) ENGINE=InnoDB;

-- Envanter tablosu
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    is_equipped TINYINT(1) DEFAULT 0,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items (id)
) ENGINE=InnoDB;

-- Mülk türleri tablosu
CREATE TABLE IF NOT EXISTS property_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    size INT NOT NULL,
    max_furniture INT DEFAULT 20,
    image VARCHAR(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB;

-- Mülkler tablosu
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT DEFAULT NULL,
    property_type_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    purchase_price DECIMAL(10,2) NOT NULL,
    monthly_cost DECIMAL(10,2) DEFAULT 0,
    purchased_at DATETIME,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE SET NULL,
    FOREIGN KEY (property_type_id) REFERENCES property_types (id)
) ENGINE=InnoDB;

-- Mobilya tablosu
CREATE TABLE IF NOT EXISTS furniture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    item_id INT NOT NULL,
    position_x INT DEFAULT 0,
    position_y INT DEFAULT 0,
    rotation INT DEFAULT 0,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (property_id) REFERENCES properties (id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items (id)
) ENGINE=InnoDB;

-- İş türleri tablosu
CREATE TABLE IF NOT EXISTS job_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    min_intelligence INT DEFAULT 0,
    min_strength INT DEFAULT 0,
    min_charisma INT DEFAULT 0,
    salary DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

-- İşler tablosu
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    job_type_id INT NOT NULL,
    started_at DATETIME NOT NULL,
    title VARCHAR(50) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    hours_per_day INT DEFAULT 8,
    experience INT DEFAULT 0,
    next_payday DATETIME NOT NULL,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (job_type_id) REFERENCES job_types (id)
) ENGINE=InnoDB;

-- Beceriler tablosu
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- Karakter becerileri tablosu
CREATE TABLE IF NOT EXISTS character_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    skill_id INT NOT NULL,
    level INT DEFAULT 1,
    experience INT DEFAULT 0,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills (id)
) ENGINE=InnoDB;

-- İlişki türleri tablosu
CREATE TABLE IF NOT EXISTS relationship_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- İlişkiler tablosu
CREATE TABLE IF NOT EXISTS relationships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    target_character_id INT NOT NULL,
    relationship_type_id INT NOT NULL,
    level INT DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (target_character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (relationship_type_id) REFERENCES relationship_types (id)
) ENGINE=InnoDB;

-- Mesajlar tablosu
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_at DATETIME NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (sender_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES characters (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Çocuklar tablosu
CREATE TABLE IF NOT EXISTS children (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    parent_character_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    race VARCHAR(30) NOT NULL,
    age INT DEFAULT 0,
    appearance TEXT,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (parent_character_id) REFERENCES characters (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Etkinlikler tablosu
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    requirements TEXT,
    rewards TEXT,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB;

-- Karakter etkinlikleri tablosu
CREATE TABLE IF NOT EXISTS character_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    event_id INT NOT NULL,
    joined_at DATETIME NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- İlk property türleri
INSERT INTO property_types (name, description, price, size, max_furniture, image) VALUES
('house', 'Standart bir ev, temel ihtiyaçlar için ideal.', 10000.00, 150, 30, 'house.jpg'),
('apartment', 'Şehir merkezinde küçük bir daire.', 7500.00, 80, 20, 'apartment.jpg'),
('cottage', 'Kırsal alanda sevimli bir kulübe.', 5000.00, 100, 25, 'cottage.jpg'),
('manor', 'Gösterişli bir malikane.', 50000.00, 400, 100, 'manor.jpg'),
('castle', 'Büyük ve ihtişamlı bir kale.', 200000.00, 1000, 200, 'castle.jpg');

-- İlk eşya türleri
INSERT INTO item_types (name, description) VALUES
('weapon', 'Silahlar ve savaş ekipmanları'),
('armor', 'Zırhlar ve koruyucu ekipmanlar'),
('food', 'Yiyecek ve içecekler'),
('potion', 'İksirler ve sihirli karışımlar'),
('furniture', 'Ev eşyaları ve mobilyalar'),
('clothing', 'Kıyafetler ve aksesuarlar'),
('book', 'Kitaplar ve yazılar'),
('crafting', 'Zanaat malzemeleri'),
('quest', 'Görev eşyaları');

-- İlk iş türleri
INSERT INTO job_types (name, description, min_intelligence, min_strength, min_charisma, salary) VALUES
('blacksmith', 'Demirci olarak çalış, silah ve zırh üreterek para kazan.', 30, 70, 30, 80.00),
('merchant', 'Tüccar olarak çalış, alım satım yaparak para kazan.', 60, 20, 60, 70.00),
('guard', 'Şehir muhafızı olarak çalış, halkı koruyarak para kazan.', 40, 60, 40, 60.00),
('alchemist', 'Simyacı olarak çalış, iksir üreterek para kazan.', 80, 20, 30, 90.00),
('farmer', 'Çiftçi olarak çalış, ürün yetiştirerek para kazan.', 30, 60, 30, 50.00),
('mage', 'Büyücü olarak çalış, sihirli hizmetler sunarak para kazan.', 80, 20, 40, 100.00),
('innkeeper', 'Hancı olarak çalış, konaklama hizmeti vererek para kazan.', 50, 40, 70, 75.00),
('tailor', 'Terzi olarak çalış, kıyafet üreterek para kazan.', 60, 30, 40, 65.00);

-- İlk beceriler
INSERT INTO skills (name, description) VALUES
('swordsmanship', 'Kılıç kullanma becerisi'),
('archery', 'Ok ve yay kullanma becerisi'),
('magic', 'Büyü kullanma becerisi'),
('smithing', 'Demircilik becerisi'),
('alchemy', 'Simya becerisi'),
('cooking', 'Yemek pişirme becerisi'),
('mining', 'Madencilik becerisi'),
('farming', 'Çiftçilik becerisi'),
('fishing', 'Balıkçılık becerisi'),
('persuasion', 'İkna becerisi'),
('stealth', 'Gizlenme becerisi'),
('lockpicking', 'Kilit açma becerisi');

-- İlişki türleri
INSERT INTO relationship_types (name, description) VALUES
('friend', 'Arkadaşlık ilişkisi'),
('best_friend', 'Yakın arkadaşlık ilişkisi'),
('dating', 'Flört ilişkisi'),
('married', 'Evlilik ilişkisi'),
('enemy', 'Düşmanlık ilişkisi'),
('family', 'Aile ilişkisi'),
('acquaintance', 'Tanışıklık ilişkisi'),
('colleague', 'İş arkadaşlığı ilişkisi');

-- Temel değişkenler için ayarlar tablosu
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT
) ENGINE=InnoDB;

INSERT INTO settings (setting_key, setting_value, description) VALUES
('STARTING_MONEY', '1000', 'Karakterin başlangıçta sahip olduğu para miktarı'),
('HUNGER_INCREASE_RATE', '5', 'Saatlik açlık artış oranı'),
('THIRST_INCREASE_RATE', '8', 'Saatlik susuzluk artış oranı'),
('ENERGY_DECREASE_RATE', '10', 'Saatlik enerji azalma oranı'),
('HAPPINESS_DECREASE_RATE', '3', 'Saatlik mutluluk azalma oranı'),
('HEALTH_DECREASE_THRESHOLD', '70', 'Sağlığın azalmaya başlayacağı açlık/susuzluk eşiği'),
('LEVEL_EXP_MULTIPLIER', '1000', 'Seviye atlamak için gereken tecrübe puanı çarpanı'),
('MAX_CHARACTER_AGE', '100', 'Karakterin maksimum yaşı'),
('AGING_RATE', '0.25', 'Gerçek bir günde karakterin yaşlanma oranı'); 