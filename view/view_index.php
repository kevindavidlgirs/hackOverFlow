
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
<link href="../css/bootstrap.min.css" rel="stylesheet">


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
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <a class="navbar-brand" href="post/index">Hack OverFlow</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link active" href="post/index">Question <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user/login">login</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="user/signup">SignUp<span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>
<!-- MAIN -->
<main role="main" class="container">
<div class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="post/index">Récents</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
      <li>
      <form class="form-inline">
        <input class="form-control mr-sm-2  ml-auto" type="search" placeholder="Search" aria-label="Search">
      </form>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <ul class="list-group list-group-flush"> 
    <?php foreach($posts as $post): ?>
      <li class="list-group-item">
        <?php
          echo "<a href=post/index>".$post->getTitle()."</a><br>"; 
          echo $post->getBody()."<br>";
          
          //Se charge de déterminer et d'afficher les jours (ou heures) passés depuis la création d'un post. 
          $datetime = new DateTime("now");
          $datetime1 = new DateTime($post->getTimestamp());
          $interval = $datetime->diff($datetime1);
          if($interval->format('%d') > 0){
            echo "<small>Il y a ".$interval->format('%d jour(s)')." par ".$post->getUser()."</small>";
          }else{
            echo "<small>Il y a ".$interval->format('%h heure(s)')." par ".$post->getUser()."</small>";
          }
          //Se charge d'afficher le nombre de réponses
          if($post->getTotalVote() === null){
            echo "<small> (0 vote(s), ". $post->getNbAnswers() ." réponse(s))</small>";
          }else{
            echo "<small> (".$post->getTotalVote()." vote(s), ". $post->getNbAnswers() ." réponse(s))</small>";   
          }
        ?>
      </li>
    <?php endforeach ?>
    </ul>
  </div>
</div>
</main>
</html>
