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
    <!-- Bootstrap core CSS + fontawesome -->    

    <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script>
      let questions;
      let navigation;
      let question_list;

      $(function(){
        
        navigation = $("#navigation");
        question_list = $("#question_list");
        getQuestions();
        displayNavigation();
        displayList();
        $('#newest').on('click', function (e) {
          e.preventDefault()
          $('#newest').tab('show')
        })
      });
      
      function getQuestions(){
        $.get("post/get_questions_service/", function(data){
          questions = data;
          console.log(data);
        },"json").fail(function(){
          console.log("fail json !");
        });
      }

      function displayNavigation(){
        let html = "<ul class=\"nav nav-tabs card-header-tabs row\">" +
                   "<li id=\"newest\" class=\"nav-item\">" +
                   "<a class=\"nav-link\" href=\"post\">Newest</a>" +
                   "</li>" +
                   "<li class=\"nav-item\">" +
                   "<a class=\"nav-link\" href=\"post/active\">Active</a>"+
                   "</li>"+
                   "<li class=\"nav-item\">"+
                   "<a class=\"nav-link\" href=\"post/unanswered\">Unanswered</a>"+
                   "</li>"+
                   "<li class=\"nav-item\">"+
                   "<a class=\"nav-link\" href=\"post/votes\">Votes</a>"+
                   "</li>"+
                   "<li class=\"nav-item\">"+
                   "<form action=\"\" method=\"post\">"+
                   "<input class=\"form-control\" type=\"search\" name=\"search\" placeholder=\"Search...\" aria-label=\"Search\">"+
                   "</form>"+
                   "</li>"+
                   "</ul>";
        navigation.html(html);
      }

      function displayList(){
        let html = "<ul class=\"list-group list-group-flush\">";
        for (let question of questions){
          html += "<li class=\"list-group-item\">";
          html += "<a href=post/show/"+question["postId"]+">"+question["title"]+"</a><br>"; 
          html += ""+question['body']+"\"<br>\"";
          hmtl += "<small>Asked \"+Utils::time_elapsed_string("+question['timestamp']+")+\" by <a href='user/profile/\""+question['authorId']+"\"'>\""+question['fullname']+"\"</a></small>\""; 
          html += "<small> (\""+question['totalVote']+"\" vote(s), \"";   
          html += ""+question['nbAnswers']+"\" answer(s))</small>\"";
          for($i = 0 ; $i < sizeof(question["tags"]); $i++){
            html += "<a type=\"button\" class=\"btn button\" href=\"post/tags/'"+question['tags'][$i]['tagId']+"'/1\">'"+question['tags'][$i]['tagName']+"'</a>";
          }
          html += "</li>";
        }
        html += "</ul>";
        question_list.html(html);
      }

    
    </script>
  </head>
  <body>
    <?php
      $active = 'question';
      include('header.html');   
    ?>
    <!-- MAIN -->
    <main role="main" class="container">
      <div id="card" class="card">

        <!-- navigation -->
        <div id="navigation" class="card-header">
          <ul class="nav nav-tabs card-header-tabs row">
            <li class="nav-item">
              <a class="nav-link <?php if($filter == 'newest')echo 'active'?>" href="post">Newest</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  <?php if($filter == 'active')echo 'active'?>" href="post/active">Active</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  <?php if($filter == 'unanswered')echo 'active'?>" href="post/unanswered">Unanswered</a>
            </li>
            <li class="nav-item">
              <a class="nav-link  <?php if($filter == 'votes')echo 'active'?>" href="post/votes">Votes</a>
            </li>
            <?php if($filter == 'Question tagged'):?>
              <li class="nav-item">
                <a class="nav-link  <?php echo 'active'?>">Question tagged [<?=$tag->getTagName()?>]</a>
              </li>
            <?php endif ?>
            <li class="nav-item">
              <form action="post/<?php if($filter == 'Question tagged'){echo 'tags/'.$tag->getTagId();}elseif($filter == 'newest'){echo "index";}else{echo $filter;}  ?>/<?= $page ?>" method="post">
                <input class="form-control" type="search" name="search" placeholder="Search..." aria-label="Search">
              </form>
            </li>
          </ul>
        </div>
        <!-- Navigation -->
      
        <!-- list questions -->
        <div id="question_list" class="card-body">
          <ul class="list-group list-group-flush"> 
            <?php foreach($posts as $post): ?>
              <li class="list-group-item">
                <?php
                  echo "<a href=post/show/".$post->getPostId().">".$post->getTitle()."</a><br>"; 
                  echo $post->getBodyMarkedownRemoved()."<br>";
                  echo "<small>Asked ".Utils::time_elapsed_string($post->getTimestamp())." by <a href='user/profile/".$post->getAuthorId()."'>".$post->getFullNameAuthor()."</a></small>"; 
                  echo "<small> (".$post->getTotalVote()." vote(s), ";   
                  echo $post->getNbAnswers() ." answer(s))</small>";
                  foreach($post->getTags() as $tagOfPost){
                    echo '<a type="button" class="btn button" href="post/tags/'.$tagOfPost->getTagId().'/1">'.$tagOfPost->getTagName().'</a>';
                  }
                ?>    
              </li>
              
            <?php endforeach ?>
          </ul>
        </div>
        <!-- list questions -->

        <!-- pagination -->
        <navs>
          <ul id="pagination" class="pagination justify-content-end">
            <?php if($page > 1): ?>
              <li class="page-item">
                <a class="page-link" style="border-color:white; color:#686868	;" href="post/<?php if($filter == 'Question tagged'){echo 'tags/'.$tag->getTagId();}elseif($filter == 'newest'){echo 'index';}else{echo $filter;} ?>/<?=$page-1?>/<?= $search_enc ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>
            <?php endif ?>
            <?php if($nb_pages != 1): ?>
              <?php for($i = 1; $i <= $nb_pages; ++$i): ?>
                <li class="page-item <?= ($i == $page) ? "active" : "" ?>"><a <?= ($i == $page) ? "style='background-color: #323232; border-color:white; color:white;'" : "style='background-color: #e5e5e5; border-color:white; color:white;'" ?> class="page-link" href="post/<?php if($filter == 'Question tagged'){echo 'tags/'.$tag->getTagId();}elseif($filter == 'newest'){echo "index";}else{echo $filter;} ?>/<?=$i?>/<?= $search_enc ?>"><?= $i ?></a></li>
              <?php endfor; ?>
            <?php endif ?>
            <?php if($page < $nb_pages): ?>
            <li class="page-item">
              <a class="page-link" style="border-color:white; color:#686868	;" href="post/<?php if($filter == 'Question tagged'){echo 'tags/'.$tag->getTagId();}elseif($filter == 'newest'){echo 'index';}else{echo $filter;} ?>/<?=$page+1?>/<?= $search_enc ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
            <?php endif ?>
          </ul>
        </nav>
        <!-- pagination -->

      </div>
    </main>
  </body>
</html>
