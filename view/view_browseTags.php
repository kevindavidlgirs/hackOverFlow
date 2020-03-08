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
      $active = 'tag';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <table class="table table-striped table-dark">
        <thead>
          <tr>
            <th scope="col">TagName</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tags as $tag): ?>
              <tr>
                <td>
                    <?= $tag->getTagName()."  (<a href=/>".$tag->getNbQuestionsAssociees()." posts</a>)" ?>
                </td>
                <td>
                  <?php if(isset($user) && $user->getUserName() == 'admin'): ?> <!-- plutôt le rôle ! -->
                    <form action="post/unanswered" method="post">
                      <input class="form-control" size="5" placeholder="<?= $tag->getTagName() ?>" >
                      <button type='submit' class='btn btn-outline-*' name='edit'><i class='fas fa-edit'></i></button>
                      <button type='submit' class='btn btn-outline-*' name='delete'><i class='fas fa-trash-alt'></i></button>
                    </form>
                  <?php endif ?>  
                </td>
              </tr>
          <?php endforeach?>
            <!-- <tr>
              <td></td>
              <td></td>
            </tr> -->
        </tbody>
      </table>
    </main>          
  </body>
</html>
