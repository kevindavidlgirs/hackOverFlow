
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
<link href="../css/bootstrap.min.css" rel="stylesheet">


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
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <a class="navbar-brand" href="#">Hack OverFlow</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="post/index">Question <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user/login">login</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="user/signup">SignUp<span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>
<!-- MAIN -->
<main role="main" class="container">
      <form action="user/signup" method="POST">
        <label>UserName : <input name="username" type="text" size="64"/></label>
        <label>Password : <input name="password" type="password" size="64"/></label>
        <label>Confirm password : <input name="password_confirm" type="password" size="64"/></label>
        <label>Full name : <input name="fullname" type="text" size="64"/></label>
        <label>E-Mail : <input name="email" type="text" size="64"/></label>
        <input type="submit" value="Sign up"/>
      </form>
      <?php if(count($errors)!=0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
      <?php endif; ?>
</main>
</boby>
</html>
