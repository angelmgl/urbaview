CREATE TABLE videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_id INT(11) UNSIGNED,
    youtube_url VARCHAR(255),

    FOREIGN KEY (property_id) REFERENCES properties(id)
);