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

    <!-- Javascript -->
    <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script>
      //Type à réctifier.
      var navigation;
      var question_list;
      var pagination;
      var tagId = null;
      var tagName = null;
      var typeList = 'newest';
      var searchValue = null;
      var questions;
      var page;
      var nb_pages = 0;

      $(function(){
        navigation = $("#navigation");
        question_list = $("#question_list");
        pagination = $("#pagination");
        displayAll(typeList);
      });

      function configActions(){
        $("#inputSearch").focus();
        $('#tags a').on('click', function (e) {
          displayAll("tags", $(this).attr("id"), $(this).attr("name"));
        });

        $("#inputSearch").on("input", function(){
          searchValue = $(this).val();
          typeList = "search";
          page = 1;
          displayNavigation();
          displayQuestionsSearch();
          $("#inputSearch").val(searchValue);
          $("#inputSearch").focus();
        });
      }

//Rassembler ces deux fonctions
      function displayAll(tpList, tgId = null, tgName = null){
        page = 1;
        typeList = tpList;
        tagId = tgId;
        tagName = tgName;
        $.get("post/get_questions_service/"+typeList+"/"+page+"/"+(tagId != null ? tagId : "")+"/", function(data){
          questions = data;
          nb_pages = questions['1']['pages']['0']['nb_pages'];
          displayNavigation();
          displayAllQuestions();
          displayPagination();
          configActions();
        },"json").fail(function(){
          console.log("fail json !");
        });
      }

      function displayPageSelected(){
        $.get("post/get_questions_service/"+typeList+"/"+page+"/"+(tagId != null ? tagId : "")+"/", function(data){
          questions = data;
          nb_pages = questions['1']['pages']['0']['nb_pages'];
          displayAllQuestions();
          displayPagination();
          configActions();
        },"json").fail(function(){
          console.log("fail json !");
        });
      }
//Rassembler ces deux fonctions

      function displayQuestionsSearch(){
        $.post("post/get_questions_service/newest/"+page+"/"+(tagId != null ? tagId : "")+"/", {search : searchValue}, function(data){
          //POURQUOI DES SAUTS A LA LIGNES ALEATOIRES ?! :^O
          questions = JSON.parse(data.replace(/\r?\n|\r/g, ''));
          //POURQUOI DES SAUTS A LA LIGNES ALEATOIRES ?! :^O
          if(Object.entries(questions).length !== 0){
            nb_pages = questions['1']['pages']['0']['nb_pages'];
          }else{
            nb_pages = 0;
          }
          displayAllQuestions();
          displayPagination();
          configActions();
        });
      }

      function displayNavigation(){
        let html = "<li class=\"nav-item\" id=\"newest\">" +
                   "<a id=\"newest\" class='nav-link "+(typeList == 'newest'? "active" : "" )+"' href=\"javascript:displayAll('newest');\";>Newest</a>" +
                   "</li>" +
                   "<li class=\"nav-item\">" +
                   "<a id=\"active\" class='nav-link "+(typeList == 'active'? "active" : "" )+"' href=\"javascript:displayAll('active');\">Active</a>"+
                   "</li>"+
                   "<li class=\"nav-item\">"+
                   "<a id=\"unanswered\" class='nav-link "+(typeList == 'unanswered'? "active" : "" )+"' href=\"javascript:displayAll('unanswered');\">Unanswered</a>"+
                   "</li>"+
                   "<li class=\"nav-item\">"+
                   "<a id=\"votes\" class='nav-link "+(typeList == 'votes'? "active" : "" )+"' href=\"javascript:displayAll('votes');\">Votes</a>"+
                   "</li>"+
                   "<li id=\"tags\" class=\"nav-item\"><a class='nav-link active' "+((tagId == null || tagId == "") || typeList !== "tags" ? "style=\"display:none;\"" : "")+">Question tagged ["+tagName+"]</a></li>"+
                   "<li class=\"nav-item\">"+
                   "</li>"+
                   "<li id=\"tags\" class=\"nav-item\"><a class='nav-link active' "+(typeList !== 'search' ? "style=\"display:none;\"" : "")+">Search Results</a></li>"+
                   "<li class=\"nav-item\">"+
                   "<form >"+
                   "<input id=\"inputSearch\" class=\"form-control\" type=\"search\" placeholder=\"Search...\" name=\"search\" aria-label=\"Search\" >"+
                   "</form>"+
                   "</li>"; 
        navigation.html(html);
      }

      function displayAllQuestions(){
        let html = "";
        if(Object.entries(questions).length !== 0){
          for (let question of questions['0']['questions']){
            html += "<li class=\"list-group-item\">";
            html += "<a href=post/show/"+question["postId"]+">"+question["title"]+"</a><br>"; 
            html += question['body']+"<br>";
            html += "<small>Asked "+question['timestamp']+" by <a href='user/profile/"+question["authorId"]+"'>"+question["fullName"]+"</a></small>"; 
            html += "<small> ("+question['totalVote']+" vote(s), ";   
            html += ""+question['nbAnswers']+" answer(s))</small><span id=\"tags\">";
            for(let tag of question['tags']){
              html += "<a id='"+tag["tagId"]+"' name='"+tag["tagName"]+"' type=\"button\" class=\"btn button\">"+tag["tagName"]+"</a>";
            }
            html += "</span></li>";
          }
        }
        question_list.html(html);
      }
        

      function displayPagination(){
        let html = "<ul class=\"pagination justify-content-end\">";
        if(page > 1){
          html += "<li class=\"page-item\">"+
                  "<a type=\"button\" class=\"page-link\" style=\"border-color:white; color:#686868	;\"  aria-label=\"Previous\" onclick=\"incrementNbPages('Previous');"+(typeList == 'search'? "displayQuestionsSearch();" : "displayPageSelected();")+"\">"+
                  "<span aria-hidden=\"true\">&laquo;</span>"+
                  "<span class=\"sr-only\">Previous</span>"+
                  "</a>"+
                  "</li>";
          }
        if(nb_pages != 1){
          for(var i = 1; i <= nb_pages; ++i)
            html += "<li class=\"page-item"+(i == page ? ' active' : "" )+"\"><a type=\"button\""+ (i == page ? " style='background-color: #323232; border-color:white; color:white;'" : " style='background-color: #e5e5e5; border-color:white; color:white;'")+" class=\"page-link\" onclick=\"choosePage("+i+");"+(typeList == 'search'? "displayQuestionsSearch();" : "displayPageSelected();")+"\">"+i+"</a></li>";
        }
        if(page < nb_pages){
          html += "<li class=\"page-item\">"+
                  "<a type=\"button\" class=\"page-link\" style=\"border-color:white; color:#686868	;\" aria-label=\"Next\" onclick=\"incrementNbPages('Next'); displayPageSelected();"+(typeList == 'search'? "displayQuestionsSearch();" : "displayPageSelected();")+"\">"+
                  "<span aria-hidden=\"true\">&raquo;</span>"+
                  "<span class=\"sr-only\">Next</span>"+
                  "</a>"+
                  "</li>";
        }     
        html += "</ul>";              
        pagination.html(html);
      }

      function choosePage(pge){
        page = pge;
      }

      function incrementNbPages(side){
        if(side == 'Previous')
          --page;
        else
          ++page;
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
        <div class="card-header">
          <ul id="navigation" class="nav nav-tabs card-header-tabs row">
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
      
        <!-- list questions -->
        <div class="card-body">
          <ul id="question_list" class="list-group list-group-flush"> 
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
        <navs id="pagination">
          <ul class="pagination justify-content-end">
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
