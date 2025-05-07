# HelixRP - Fantasy Life Simulation MMORPG

HelixRP is a web-based fantasy life simulation role-playing game where players can create characters, buy properties, develop relationships, and live in a rich fantasy world.

## Features

- Character creation and customization
- Property ownership (buying, selling, and furnishing homes)
- Social interactions with other players
- Career progression and skill development
- Economy system with currency and trading
- Basic needs management (health, energy, hunger, etc.)
- Fantastical world with unique creatures and items

## Technologies Used

- PHP 7.4+ with MVC architecture
- MySQL database
- HTML5, CSS3, and JavaScript
- AJAX for dynamic content loading
- WebSockets for real-time player interactions

## Installation

1. Clone the repository to your local machine or web server:
   ```
   git clone https://github.com/yourusername/helixrp.git
   ```

2. Configure your web server to use the project root as the document root, or place the project in your web server's document root.

3. Create a MySQL database for the project.

4. Update the database configuration in `config/config.php` with your database details:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'helixrp');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

5. Run the installation script by visiting `install.php` in your web browser:
   ```
   http://localhost/helixrp/install.php
   ```

6. Delete or rename `install.php` after installation for security.

7. Access the game by visiting:
   ```
   http://localhost/helixrp/
   ```

## Directory Structure

```
helixrp/
├── App/
│   ├── Controllers/       # Application controllers
│   ├── Core/              # Core framework classes
│   ├── Models/            # Application models
│   └── Views/             # View templates
├── config/                # Configuration files
├── public/                # Public assets
│   ├── css/               # CSS files
│   ├── js/                # JavaScript files
│   └── images/            # Image files
├── .htaccess              # URL rewriting rules
├── index.php              # Main entry point
├── install.php            # Installation script
└── README.md              # This file
```

## Game Mechanics

### Character System
Characters have various attributes including health, energy, hunger, thirst, happiness, intelligence, strength, and charisma. These attributes affect gameplay and available options.

### Property System
Players can buy or rent properties of different types, from small cottages to large castles. Properties can be furnished and decorated to the player's liking.

### Social System
Players can interact with each other, form friendships, start romantic relationships, get married, and have children.

### Economy System
The game features a robust economy with various ways to earn and spend money, including jobs, businesses, and trading.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

- Inspiration from various life simulation games and MMORPGs
- All contributors and testers

## Contact

For questions or support, please create an issue on the GitHub repository.
