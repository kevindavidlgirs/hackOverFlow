<?php

require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

//A voir s'il ne faut pas ajouter un controller Answer
class ControllerPost extends Controller{
//si l'utilisateur est conecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() {
        (new View("index"))->show(array("posts"=> Post::get_posts()));
    }

    public function ask(){
        if($this->user_logged() && !isset($_POST['title']) && !isset($_POST['body'])){
            (new View("ask"))->show();
        }else if($this->user_logged() && isset($_POST['title']) && isset($_POST['body'])){
            $post = new Post(null, $_SESSION['user']->getUserId(), $_POST['title'], $_POST['body'], null, null, null, null, null, null, null);
            $post->create_post();   
            $this->redirect();
        }else{
            $this->redirect();
        }
         
    }

    public function show(){
        if(isset($_GET['param1'])){       
            (new View("show"))->show(array("post"=> Post::get_post($_GET['param1'])));
        }else{
            $this->redirect();
        }        
    }

    public function like(){
        if(isset($_GET['param1']) && isset($_GET['param2']) && isset($_GET['param3'])){
            $value =  $_GET['param1'];
            $postId = $_GET['param2'];
            $AnswerId = $_GET['param3'];
            $userId = $_SESSION['user']->getUserId();
            echo $value;
            Vote::update_vote($userId, $AnswerId, $value);    
        }else if(isset($_GET['param1']) && isset($_GET['param2'])){
            $value =  $_GET['param1'];
            $postId = $_GET['param2'];
            $userId = $_SESSION['user']->getUserId();
            echo $value;
            Vote::update_vote($userId, $postId, $value);    
        }
        $this->redirect("post", "show", $postId);

    }
}

?>