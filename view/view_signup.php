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
      $active = 'signup';
      include('header.html');  
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div class="card col-md-4 offset-md-4">
        <div class="card-header text-center header-color-white">
          <h5>Sign up</h5>
        </div>
        <div class="card-body">
          <form action="user/signup" method="post">
          <?php if(array_key_exists('user', $errors)):?>            
            <input type="text" name="username" class="form-control is-invalid" placeholder="<?= $username ?>" required>
              <div class="invalid-feedback">
                <?= $errors['user']; ?>
              </div>  
          <?php else: ?>   
            <div class="form-group">
              <input type="text" name="username" class="form-control" <?php if($username != null){echo 'value="'.$username.'"';}else{echo 'placeholder="Username"';}?>/>
            </div>
          <?php endif ?>
          
          <?php if(array_key_exists('name', $errors)):?>            
            <input type="text" name="fullname" class="form-control is-invalid" placeholder="<?= $fullname ?>" required>
              <div class="invalid-feedback">
                <?= $errors['name']; ?>
              </div>  
          <?php else: ?> 
            <div class="form-group">
              <input type="text" name="fullname" class="form-control" <?php if($fullname != null){echo 'value="'.$fullname.'"';}else{echo 'placeholder="Full Name"';}?>/>
            </div>
          <?php endif ?>

          <?php if(array_key_exists('email', $errors)):?>            
            <div class="form-group">
              <input type="text" name="email" class="form-control is-invalid" placeholder="<?= $email ?>" required>
              <div class="invalid-feedback">
                <?= $errors['email']; ?>
              </div>  
            </div> 
          <?php else: ?> 
            <div class="form-group">
              <input type="text" name="email" class="form-control" <?php if($email != null){echo 'value="'.$email.'"';}else{echo 'placeholder="name@example.com"';}?> />
            </div>
          <?php endif ?>

          <?php if(array_key_exists('password', $errors)):?>  
            <div class="form-group">          
              <input type="password" name="password" class="form-control is-invalid" placeholder="Password" required>
                <div class="invalid-feedback">
                  <?= $errors['password']; ?>
                </div>  
            </div>
          <?php else: ?> 
            <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Password"/>
            </div>
          <?php endif ?>

          <?php if(array_key_exists('password_confirm', $errors)):?> 
            <div class="form-group">           
              <input type="password" name="password_confirm" class="form-control is-invalid" placeholder="Confirm your password" required>
                <div class="invalid-feedback">
                  <?= $errors['password_confirm']; ?>
                </div> 
            <div class="form-group"> 
          <?php else: ?> 
            <div class="form-group">
              <input type="password" name="password_confirm" class="form-control" placeholder="Confirm your password"/>
            </div>
          <?php endif ?>
          
            <input type="submit" class="btn btn-primary btn-dark btn-block" value="Sign up"/>
          </form>
        </div>
      </div>
    </main>
  </boby>
</html>
