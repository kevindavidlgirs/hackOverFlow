
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

    <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="lib/jquery.validate.min.js" type="text/javascript"></script>
    <script>
      $(function(){
        $("form :input").change(function(){
          //bugs à régler "this"... 
          $(this).parent().validate({
            rules: {
              tagName:{
                required: true,
                maxlength: 10
              }
            },
            messages: {
              tagName: {
                required: "the tag is required.",
                maxlength: "the tag must have less than 10 letters."
              }
            },
            errorPlacement: function(error, element) {
              error.addClass("invalid-feedback")
              error.insertAfter(this);
            },
            highlight: function ( element, error ) {
              $(element).addClass("is-invalid");
              //Désactiver le bouton 
            },
            unhighlight: function ( element, error) {
              $(element).removeClass("is-invalid");
              //Réactiver le bouton
				    }
          });
        });
      });
    </script>
  </head>
  <body>
    <?php
      $active = 'tag';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <table class="table table-striped table-dark">
        <thead>
          <tr>
            <th scope="col">TagName</th>
            <?php if(isset($user) && $user->isAdmin()): ?>
              <th scope="col">Action</th>
            <?php endif ?>
          </tr>

        <!-- Formulaires pour montrer les tags existants -->  
        </thead>
        <tbody>
          <?php foreach($tags as $tag): ?>
            <tr>
              <td>
                <?= $tag->getTagName()."  (<a href=post/tags/".$tag->getTagId()."/1>".$tag->getNbAssociatedQuestions()." posts</a>)" ?>
              </td>
              <td>
                <?php if(isset($user) && $user->isAdmin()): ?>
                  
                  <form action="tag/edit/<?= $tag->getTagId() ?>" method="post" class="form-inline" style='display: inline-block'>
                    
                    <!-- Erreur si le tag n'est pas unique -->
                    <?php if(array_key_exists('unicity', $error) && $error['tagId'] === $tag->getTagId()):?>
                      <input class="form-control is-invalid" type="text" name="tagName" value=<?= $error['tagName'] ?> >
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'style="color:white"></i></button>
                      <div class="invalid-feedback">
                        <?= $error['unicity']; ?><br>
                      </div>   
                    
                    <!-- Erreur si le champ est vide ou si les caractères du tags dépassent sont supérieurs à 10 -->
                    <?php elseif(array_key_exists('tagName', $error) && $error['tagId'] === $tag->getTagId()): ?>
                      <input class="form-control is-invalid" type="text" name="tagName" placeholder="<?= $tag->getTagName() ?>" >
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'style="color:white"></i></button>
                      <div class="invalid-feedback">
                        <?= $error['tagName']; ?><br>
                      </div> 
                    
                    <?php else: ?>
                      <input placeholder="<?= $tag->getTagName() ?>" type="text" name="tagName" class="form-control" >
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'style="color:white"></i></button>
                    <?php endif ?>  

                  </form>

                  <form action="tag/delete/<?= $tag->getTagId() ?>" method="post" style='display: inline-block'>
                    <button type='submit' class='btn btn-outline-*' name='delete'><i class='fas fa-trash-alt'style="color:white"></i></button>
                  </form>

                <?php endif ?>  
              </td>
            </tr>
          <?php endforeach?>
        </tbody>
      </table>
      <!-- Formulaires pour montrer les tags existants -->

      <!-- Formulaire de création d'un nouveau tag -->
      <?php if(isset($user) && $user->isAdmin()): ?>
        <tr>
          <td>
            <form action="tag/create" method="post" style='display: inline-block'>
              <?php if(array_key_exists('unicity', $error) && $error['tagId'] === null): ?>
                <input class="form-control is-invalid" type="text" name="newTag">
                <button type='submit' class='btn btn-outline-*' name='add'><i class="fas fa-plus-circle" style="color:white"></i></button>
                <div class="invalid-feedback">
                  <?= $error['unicity']; ?><br>
                </div> 
                <?php elseif(array_key_exists('tagName', $error) && $error['tagId'] === null): ?>
                  <div class="input-group invalid-feedback">
                    <input class="form-control is-invalid" type="text" name="newTag">
                    <span class="input-group-btn">
                      <button type='submit' class='btn btn-outline-*' name='add'><i class="fas fa-plus-circle" style="color:rgb(47, 79, 79)"></i></button>
                    </span>
                    <div class="invalid-feedback">
                      <?= $error['tagName']; ?><br>
                    </div> 
                  </div>
                <?php else: ?>
                  <div class="input-group">
                    <input class="form-control" type="text" name="newTag">
                    <span class="input-group-btn">
                      <button type='submit' class='btn btn-outline-*' name='add'><i class="fas fa-plus-circle" style="color:rgb(47, 79, 79)"></i></button>
                    </span>
                  </div>
                <?php endif ?>
            </form>
          </td>
       </tr> 
      <?php endif ?>
      <!-- Formulaire de création d'un nouveau tag -->

    </main>          
  </body>
</html>
