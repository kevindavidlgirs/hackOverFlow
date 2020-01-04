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
    
    <!-- Propre? -->   
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/myStyle.css" rel="stylesheet">
    <!-- Propre? -->

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
    <base href="<?= $web_root ?>" />
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
          <input class="form-control" type="text" name="title"><br>
        </div>
        <div class="form-group">
          <label>Body</label><br>
          <small>Include all the information someone would need to answer your question</small>
          <textarea class="form-control" type="text" name="body" rows="6"></textarea>
        </div>
        <button type="submit" class="btn btn-dark mb-2">Publish your question</button>
      </form>
    </main>          
  </body>
</html>
