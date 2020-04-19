<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Hack overFlow</title>

    <base href="<?= $web_root ?>" />
    
    <!-- Bootstrap core CSS + fontawesome -->    
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/myStyle.css" rel="stylesheet">
    <link href="css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">
    <link href="navbar-top.css" rel="stylesheet">
  </head>
  <body>
    <?php
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="card text-white bg-danger mb-3 col-md-4 offset-md-4" style="max-width: 24rem;">
        <div class="card-header">Error</div>
        <div class="card-body text-succes">
          <p class="card-text"><?= $error ?></p>
        </div>
      </div>
    </main>          
  </body>
</html>
