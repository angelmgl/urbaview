CREATE TABLE property_commodities (
    property_id INT(11) UNSIGNED NOT NULL,
    commodity_id INT(11) UNSIGNED NOT NULL,

    PRIMARY KEY (property_id, commodity_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (commodity_id) REFERENCES commodities(id)  ON DELETE CASCADE
);