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
      $active = 'profile';
      include('header.html');   
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
		  <div class="card text-white bg-dark mb-3 col-md-4 offset-md-4" style="max-width: 24rem;">
        <div class="card-header text-center"><h4><?= $user->getFullName() ?></h4></div>
          <div class="card-body">
          <ul class="list-group ">
            <li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center">
              <?= '<strong>Pseudo : </strong>'.$user->getUserName() ?>
            </li>
            <li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center">
              Nombre de questions :
              <span class="badge badge-secondary badge-pill"><?= $user->get_sum_questions() ?></span>
            </li>
            <li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center">
              Nombre de réponses :
              <span class="badge badge-secondary badge-pill"><?= $user->get_sum_answers() ?></span>
            </li>
          </ul>
          </div>
        </div>
      </div>
    </main>          
  </body>
</html>
