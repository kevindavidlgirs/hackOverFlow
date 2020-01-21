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
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
    <?php if(isset($parentId)): ?> 
      <form action="post/edit/<?= $parentId ?>/<?= $answerId ?>" method="post">
    <?php else : ?>
      <form action="post/edit/<?= $post->getPostId() ?>" method="post">
    <?php endif ?>
        <div class="form-group">
          <h5>Body</h5>
          <small>Include all information someone would need to answer your question</small>
          <?php if(array_key_exists('body', $error)):?>
            <textarea class="form-control is-invalid" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
            <div class="invalid-feedback">
              <?= $error['body']; ?>
            </div>
          <?php else: ?>
            <textarea class="form-control" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
          <?php endif ?>
        </div>
        <button type="submit" class="btn btn-dark mb-2">Edit your post</button>
      </form>
    </main>          
  </body>
</html>
