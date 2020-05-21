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
        
        $('#loginForm').validate({
          rules: {
            username: {
              required: true
            },
            password: {
              required: true
            }    
          },
          messages: {
            username: {
              required: 'username is required.'              
            },
            password: {
              required: 'password is required.'
            }
          },
          errorPlacement: function(error, element) {
            error.addClass("invalid-feedback")
            error.insertAfter(element);
          },
          highlight: function ( element, error ) {
            $(element).addClass("is-invalid");
				  },
				  unhighlight: function ( element, error) {
            $(element).removeClass("is-invalid");
				  }
        });

        $("#username").focus();

      });

    </script>
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
          <form id="loginForm" action="user/login" method="post">

            <?php if(array_key_exists('user', $errors)):?>            
              <input type="text" id="username" name="username" class="form-control is-invalid" placeholder="<?= $username ?>" required>
              <div class="invalid-feedback">
                <?= $errors['user']; ?>
              </div>  
            <?php else: ?> 
              <div class="form-group">
                <input type="text" id="username" name="username" class="form-control" <?php if($username != null){echo 'value="'.$username.'"';}else{echo 'placeholder="Username"';}?> />
              </div>
            <?php endif ?> 

            <?php if(array_key_exists('password', $errors)):?>            
              <input type="password" id="password" name="password" class="form-control is-invalid" placeholder="Password" required>
              <div class="invalid-feedback">
                <?= $errors['password']; ?>
              </div>  
            <?php else: ?> 
              <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
              </div>
            <?php endif ?>
            
            <button type="submit" class="btn btn-primary btn-dark btn-block">login</button>
          </form>
        </div>
      </div>
    </main>          
  </body>
</html>
