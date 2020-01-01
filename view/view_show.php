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
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <h5><?= $post->getTitle() ?></h5>
          <?php 
            $datetime = new DateTime("now");
            $datetime1 = new DateTime($post->getTimestamp());
            $interval = $datetime->diff($datetime1);
                        
            if($interval->format('%d') > 0){
              echo "<small>Asked ".$interval->format('%d day(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else{
              echo "<small>Asked ".$interval->format('%h hour(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }
          ?>
        </li>
        <li class="list-group-item">
            <?= $post->getBody() ?><br>
            <?= $post->getNbAnswers()?>
        </li>
        <li class="list-group-item">
            
        </li>
      </ul>
    </main>          
  </body>
</html>
