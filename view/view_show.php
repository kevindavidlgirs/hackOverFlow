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
            $datetime = new DateTime("now");
            $datetime1 = new DateTime($post->getTimestamp());
            $interval = $datetime->diff($datetime1);           
            if($interval->format('%y') > 0){
              echo "<small>Asked ".$interval->format('%y year(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";  
            }else if($interval->format('%m') > 0){
              echo "<small>Asked ".$interval->format('%m month(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else if($interval->format('%d') > 0){
              echo "<small>Asked ".$interval->format('%d day(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else if($interval->format('%h') > 0){
              echo "<small>Asked ".$interval->format('%h hour(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else if($interval->format('%i') > 0){
              echo "<small>Asked ".$interval->format('%i minute(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else if($interval->format('%s') > 10){
              echo "<small>Asked ".$interval->format('%s seconde(s)')." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";
            }else{
              echo "<small>Asked now by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameUser()."</a></small>";  
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
              <?= $post->getBody() ?><br><br>
            </div>  
          </div>
          <?= $post->getNbAnswers().' Answer(s)';?>     
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
              <div class="col">
                <?= $answer->getBody(); ?><br>
                <?php 
                  $datetime = new DateTime("now");
                  $datetime1 = new DateTime($answer->getTimestamp());
                  $interval = $datetime->diff($datetime1);
                  if($interval->format('%y') > 0){
                    echo "<small>Asked ".$interval->format('%y year(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";  
                  }else if($interval->format('%m') > 0){
                    echo "<small>Asked ".$interval->format('%m month(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";
                  }else if($interval->format('%d') > 0){
                    echo "<small>Asked ".$interval->format('%d day(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";
                  }else if($interval->format('%h') > 0){
                    echo "<small>Asked ".$interval->format('%h hour(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";
                  }else if($interval->format('%i') > 0){
                    echo "<small>Asked ".$interval->format('%i minute(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";
                  }else if($interval->format('%s') > 10){
                    echo "<small>Asked ".$interval->format('%s seconde(s)')." ago by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";
                  }else{
                    echo "<small>Asked now by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameUser()."</a></small>";  
                  }
                ?>
              </div>
            </div>  
          </li>

        <?php endforeach ?>

      </ul><br>
      <!-- Formulaire pour ajouter une réponse -->
      <div class="form-group">
        <small>Your answer</small>
        <textarea class="form-control rounded-0" name="answer" rows="10"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-dark">Post your answer</button>
    </main>          
  </body>
</html>
