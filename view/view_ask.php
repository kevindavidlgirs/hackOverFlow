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
        
      });
    </script>
  </head>
  <body>
    <?php
      $active = 'ask';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <form action="post/ask" method="post">
        
        <!-- Gestion du titre -->
        <div class="form-group">
          <label><strong>Title</strong></label><br>
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
        
        <!-- Gestion des tags (S'il n'y a pas de Tags? A voir....) -->
        <div class="form-group">
          <label><strong>Tags</strong></label><br>
          <small>Add up to <?= $max_tags ?> tags to describe what your question is about</small><br>
          <?php if(array_key_exists('tags', $errors)):?>
            <?php foreach($allTags as $tag): ?>
              <div class="form-check form-check-inline ">
                <input class="form-check-input is-invalid" type="checkbox" name="choice[]" value="<?= $tag->getTagId() ?>">
                <label class="form-check-label" for="inlineCheckbox1"><?= $tag->getTagName()?></label>
              </div>
            <?php endforeach ?><br>
            <small style="color:crimson"><?= $errors['tags']; ?></small>
          <?php else: ?>
            <?php foreach($allTags as $tag): ?>
              <div class="form-check form-check-inline ">
                <input class="form-check-input" type="checkbox" name="choice[]" value="<?= $tag->getTagId() ?>">
                <label class="form-check-label" for="inlineCheckbox1"><?= $tag->getTagName()?></label>
              </div>
            <?php endforeach ?>
          <?php endif ?>
        </div>

        <!-- Gestion du body -->
        <div class="form-group">
          <label><strong>Body</strong></label><br>
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
