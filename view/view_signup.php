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
      
      $.validator.addMethod("regex", function (value, element, pattern) {
        if (pattern instanceof Array) {
          for(p of pattern) {
            if (!p.test(value))
              return false;
          }
          return true;
        } else {
          return pattern.test(value);
        }
      }, "Password must contain one uppercase letter, one number and one punctuation mark.");
      
      $(function(){
        $('#signupForm').validate({
          rules: {
            username: {
              remote: {
                url: 'user/username_signup_service',
                type: 'post',
                data:  {
                  username: function() { 
                    return $("#username").val();
                  }
                }
              },
              required: true,
              minlength: 3,
              maxlength: 16,
              regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
            },
            fullname: {
              required: true,
              minlength: 3,
              maxlength: 16,
              regex: /^[a-zA-Z ]*$/,
            },
            email: {
              remote: {
                url: 'user/email_signup_service',
                type: 'post',
                data:  {
                  email: function() { 
                    return $("#email").val();
                  }
                }
              },
              required: true,
              regex: /^[a-z0-9-._]+\@[a-z0-9-._]{2,}\.[a-z]{2,4}$/,
            },
            password: {
              required: true,
              minlength: 8,
              maxlength: 30,
              regex: [/[A-Z]/, /\d/, /['\";!:,.=+%£µ$)}{\/?\\-]/],
            },
            password_confirm: {
              required: true,
              minlength: 8,
              maxlength: 30,
              equalTo: "#password",
              regex: [/[A-Z]/, /\d/, /['\";!:,.=+%£µ$)}{\/?\\-]/],
            }
          },
          messages: {
            username: {
              remote: 'This username already exists',
              required: 'username is required.',
              minlength: 'minimum 3 characters',
              maxlength: 'maximum 16 characters',
              regex: 'username must start by a letter and must contain only letters and numbers.',
            },
            fullname: {
              required: 'fullname is required.',
              minlength: 'minimum 3 characters',
              maxlength: 'maximum 16 characters',
              regex: 'fullname contain only letters.',
            },
            email: {
              remote: 'This email already exists.',
              required: 'email is required.',
              regex: 'invalid email.',
            },
            password: {
              required: 'required',
              minlength: 'minimum 8 characters',
              maxlength: 'maximum 30 characters',
              regex: 'bad password format',
            },
            password_confirm: {
              required: 'required',
              minlength: 'minimum 8 characters',
              maxlength: 'maximum 30 characters',
              equalTo: 'You have to enter twice the same password.',
              regex: 'Password must contain one uppercase letter, one number and one punctuation mark.',
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

        $("input:text:first").focus();

      });
    </script>
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
          <form id="signupForm" action="user/signup" method="post">
            <?php if(array_key_exists('user', $errors)):?>            
              <input type="text" id="username" name="username" class="form-control is-invalid" placeholder="<?= $username ?>">
                <div class="invalid-feedback">
                  <?= $errors['user']; ?>
                </div>  
            <?php else: ?>   
              <div class="form-group">
                <input type="text" id="username" name="username" class="form-control" <?php if($username != null){echo 'value="'.$username.'"';}else{echo 'placeholder="Username"';}?>/>
              </div>
            <?php endif ?>
            
            <?php if(array_key_exists('name', $errors)):?>            
              <input type="text" id="fullname" name="fullname" class="form-control is-invalid" placeholder="<?= $fullname ?>">
                <div class="invalid-feedback">
                  <?= $errors['name']; ?>
                </div>  
            <?php else: ?> 
              <div class="form-group">
                <input type="text" id="fullname" name="fullname" class="form-control" <?php if($fullname != null){echo 'value="'.$fullname.'"';}else{echo 'placeholder="Full Name"';}?>/>
              </div>
            <?php endif ?>

            <?php if(array_key_exists('email', $errors)):?>            
              <div class="form-group">
                <input type="text" id="email" name="email" class="form-control is-invalid" placeholder="<?= $email ?>">
                <div class="invalid-feedback">
                  <?= $errors['email']; ?>
                </div>  
              </div> 
            <?php else: ?> 
              <div class="form-group">
                <input type="text" id="email" name="email" class="form-control" <?php if($email != null){echo 'value="'.$email.'"';}else{echo 'placeholder="name@example.com"';}?> />
              </div>
            <?php endif ?>

            <?php if(array_key_exists('password', $errors)):?>  
              <div class="form-group">          
                <input type="password" id="password" name="password" class="form-control is-invalid" placeholder="Password">
                  <div class="invalid-feedback">
                    <?= $errors['password']; ?>
                  </div>  
              </div>
            <?php else: ?> 
              <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
              </div>
            <?php endif ?>

            <?php if(array_key_exists('password_confirm', $errors)):?> 
              <div class="form-group">           
                <input type="password" id="password_confirm" name="password_confirm" class="form-control is-invalid" placeholder="Confirm your password">
                  <div class="invalid-feedback">
                    <?= $errors['password_confirm']; ?>
                  </div> 
              <div class="form-group"> 
            <?php else: ?> 
              <div class="form-group">
                <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Confirm your password"/>
              </div>
            <?php endif ?>
            
            <button type="submit" class="btn btn-primary btn-dark btn-block">Sign up</button>
          </form>
        </div>
      </div>
    </main>
  </body>
</html>
