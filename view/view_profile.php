<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Hack overFlow</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/navbar-static/">

    <!-- Bootstrap core CSS -->
    
    <!-- Propre? -->   
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/myStyle.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/myStyle.css" rel="stylesheet">
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
