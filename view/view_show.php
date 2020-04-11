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
      <ul class="list-group list-group-flush">
        <!-- Affiche la question ainsi que le temps depuis la création de celle-ci et que le créateur -->
        <li class="list-group-item">
          <h5><?= $post->getTitle() ?></h5>          
          <?= "<small style='color:rgb(250, 128, 114)'>Asked ".Utils::time_elapsed_string($post->getTimestamp())." by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameAuthor()."</a></small>"; ?>
          
          <!-- Gestion des boutons de supression et d'édition "start" -->
          <?php if($user !== null && $user->getFullName() === $post->getFullNameAuthor()): ?>
            <form action='post/edit/<?= $post->getPostId() ?>' method='post' style='display: inline-block'>
              <button type='submit' class='btn pad' name='edit'><i class='fas fa-edit'></i></button>
            </form>
          <?php if($post->getNbAnswers() < 1 && !$post->hasComments()): ?>
            <form action='post/delete/<?= $post->getPostId() ?>' method='post' style='display: inline-block'>
              <button type='submit' class='btn pad' name='delete'><i class='fas fa-trash-alt'></i></button>
            </form>
            <?php endif ?>
          <?php endif ?>
          <br>
          <!-- Gestion des boutons de supression et d'édition "end" -->

          <!-- Gestion des tags "start"-->
          <?php if($user !== null && $user->getFullName() === $post->getFullNameAuthor()): ?>

            <?php foreach($post->getTags() as $tag): ?>
              <span class="buttons"><a type="button" class="btn pad" href="post/tags/<?= $tag->getTagId() ?>"><?= $tag->getTagName() ?></a><a class="btn pad" href="post/removeTag/<?= $post->getPostId() ?>/<?= $tag->getTagId() ?>"><i class="far fa-times-circle fa-2px"></i></a></span>
            <?php endforeach ?>
            
            <?php if($post->getNbTags() < $max_tags): ?>
              <form action="post/addTag/<?= $post->getPostId() ?>" method="post" class="form-inline" style='display: inline-block'>
                <select name="tag" style="font-size: .8em">
                  <?php foreach($allTags as $tag): ?>
                    <?php $containsTag = false; ?>
                    
                    <!-- DEVRAIT CHANGER -->
                    <?php foreach($post->getTags() as $postTag): ?>
                      <?php if($postTag->getTagName() === $tag->getTagName()): ?>
                        <?php $containsTag = true; ?>
                      <?php endif ?>
                    <?php endforeach ?>
                    <!-- DEVRAIT CHANGER -->

                    <?php if(!$containsTag): ?>
                      <option><?=$tag->getTagName()?></option>
                    <?php endif ?>
                  <?php endforeach ?>
                </select> 
                <button type="submit" value="Submit" class='btn pad'><i class="fas fa-plus"></i></button>
              </form>  
            <?php endif ?> 

          <?php else: ?>
            
            <?php foreach($post->getTags() as $tag): ?>
                <a type="button" class="btn button" href="post/tags/<?= $tag->getTagId() ?>"><?= $tag->getTagName() ?></a>
            <?php endforeach ?>  
            
          <?php endif ?>  
          <!-- Gestion des tags "end"-->
              
        </li>
        <li class="list-group-item">
          <div class="row">
            <div class="col col-lg-1">
              <!-- Getion des boutons like si l'utilisateur est connecté -->
              <?php if($user !== null):?>

                <?php if($post->get_upDown_vote($user->getUserId(), $post->getPostId()) == 1): ?>
                  <a class="btn" href="post/like/1/<?= $post->getPostId()?>">
                    <i class="fas fa-heart fa-7px"></i>
                  </a><br>
                <?php else: ?>
                  <a class="btn" href="post/like/1/<?= $post->getPostId()?>">
                    <i class="far fa-heart fa-7px"></i>
                  </a><br>
                <?php endif ?>
                <!-- Affiche la somme des votes entre le button like et dislike -->
                <?= $post->getTotalVote() ?>
                <small>vts.</small>
                <!-- Getion des boutons dislike si l'utilisateur est connecté -->
                <?php if($post->get_upDown_vote($user->getUserId(), $post->getPostId()) == -1): ?>
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
                <?= $post->getTotalVote() ?>
                <small>vts.</small>
                <a class="btn" href="user/signup">
                  <i class="far fa-frown"></i>
                </a>

              <?php endif ?>
            </div>
            <!-- affiche le body du post sélectionné -->
            <div class="col">
              <?= $post->getBodyMarkedown() ?>
              <!-- affiche les commentaires du post sélectionné -->
              <div style="margin-left : 25px;">
                <?php if($post->hasComments()):  ?>
                <!-- ATTENTION UTILISER MARKDOWN POUR CHAQUE COMMENTAIRE -->
                  <?php foreach($post->getComments() as $comment): ?>
                    <hr><?= "<small>".$comment->getBody()." - <a href='user/profile/".$comment->getAuthorId()."'>".$comment->getFullNameAuthor()."</a></small> <small style='color:rgb(250, 128, 114)'>".Utils::time_elapsed_string($comment->getTimestamp())."</small>"?>
                      <?php if($user !== null && ($user->isAdmin() || $user->getUserid() === $comment->getAuthorId())): ?>
                        <a href="comment/edit/<?= $comment->getCommentId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">edit</small></a>
                        <a href="comment/delete/<?= $comment->getCommentId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">delete</small></a>
                      <?php endif ?>
                    </hr>
                  <?php endforeach ?><br>
                <?php endif ?>
                <?php if($user !== null): ?><a href="comment/add/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">add a comment</small></a><?php endif ?>
              </div>    
            </div> 
          </div>
          <span><?= $post->getNbAnswers().' Answer(s)';?></span>      
        </li>
            
        <!--Affiche les réponses-->
        <?php foreach($post->getAnswers() as $answer) : ?>

          <li class="list-group-item">
            <div class="row">
              <div class="col col-lg-1">

                <?php if($user !== null):?>

                  <!-- Gestion des boutons like si l'utilisateur est connecté -->
                  <?php if($post->get_upDown_vote($user->getUserId(), $answer->getPostId()) == 1): ?>
                    <a class="btn" href="post/like/1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="fas fa-heart fa-7px"></i>
                    </a><br>
                  <?php else: ?>
                    <a class="btn" href="post/like/1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="far fa-heart fa-7px"></i>
                    </a><br>
                  <?php endif ?>

                  <!-- Affiche le nombre de vote entre le butons like et dislike -->
                  <?= $answer->getTotalVote() ?>
                  <small>vts.</small>
                  <!-- Gestion des boutons dislike si l'utilisateur est connecté -->
                  <?php if($post->get_upDown_vote($user->getUserId(), $answer->getPostId()) == -1): ?>
                    <a class="btn" href="post/like/-1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="fas fa-frown"></i>
                    </a>
                  <?php else: ?>
                    <a class="btn" href="post/like/-1/<?= $post->getPostId()?>/<?= $answer->getPostId()?>">
                      <i class="far fa-frown" ></i>
                    </a>
                  <?php endif ?>    
                  <!-- Gestion des boutons lorsqu'une question a été acceptée -->         
                  <?php if($post->getAcceptedAnswerId() === $answer->getPostId()): ?>
                    <i class="fas fa-check greeniconcolor"></i>
                    <?php if($user->getUserId() === $post->getAuthorId()): ?>
                      <form action="post/delete_accepted_answer/<?= $post->getPostId()?>" method="post" style='display: inline-block'>
                        <button type='submit' class='btn btn-outline-*' name='delete_acceptation'><i class="fas fa-times rediconcolor"></i></button>
                      </form>
                    <?php endif ?>
                  <?php endif ?>  

                <?php else: ?>  

                  <!-- Gestion des boutons like et dislike si l'utilisateur est un visiteur -->  
                  <a class="btn" href="user/signup">
                    <i class="far fa-heart fa-7px"></i>
                  </a><br>
                  <?= $answer->getTotalVote() ?>
                  <small>vts.</small>
                  <a class="btn" href="user/signup">
                    <i class="far fa-frown"></i>
                  </a>
                  <?php if($post->getAcceptedAnswerId() === $answer->getPostId()): ?>
                    <i class="fas fa-check greeniconcolor"></i>
                  <?php endif ?> 

                <?php endif ?>
              
              </div>
              <div class="col">

                <!-- Affiche le corps de la réponse -->
                <?= $answer->getBodyMarkedown(); ?><br> 
                <?= "<small style='color:rgb(250, 128, 114)'>Asked ".Utils::time_elapsed_string($post->getTimestamp())." by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameAuthor()."</a></small>"; ?>              
                <?php if($user !== null): ?>
                  <!-- Gestion des boutons d'acceptance -->
                  <?php if($post->getAcceptedAnswerId() !== $answer->getPostId() && $user->getUserId() === $post->getAuthorId()): ?>
                    <form action='post/accept_answer/<?= $post->getPostId() ?>/<?= $answer->getPostId() ?>' method='post' style='display: inline-block'>
                      <button type='submit' class='btn btn-outline-*' name='accept'><i class='far fa-check-circle'></i></button>
                    </form>
                  <?php endif ?>
                  <!-- Gestion boutons edit et delete -->
                  <?php if($user->getFullName() === $answer->getFullNameAuthor()): ?>
                    <form action='post/edit/<?= $post->getPostId() ?>/<?= $answer->getPostId() ?>' method='post' style='display: inline-block'>
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'></i></button>
                    </form>
                    <?php if(!$answer->hasComments()): ?>
                      <form action='post/delete/<?= $post->getPostId() ?>/<?= $answer->getPostId() ?>' method='post' style='display: inline-block'>
                        <button type='submit' class='btn btn-outline-*' name='delete'><i class='fas fa-trash-alt'></i></button>
                      </form>  
                    <?php endif ?>
                  <?php endif ?>
                <?php endif ?>
                
                <!-- affiche les commentaires de la réponse sélectionnée -->
                <div class="col">
                  <div style="margin-left : 25px;">
                    <?php if($answer->hasComments()):  ?>
                      <?php foreach($answer->getComments() as $comment): ?>
                        <!-- ATTENTION UTILISER MARKDOWN POUR CHAQUE COMMENTAIRE -->
                        <hr><?= "<small>".$comment->getBody()." - <a href='user/profile/".$comment->getAuthorId()."'>".$comment->getFullNameAuthor()."</a></small> <small style='color:rgb(250, 128, 114)'>".Utils::time_elapsed_string($comment->getTimestamp())."</small>"?>
                          <?php if($user !== null && ($user->isAdmin() || $user->getUserid() === $comment->getAuthorId())): ?>
                            <a href="comment/edit/<?= $comment->getCommentId() ?>/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">edit</small></a>
                            <a href="comment/delete/<?= $comment->getCommentId() ?>/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">delete</small></a>
                          <?php endif ?>
                        </hr>
                      <?php endforeach ?><br>
                    <?php endif ?>
                    <?php if($user !== null): ?><a href="comment/add/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">add a comment</small></a><?php endif ?>    
                  </div> 
                </div> 

              </div>
            </div>  
          </li>

        <?php endforeach ?>

      </ul><br>
      <!-- Formulaire pour ajouter une réponse -->
      
      <form action="post/answer/<?= $post->getPostId() ?>" method="post">
        <div class="form-group">
          <small>Your answer</small>
            <?php if(array_key_exists('body', $error)): ?>
              <textarea class="form-control is-invalid" type="text" name="body" rows="10"></textarea>
              <div class="invalid-feedback">
                <?= $error['body']; ?>
              </div>
            <?php else: ?>
              <textarea class="form-control rounded-0" name="body" rows="10"></textarea>
            <?php endif ?>
        </div>
        <button type="submit" class="btn btn-primary btn-dark">Post your answer</button>
      </form>
    </main>          
  </body>
</html>
