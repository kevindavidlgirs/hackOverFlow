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
  </head>
  <body>
    <?php
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <ul class="list-group list-group-flush">

        <!-- Affiche la question ainsi que le temps depuis la création de celle-ci et que le créateur -->
        <?php if($post->isQuestion()): ?>
          <li class="list-group-item">
          <h5><?= $post->getTitle() ?></h5>         
          <?= "<small>Asked ".Utils::time_elapsed_string($post->getTimestamp())." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameAuthor()."</a></small><br>"; ?>
          <?php foreach($post->getTags() as $tag): ?>
            <span class="buttons"><a type="button" class="btn pad" href="post/tags/<?= $tag->getTagId() ?>"><?= $tag->getTagName() ?></a></span>
          <?php endforeach ?> 
          </li>
          <li class="list-group-item">
            <?= $post->getBodyMarkedown() ?>
          </li>
        <?php else: ?>   
          <h5>Edit your comment to this answer</h5>          
          <li class="list-group-item">
            <?= $post->getBodyMarkedown() ?>
            <?= "<small>Asked ".Utils::time_elapsed_string($post->getTimestamp())." ago by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameAuthor()."</a></small><br>"; ?>
          </li>
        <?php endif ?>
    
      </ul><br>
      <form action="comment/edit/<?= $comment->getCommentId() ?>/<?= $post->getPostId() ?>/<?php if(!$post->isQuestion()){echo $question->getPostId();}?>" method="post">
        <div class="form-group">
          <small>Edit a comment</small>
            <?php if(array_key_exists('body', $error)): ?>
              <textarea class="form-control is-invalid" type="text" name="body" rows="1"></textarea>
              <div class="invalid-feedback">
                <?= $error['body']; ?>
              </div>
            <?php else: ?>
              <textarea class="form-control rounded-0" name="body" rows="2"><?= $comment->getBody() ?></textarea>
            <?php endif ?>
        </div>
        <button type="submit" class="btn btn-primary btn-dark">Save</button>
      </form>
    </main>          
  </body>
</html>
