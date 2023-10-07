<?php
$is_usd = $property['price_usd'] > 0;
?>

<div class="input-wrapper select-input">
    <label for="currency">Seleccionar moneda:</label>
    <select id="currency" name="currency">
        <option value="usd" <?php echo $is_usd ? "selected" : "" ?>>Dólares</option>
        <option value="gs" <?php echo $is_usd ? "" : "selected" ?>>Guaraníes</option>
    </select>
</div>

<div class="input-wrapper text-input">
    <label id="price-label">
        Precio <?php echo $is_usd ? "Precio USD" : "Precio GS" ?>: 
    </label>
    <input 
        type="number" 
        id="price" 
        name="<?php echo $is_usd ? "price_usd" : "price_gs" ?>" 
        value="<?php echo $is_usd ? $property['price_usd'] : $property['price_gs'] ?>">
</div>