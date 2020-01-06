<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Error</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/navbar-static/">

    <!-- Bootstrap core CSS -->
    
    <!-- Propre? -->   
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/myStyle.css" rel="stylesheet">
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/myStyle.css" rel="stylesheet">
    <link href="../../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../../../css/myStyle.css" rel="stylesheet">
    <link href="../../css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">
    <link href="../../../css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">
    <!-- Propre? -->

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="navbar-top.css" rel="stylesheet">
    <base href="<?= $web_root ?>" />
  </head>
  <body>
    <?php
      $active = 'login';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="title">Error</div>
      <div class="main">
        <?= $error ?>
      </div>
    </main>          
  </body>
</html>
