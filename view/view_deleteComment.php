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
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <form action='comment/delete/<?= $comment->getCommentId()?>/<?= $postId ?>' method='post'>
        <div class="card col-md-4 offset-md-4">
          <div class="card-header text-center header-color-white">
            <h3>Are you sure you want delete this comment ?</h3>
          </div>
          <div class="card-body text-center">
              <p>This process cannot be undone.</p>
              <button type="submit" class="btn btn-primary btn-dark" name="cancel"><i class="fas fa-ban fa-lg"></i></button>
              <button type="submit" class="btn btn-primary btn-dark" name="delete_confirmation"><i class='fas fa-trash-alt fa-lg'></i></button>
          </div>
        </div>
      </form>
    </main>          
  </body>
</html>
