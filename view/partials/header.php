<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootsrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Bootsrap CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- FontAwesome İcon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootsrap İcon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/paritals_css/breadcrumb.css">
    <link rel="stylesheet" href="../css/paritals_css/navbar.css">
    <link rel="stylesheet" href="../css/paritals_css/footer.css">
    <link rel="stylesheet" href="../css/main.css">
    <?php
    if (isset($page_css)) {
        echo '<link rel="stylesheet" href="' . $page_css . '">';
    }
    ?>
</head>


