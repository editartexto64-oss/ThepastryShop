<!DOCTYPE html>
<html lang="es">
<head>

    <!-- ===========================
        META BÁSICOS SEO
    ============================ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title dinámico -->
    <title><?= $title ?? "ThePastryShop - Pasteles artesanales en Lima"; ?></title>

    <!-- Descripción SEO dinámica -->
    <meta name="description"
          content="<?= $description ?? "Pasteles, cupcakes y postres artesanales hechos a pedido. Entrega a domicilio en Lima."; ?>">

    <!-- Palabras clave -->
    <meta name="keywords"
          content="pasteles, tortas, cupcakes, repostería artesanal, delivery, Lima, ThePastryShop">

    <!-- Canonical -->
    <link rel="canonical" href="<?= BASE_URL . ($_GET['url'] ?? ''); ?>">

    <!-- ===========================
        OPEN GRAPH (REDES SOCIALES)
    ============================ -->
    <meta property="og:title" content="<?= $title ?? 'ThePastryShop'; ?>">
    <meta property="og:description" content="<?= $description ?? 'Pasteles artesanales en Lima'; ?>">
    <meta property="og:image" content="<?= BASE_URL ?>public/assets/img/logo.png">
    <meta property="og:url" content="<?= BASE_URL . ($_GET['url'] ?? ''); ?>">
    <meta property="og:type" content="website">

    <!-- ===========================
        SCHEMA.ORG (DATOS ESTRUCTURADOS)
    ============================ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Bakery",
      "name": "ThePastryShop",
      "image": "<?= BASE_URL ?>public/assets/img/logo.png",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Valle Hermoso, Santiago de Surco 15038",
        "addressLocality": "Surco",
        "addressRegion": "Lima",
        "addressCountry": "Peru"
      },
      "url": "<?= BASE_URL ?>",
      "telephone": "+51 999 999 999"
    }
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/style.css">

</head>
<body>
