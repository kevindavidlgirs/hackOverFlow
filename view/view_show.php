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
    <!-- Bootstrap core CSS + fontawesome -->    

    <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="lib/jquery.validate.min.js" type="text/javascript"></script>
    <script>
      let questionId = "<?= $post->getPostId() ?>"
      let answerId = ''
      let body
      let editLink
      let html
      let data
      let answerForm

      $(function(){
        configActionsForComments()
        answerForm = $("#answerForm")
        configActionsForAnswer()
        configGestionErrorsForAnswer()
      });
      
      function configActionsForComments(){
        $("a[name='edit']").click(function(e){

          e.preventDefault()

          $("a[name='edit']").next('form').hide()
          $("a[name='edit']").show()

          editLink = $(this)
          let form = editLink.next('form')
          answerId = editLink.attr("id")

          configGestionErrorsForComments(form)
          editLink.toggle("slide")

          form.toggle("slide", function(){
            addCommentButton = $(this).find('[name="addCommentButton"]')
            addCommentButton.attr("disabled", true);

            $(this).find(":input").on("input", function (){
              addCommentButton.attr("disabled", $(this).val().length < 10 || $(this).val().length > 100);
            });

            $(this).find('[name="addCommentButton"]').click(function(){
              $("a[name='edit']").next('form').hide()
              $("a[name='edit']").show()
              body = form.find("#body").val()
              form.find(":input").val('')
              addCmt(); 
            });

            $(this).find('[name="cancelButton"]').click(function(){
              $("a[name='edit']").next('form').hide()
              $("a[name='edit']").show()
              form.find(":input").val('')
            });

            if($(this).is(":visible")){
              $(this).find('input').focus();
            }
          });
        }); 
      }

      function configActionsForAnswer(){
        let addAnswerButton = $('[name="addAnswerButton"]')
        addAnswerButton.attr("disabled", true);
        answerForm.find('[name="body"]').on("input", function (){
          addAnswerButton.attr("disabled", $(this).val().length < 30);
        });
      }

      function configGestionErrorsForComments(form){
        form.validate({
          rules: {
            body:{
              required: true,
              minlength: 10,
              maxlength: 100
            }
          },
          messages: {
            body: {
              required: "Write a comment or click on \"cancel\" button",
              minlength: "The length of the comment must be greater than or equal to 10.",
              maxlength: "Comment length must be less than or equal to 100."
            }
          },
          errorPlacement: function(error, element) {
            error.addClass("invalid-feedback")
            error.insertAfter(form.find("#cancelButton"));
          },
          highlight: function ( element, error ) {
            $(element).addClass("is-invalid");
				  },
				  unhighlight: function ( element, error) {
            $(element).removeClass("is-invalid");
				  }
        });
      }


      function configGestionErrorsForAnswer(){
        answerForm.validate({
          rules: {
            body:{
              required: true,
              minlength: 30,
            }
          },
          messages: {
            body: {
              required: "the field above requires a minimum of 30 characters",
              minlength: "The length of the body must be greater than or equal to 30 characters.",
            }
          },
          errorPlacement: function(error, element) {
            error.addClass("invalid-feedback")
            error.insertBefore(answerForm.find("#addAnswerButton"));
          },
          highlight: function ( element, error ) {
            $(element).addClass("is-invalid");
				  },
				  unhighlight: function ( element, error) {
            $(element).removeClass("is-invalid");
				  }
        });
      }
      

      function addCmt(){
        if(answerId == questionId)
          answerId = '';
        $.post("comment/add_comment_service/"+questionId+"/"+answerId+"/", {body : body}, function(data){
          //Probleme avec parsing et data defined
          data = JSON.parse(data);
          console.log(data)
          editLink.before(buildComment(data));
        });
      }

      function buildComment(data){
        html = "<hr><small>"+data[0]['body']+"<a href='user/profile/"+data[0]['userId']+"'>"+data[0]['fullName']+"</a></small> <small style='color:rgb(250, 128, 114)'>"+data[0]['timestamp']+"</small>";
                if(answerId === ''){
                  html += "<a href=\"comment/edit/"+data[0]['commentId']+"/"+questionId+"/\"><small style=\"color:rgb(119, 136, 153);\"> edit</small></a>"+
                          "<a href=\"comment/delete/"+data[0]['commentId']+"/"+questionId+"/\"><small style=\"color:rgb(119, 136, 153);\"> delete</small></a>";
                }else{
                  html += "<a href=\"comment/edit/"+data[0]['commentId']+"/"+answerId+"/"+questionId+"/\"><small style=\"color:rgb(119, 136, 153);\"> edit</small></a>"+
                          "<a href=\"comment/delete/"+data[0]['commentId']+"/"+answerId+"/"+questionId+"/\"><small style=\"color:rgb(119, 136, 153);\"> delete</small></a>";  
                }
        html += "</hr><br>";
        return html;                    
      }
    </script>

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
          <?php if($user !== null && ($user->isAdmin() || $user->getFullName() === $post->getFullNameAuthor())): ?>
            <form action='post/edit/<?= $post->getPostId() ?>' method='post' style='display: inline-block'>
              <button type='submit' class='btn pad' name='edit'><i class='fas fa-edit'></i></button>
            </form>
          <?php if($user->isAdmin() || ($post->getNbAnswers() < 1 && !$post->hasComments())): ?>
            <form action='post/delete/<?= $post->getPostId() ?>' method='post' style='display: inline-block'>
              <button type='submit' class='btn pad' name='delete'><i class='fas fa-trash-alt'></i></button>
            </form>
            <?php endif ?>
          <?php endif ?>
          <br>
          <!-- Gestion des boutons de supression et d'édition "end" -->

          <!-- Gestion des tags "start"-->
          <?php if($user !== null && ($user->isAdmin() || $user->getFullName() === $post->getFullNameAuthor())): ?>

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
                  <?php foreach($post->getComments() as $comment): ?>
                    <hr><?= "<small>".$comment->getBodyMarkedown()."<a href='user/profile/".$comment->getAuthorId()."'>".$comment->getFullNameAuthor()."</a></small> <small style='color:rgb(250, 128, 114)'>".Utils::time_elapsed_string($comment->getTimestamp())."</small>"?>
                      <?php if($user !== null && ($user->isAdmin() || $user->getUserid() === $comment->getAuthorId())): ?>
                        <a href="comment/edit/<?= $comment->getCommentId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">edit</small></a>
                        <a href="comment/delete/<?= $comment->getCommentId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">delete</small></a>
                      <?php endif ?>
                    </hr>
                  <?php endforeach ?><br>
                <?php endif ?>
                <?php if($user !== null): ?><a id="<?= $post->getPostId() ?>" type="button" name="edit" href="comment/add/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">add a comment</small></a><?php endif ?>
                
                <!-- formulaire JAVASCRIPT -->
                <form style="display:none">
                  <div class="form-group form-inline">
                    <input id="body" name="body" type="text" class="form-control mb-2 mr-sm-1 col-8">
                    <button name="addCommentButton" type="button" class="btn btn-dark btn-primary mb-2 mr-sm-1">Add your comment</button>
                    <button name="cancelButton" type="button" class="btn btn-light btn-primary mb-2">Cancel</button>
                  </div>
                </form>
                <!-- formulaire JAVASCRIPT -->

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
                    <?php if($user->isAdmin() || $user->getUserId() === $post->getAuthorId()): ?>
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
                <?= "<small style='color:rgb(250, 128, 114)'>Asked ".Utils::time_elapsed_string($answer->getTimestamp())." by <a href='user/profile/".$answer->getAuthorId()."'>".$answer->getFullNameAuthor()."</a></small>"; ?>              
                <?php if($user !== null): ?>
                  <!-- Gestion des boutons d'acceptance -->
                  <?php if($post->getAcceptedAnswerId() !== $answer->getPostId() && ($user->isAdmin() || $user->getUserId() === $post->getAuthorId())): ?>
                    <form action='post/accept_answer/<?= $post->getPostId() ?>/<?= $answer->getPostId() ?>' method='post' style='display: inline-block'>
                      <button type='submit' class='btn btn-outline-*' name='accept'><i class='far fa-check-circle'></i></button>
                    </form>
                  <?php endif ?>
                  <!-- Gestion boutons edit et delete -->
                  <?php if($user->isAdmin() || ($user->getFullName() === $answer->getFullNameAuthor())): ?>
                    <form action='post/edit/<?= $post->getPostId() ?>/<?= $answer->getPostId() ?>' method='post' style='display: inline-block'>
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'></i></button>
                    </form>
                    <?php if($user->isAdmin() || !$answer->hasComments()): ?>
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
                        <hr><?= "<small>".$comment->getBodyMarkedown()."<a href='user/profile/".$comment->getAuthorId()."'>".$comment->getFullNameAuthor()."</a></small> <small style='color:rgb(250, 128, 114)'>".Utils::time_elapsed_string($comment->getTimestamp())."</small>"?>
                        <?php if($user !== null && ($user->isAdmin() || $user->getUserid() === $comment->getAuthorId())): ?>
                            <a href="comment/edit/<?= $comment->getCommentId() ?>/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">edit</small></a>
                            <a href="comment/delete/<?= $comment->getCommentId() ?>/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">delete</small></a>
                          <?php endif ?>
                        </hr>
                      <?php endforeach ?><br>
                    <?php endif ?>

                    <?php if($user !== null): ?><a id="<?= $answer->getPostId() ?>" name="edit" href="comment/add/<?= $answer->getPostId() ?>/<?= $post->getPostId() ?>"><small style="color:rgb(119, 136, 153);">add a comment</small></a><?php endif ?>    
                    
                    <!-- formulaire JAVASCRIPT -->
                    <form style="display:none">
                      <div class="form-group form-inline">
                        <input id="body" name="body" type="text" class="form-control mb-2 mr-sm-1 col-8">
                        <button id="addCommentButton" type="button" class="btn btn-dark btn-primary mb-2 mr-sm-1">Add your comment</button>
                        <button id="cancelButton" type="button" class="btn btn-light btn-primary mb-2">Cancel</button>
                      </div>
                    </form>
                    <!-- formulaire JAVASCRIPT -->

                  </div> 
                </div> 

              </div>
            </div>  
          </li>

        <?php endforeach ?>

      </ul><br>

      <!-- Formulaire pour ajouter une réponse -->
      <form id="answerForm" action="post/answer/<?= $post->getPostId() ?>" method="post">
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
        <button id="addAnswerButton" name="addAnswerButton" type="submit" class="btn btn-primary btn-dark">Post your answer</button>
      </form>
    </main>          
  </body>
</html>
