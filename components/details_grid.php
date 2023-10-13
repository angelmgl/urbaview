<div id="details-container">
    <div id="details-grid">
        <div class="detail price">
            <h3 class="detail-title">Precio</h3>
            <p class="detail-content">
                <?php echo get_price($property) ?>
            </p>
        </div>
        <div class="detail rooms">
            <h3 class="detail-title">Habitaciones</h3>
            <p class="detail-content"><?php echo $property["rooms"] ?></p>
        </div>
        <div class="detail bathrooms">
            <h3 class="detail-title">Baños</h3>
            <p class="detail-content"><?php echo $property["bathrooms"] ?></p>
        </div>
        <div class="detail parking_capacity">
            <h3 class="detail-title">Estacionamiento</h3>
            <p class="detail-content"><?php echo $property["parking_capacity"] ?></p>
        </div>
        <?php
        $has_land = $property["land_m2"] > 0;
        if ($has_land) { ?>
            <div class="detail land_m2">
                <h3 class="detail-title">Terreno</h3>
                <p class="detail-content"><?php echo $property['land_m2'] ?> m2</p>
            </div>
            <div class="detail land_dimensions">
                <h3 class="detail-title">Dimensiones</h3>
                <p class="detail-content"><?php echo "{$property['land_width']}x{$property['land_length']}" ?></p>
            </div>
        <?php } ?>
        <div class="detail build_m2">
            <h3 class="detail-title">Construcción</h3>
            <p class="detail-content"><?php echo $property["build_m2"] ?> m2</p>
        </div>
        <div class="detail year">
            <h3 class="detail-title">Año</h3>
            <p class="detail-content"><?php echo $property["year"] ?></p>
        </div>
        <div class="detail building_floors">
            <h3 class="detail-title">Pisos</h3>
            <p class="detail-content"><?php echo $property["building_floors"] ?></p>
        </div>
    </div>
    
    <?php if (!empty($property["commodities"])) { ?>
    <hr />
    <div id="commodities">
        <h2 class="detail-title">Ammenities</h2>
        <ul id="commodities-list">
            <?php foreach($property["commodities"] as $commodity) { ?>
                <li>
                    <img src="../assets/img/check-circle.svg" alt="" width="16">
                    <span><?php echo $commodity["name"] ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>