<?php

require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

//A voir s'il ne faut pas ajouter un controller Answer
class ControllerPost extends Controller{
    
    /*
    *si l'utilisateur est conecté, redirige vers son profil.
    *sinon, produit la vue d'accueil.
    */
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

    //sert à afficher la view_show (attention peut encore afficher un show inexistant !)
    public function show(){
        if(isset($_GET['param1'])){       
            (new View("show"))->show(array("post"=> Post::get_post($_GET['param1'])));
        }else{
            $this->redirect();
        }        
    }

    /*
    *Permet la gestion des boutons like et dislike
    *Si deux paramètres sont passés cela veut dire que c'est la gestion des boutons pour des réponses
    *Si il n'y a qu'un seul paramètre cela veut dire que c'est pour une question
    */
    public function like(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_GET['param3'])){
            $value =  $_GET['param1'];
            $postId = $_GET['param2'];
            $AnswerId = $_GET['param3'];
            $userId = $_SESSION['user']->getUserId();
            Vote::update_vote($userId, $AnswerId, $value); 
            $this->redirect("post", "show", $postId);   
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2'])){
            $value =  $_GET['param1'];
            $postId = $_GET['param2'];
            $userId = $_SESSION['user']->getUserId();
            Vote::update_vote($userId, $postId, $value); 
            $this->redirect("post", "show", $postId);   
        }
        $this->redirect();
        
    }

    //Gère l'édition d'une post ou d'une réponse (function à découper)
    public function edit(){
        if($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && !isset($_POST['body'])){
            $postId = $_GET['param1'];
            (new View("edit"))->show(array("post" => Post::get_post($postId)));
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && !isset($_POST['body'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            (new View("edit"))->show(array("parentId"=>$postId, "answerId" => $answerId, "post" => Answer::get_answer($answerId)));     
        }else if($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['body'])){
            $postId = $_GET['param1'];
            if(Post::edit_post($postId, $_POST['body'])){
                $this->redirect("post", "show", $postId);       
            }
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['body'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(Answer::edit_answer($answerId, $_POST['body'])){
                $this->redirect("post", "show", $postId);       
            }
        }else{
            $this->redirect();
        }

    }

    //Gère la suppression d'un post ou d'une question
    public function delete(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_POST['cancel'])){
            $postId = $_GET['param1'];
            $this->redirect("post", "show", $postId);     
        }
        else if($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['delete'])){
            $postId = $_GET['param1'];
            if(self::get_user_or_false()->getUserId() === Post::get_post($postId)->getAuthorId()){
                (new View("delete"))->show(array("postId"=> $postId));
            }    
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['delete'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(self::get_user_or_false()->getUserId() === Post::get_post($answerId)->getAuthorId()){
                (new View("delete"))->show(array("postId"=> $postId, "answerId"=> $answerId));
            }
        }else if ($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['delete_confirmation'])){
            $postId = $_GET['param1'];
            if(Post::delete($postId)){
                $this->redirect();    
            }
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['delete_confirmation'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(Post::delete($answerId)){
                $this->redirect("post", "show", $postId);    
            }
        }
    }

    //Ajoute une réponse à un post 
    public function answer(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_POST['answer'])){
            $parentId = $_GET['param1'];
            $answer = $_POST['answer'];
            $userId = $_SESSION['user']->getUserId();
            if(Answer::add_answer($userId, $parentId, $answer)){
                $this->redirect("post", "show", $parentId);
            }
        }else{
            $this->redirect();
        }    
    }

    public function accept_question(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(Post::accept_question($postId, $answerId)){
                $this->redirect("post", "show", $postId);    
            }
        }   
    }

    
}

?>