CREATE TABLE users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    username VARCHAR(50) NOT NULL UNIQUE,
    company VARCHAR(255),
    contact_email VARCHAR(255),
    whatsapp VARCHAR(50),
    instagram VARCHAR(255),
    facebook VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

