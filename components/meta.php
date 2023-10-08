<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?></title>
<!-- facebook og seo tags -->
<meta property="og:url" content="<?php echo isset($this_url) ? $this_url : BASE_URL ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $title ?>" />
<meta property="og:description" content="Urbaview, creamos espacios con vistas 360 grados, para no perder de vista nada." />
<meta property="og:image" content="<?php echo $seo_image ? $seo_image : "https://urbaview.net/wp-content/uploads/2023/09/imagen-al-compartir-1.jpg" ?>" />
<!-- assets -->
<link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/style.css">