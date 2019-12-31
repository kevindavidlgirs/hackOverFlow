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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/myStyle.css" rel="stylesheet">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/myStyle.css" rel="stylesheet">
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
      $active = 'login';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="card col-md-4 offset-md-4">
        <div class="card-header text-center header-color-white">
          <h5>Sign in</h5>
        </div>
        <div class="card-body">
          <form action="user/login" method="post">


            <?php if(array_key_exists('user', $errors)):?>            
              <input type="text" name="username" class="form-control is-invalid" placeholder="<?= $username ?>" required>
              <div class="invalid-feedback">
                <?= $errors['user']; ?>
              </div>  
            <?php else: ?> 
              <div class="form-group">
                <!--A modifier pour faire apparaître le nom de l'user qui est quand même existant si le code est mauvais -->
                <input type="text" class="form-control" name="username" <?php if($username != null){echo 'value="'.$username.'"';}else{echo 'placeholder="Username"';}?> />
              </div>
            <?php endif ?> 


            <?php if(array_key_exists('password', $errors)):?>            
              <input type="password" name="password" class="form-control is-invalid" placeholder="Password" required>
              <div class="invalid-feedback">
                <?= $errors['password']; ?>
              </div>  
            <?php else: ?> 
              <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password"/>
              </div>
            <?php endif ?>

            
            <button type="submit" class="btn btn-primary btn-dark btn-block">login</button>
          </form>
        </div>
      </div>
    </main>          
  </body>
</html>
