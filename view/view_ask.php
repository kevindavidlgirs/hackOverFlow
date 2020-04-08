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
      $active = 'ask';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <form action="post/ask" method="post">
        
        <div class="form-group">
          <label>Title</label><br>
          <small>Be specific and imagine you're asking a question to another person</small>
          <?php if(array_key_exists('title', $errors)):?>
            <input class="form-control is-invalid" type="text" name="title" value="<?= $title ?>">
            <div class="invalid-feedback">
              <?= $errors['title']; ?><br>
            </div>
          <?php else: ?>
            <input class="form-control" type="text" name="title" value="<?= $title ?>"><br>
          <?php endif ?>
        </div>
        
        <div class="form-group">
          <label>Body</label><br>
          <small>Include all the information someone would need to answer your question</small>
          <?php if(array_key_exists('body', $errors)):?>
            <textarea class="form-control is-invalid" type="text" name="body" rows="6"><?= $body ?></textarea>
            <div class="invalid-feedback">
              <?= $errors['body']; ?>
            </div>
          <?php else: ?>
            <textarea class="form-control" type="text" name="body" rows="6"><?= $body ?></textarea>
          <?php endif ?>
        </div>
        <button type="submit" class="btn btn-dark mb-2">Publish your question</button>

      </form>
    </main>          
  </body>
</html>
