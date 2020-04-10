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
      $active = 'question';
      include('header.html');   
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs row">
            <li class="nav-item">
              <a class="nav-link <?php if($ongletSelected == 0)echo 'active'?>" href="post">Newest</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  <?php if($ongletSelected == 1)echo 'active'?>" href="post/unanswered">Unanswered</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  <?php if($ongletSelected == 2)echo 'active'?>" href="post/votes">Votes</a>
            </li>
            <?php if($ongletSelected == 3):?>
              <li class="nav-item">
                <a class="nav-link  <?php if($ongletSelected == 3)echo 'active'?>">Question tagged [<?=$tag->getTagName()?>]</a>
              </li>
            <?php endif ?>
            <li class="nav-item">
              <form action="post/<?php if($ongletSelected == 0){echo 'index';}elseif($ongletSelected == 1){echo 'unanswered';}elseif($ongletSelected == 2){echo 'votes';}elseif($ongletSelected == 3){echo 'tags/'.$tag->getTagId();}  ?>" method="post">
                <input class="form-control" type="search" name="search" placeholder="Search..." aria-label="Search">
              </form>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush"> 
            <?php foreach($posts as $post): ?>
              <li class="list-group-item">
                <?php
                  echo "<a href=post/show/".$post->getPostId().">".$post->getTitle()."</a><br>"; 
                  echo $post->getBodyMarkedownRemoved()."<br>";
                  echo "<small>Asked ".Utils::time_elapsed_string($post->getTimestamp())." by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameAuthor()."</a></small>"; 
                  echo "<small> (".$post->getTotalVote()." vote(s), ";   
                  echo $post->getNbAnswers() ." answer(s))</small>";
                  foreach($post->getTags() as $tagOfPost){
                    echo '<a type="button" class="btn button" href="post/tags/'.$tagOfPost->getTagId().'">'.$tagOfPost->getTagName().'</a>';
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
