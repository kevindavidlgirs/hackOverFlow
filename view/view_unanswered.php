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
    <base href="<?= $web_root ?>" />
    <!-- Bootstrap core CSS + fontawesome -->    
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/myStyle.css" rel="stylesheet">
    <link href="css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">

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
  </head>
  <body>
    <?php
      $active = 'question';
      include('header.html');   
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <a class="nav-link" href="post/index">Newest</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="post/unanswered">Unanswered</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="post/votes">Votes</a>
            </li>
          </ul>
        </div>
        
        <!-- A gérer
        <input class="form-control mr-sm-2  ml-auto" type="search" placeholder="Search" aria-label="Search">
        -->

        <div class="card-body">
          <ul class="list-group list-group-flush"> 
            <?php foreach($posts as $post): ?>
              <li class="list-group-item">
                <?php
                  echo "<a href=post/show/".$post->getPostId().">".$post->getTitle()."</a><br>"; 
                  echo $post->getBodyMarkedownRemoved()."<br>";
                  include('time.html');
                  //Se charge d'afficher le nombre de réponses
                  if($post->getTotalVote() === null){
                    echo "<small> (0 vote(s), ";
                  }else{
                    echo "<small> (".$post->getTotalVote()." vote(s), ";   
                  }
                  if($post->getNbAnswers() === null){
                    echo "0 answer(s))</small>";
                  }else{
                    echo $post->getNbAnswers() ." answer(s))</small>";
                  }
                ?>    
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    </main>
  </body>
</html>
