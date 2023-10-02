CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT(11) UNSIGNED,
    image_path VARCHAR(255),

    FOREIGN KEY (property_id) REFERENCES properties(id)
);