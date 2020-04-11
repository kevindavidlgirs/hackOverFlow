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
      <?php if($post->isQuestion()):?>

        <form action="post/edit/<?= $post->getPostId() ?>" method="post">
          <div class="form-group">
            <h5>Title</h5>
            <small>Be specific and imagine you're asking a question to another person</small>
            <?php if(array_key_exists('title', $errors)):?>
              <input class="form-control is-invalid" type="text" name="title" value="<?= $post->getTitle() ?>">
              <div class="invalid-feedback">
                <?= $errors['title']; ?><br>
              </div>
            <?php else: ?>
              <input class="form-control" type="text" name="title" value="<?= $post->getTitle() ?>"><br>
            <?php endif ?>
            <h5>Body</h5>
            <small>Include all information someone would need to answer your question</small>
            <?php if(array_key_exists('body', $errors)):?>
              <textarea class="form-control is-invalid" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
              <div class="invalid-feedback">
                <?= $errors['body']; ?>
              </div>
            <?php else: ?>
              <textarea class="form-control" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
            <?php endif ?>
          </div>
          <button type="submit" class="btn btn-dark mb-2">Save</button>
        </form>

      <?php else: ?>
      
        <form action="post/edit/<?= $post->getParentId() ?>/<?= $post->getPostId() ?>" method="post">
          <div class="form-group">
            <h5>Body</h5>
            <small>Edit your answer to make it understandable</small>
            <?php if(array_key_exists('body', $errors)):?>
              <textarea class="form-control is-invalid" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
              <div class="invalid-feedback">
                <?= $errors['body']; ?>
              </div>
            <?php else: ?>
              <textarea class="form-control" type="text" name="body" rows="10"><?= $post->getBody() ?></textarea>
            <?php endif ?>
          </div>
          <button type="submit" class="btn btn-dark mb-2">Save</button>
        </form>

      <?php endif ?>
    </main>          
  </body>
</html>
