<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Hack overFlow</title>

    <!-- Bootstrap core CSS + fontawesome -->    
    <!-- Propre? -->   
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/myStyle.css" rel="stylesheet">
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/myStyle.css" rel="stylesheet">
    <link href="../../css/fontawesome/fontawesome-free-5.12.0-web/css/all.css" rel="stylesheet">
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
        <!-- Affiche la question ainsi que le temps depuis la création de celle-ci et le créateur -->
        <li class="list-group-item">
          <h5><?= $post->getTitle() ?></h5>
          <?php            
            include("time.html");
            if(isset($_SESSION['user']) && $_SESSION['user']->getFullName() === $post->getFullNameUser()){
              echo "<form action='post/edit/".$post->getPostId()."' method='post' style='display: inline-block'>";
              echo "<button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'></i></button>";
              echo "</form>";
              if($post->getNbAnswers() < 1){
                echo "<form action='post/delete/".$post->getPostId()."' method='post' style='display: inline-block'>";
                echo "<button type='submit' class='btn btn-outline-*' name='delete'><i class='fas fa-trash-alt'></i></button>";
                echo "</form>";
              }
            }
          ?>
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col col-lg-1">
              <!-- Getion des boutons like si l'utilisateur est connecté -->
              <?php if(isset($_SESSION['user'])):?>

                <?php if($post->get_upDown_vote($_SESSION['user']->getUserId(), $post->getPostId()) == 1): ?>
                  <a class="btn" href="post/like/1/<?= $post->getPostId()?>">
                    <i class="fas fa-heart fa-7px"></i>
                  </a><br>
                <?php else: ?>
                  <a class="btn" href="post/like/1/<?= $post->getPostId()?>">
                    <i class="far fa-heart fa-7px"></i>
                  </a><br>
                <?php endif ?>
                <!-- Affiche le nombre de vote entre le button like et dislike -->
                <?= $post->getNbVote() ?>
                <small>Votes</small>
                <!-- Getion des boutons dislike si l'utilisateur est connecté -->
                <?php if($post->get_upDown_vote($_SESSION['user']->getUserId(), $post->getPostId()) == -1): ?>
                  <a class="btn" href="post/like/-1/<?= $post->getPostId()?>">
                  <i class="fas fa-frown"></i>
                  </a>
                <?php else: ?>
                  <a class="btn" href="post/like/-1/<?= $post->getPostId()?>">
                    <i class="far fa-frown" ></i>
                  </a>
                <?php endif ?>
                          
              
              <?php else: ?>  
                <!-- Getion des boutons like et dislike si l'utilisateur est un visiteur -->  
                <a class="btn" href="user/signup">
                  <i class="far fa-heart fa-7px"></i>
                </a><br>
                <?= $post->getNbVote() ?>
                <small>Votes</small>
                <a class="btn" href="user/signup">
                  <i class="far fa-frown"></i>
                </a>

              <?php endif ?>
            </div>
            <!-- affiche le body du post sélectionné -->
            <div class="col">
              <?= $post->getBodyMarkedown() ?>
            </div>  
          </div>
          
          <?php
            if($post->getNbAnswers() > 0){
              echo $post->getNbAnswers().' Answer(s)';
            }else{
              echo '0 Answer(s)';
            }
          ?>     
        </li>

        <!--Affiche les réponses-->
        <?php foreach($post->getAnswers() as $answer) : ?>

          <li class="list-group-item">
            <div class="row">
              <div class="col col-lg-1">
                <!-- Getion des boutons like si l'utilisateur est connecté -->
                <?php if(isset($_SESSION['user'])):?>

                  <?php if($post->get_upDown_vote($_SESSION['user']->getUserId(), $answer->getPostId()) == 1): ?>
                    <a class="btn" href="post/like/1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="fas fa-heart fa-7px"></i>
                    </a><br>
                  <?php else: ?>
                    <a class="btn" href="post/like/1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="far fa-heart fa-7px"></i>
                    </a><br>
                  <?php endif ?>
                  <!-- Affiche le nombre de vote entre le button like et dislike -->
                  <?= $answer->getNbVote() ?>
                  <small>Votes</small>
                  <!-- Getion des boutons dislike si l'utilisateur est connecté -->
                  <?php if($post->get_upDown_vote($_SESSION['user']->getUserId(), $answer->getPostId()) == -1): ?>
                    <a class="btn" href="post/like/-1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="fas fa-frown"></i>
                    </a>
                  <?php else: ?>
                    <a class="btn" href="post/like/-1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="far fa-frown" ></i>
                    </a>
                  <?php endif ?>
                              
                  
                <?php else: ?>  

                  <!-- Getion des boutons like et dislike si l'utilisateur est un visiteur -->  
                  <a class="btn" href="user/signup">
                    <i class="far fa-heart fa-7px"></i>
                  </a><br>
                  <?= $answer->getNbVote() ?>
                  <small>Votes</small>
                  <a class="btn" href="user/signup">
                    <i class="far fa-frown"></i>
                  </a>

                <?php endif ?>
              </div>
              <!-- Gestion boutons edit et delete -->
              <div class="col">
                <?= $answer->getBodyMarkedown(); ?><br>
                <?php 
                  include("time.html");
                  if(isset($_SESSION['user'])){
                    if($post->getAcceptedAnswerId() !== $answer->getPostId() && $_SESSION['user']->getUserId() === $post->getAuthorId()){
                      echo "<form action='post/accept_question/".$post->getPostId()."/".$answer->getPostId()."' method='post' style='display: inline-block'>";
                      echo "<button type='submit' class='btn btn-outline-*' class='accept'><i class='far fa-check-circle'></i></button>";
                      echo "</form>"; 
                    }
                    if($_SESSION['user']->getFullName() === $answer->getFullNameUser()){
                      echo "<form action='post/edit/".$post->getPostId()."/".$answer->getPostId()."' method='post' style='display: inline-block'>";
                      echo "<button type='submit' class='btn btn-outline-*' class='edit'><i class='fas fa-edit'></i></button>";
                      echo "</form>";
                      echo "<form action='post/delete/".$post->getPostId()."/".$answer->getPostId()."' method='post' style='display: inline-block'>";
                      echo "<button type='submit' class='btn btn-outline-*' name='delete'><i class='fas fa-trash-alt'></i></button>";
                      echo "</form>";  
                    }
                  }
                ?>
              </div>
            </div>  
          </li>

        <?php endforeach ?>

      </ul><br>
      <!-- Formulaire pour ajouter une réponse -->
      <form action="post/answer/<?= $post->getPostId() ?>" method="post">
        <div class="form-group">
          <small>Your answer</small>
          <textarea class="form-control rounded-0" name="answer" rows="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-dark">Post your answer</button>
      </form>
    </main>          
  </body>
</html>
