<?php

require_once 'model/Comment.php';
require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Utils.php';


class ControllerComment extends Controller{

    public function index(){
        $this->redirect();
    }

    //Découpe NECESSAIRE ET AMELIORATION!!!!!! (réduire aussi en utilisant le parentId au lieu de param3 pour un URL propre)
    public function add(){
        $user = $this->get_user_or_redirect();
        $error = [];
        if(isset($_GET['param1']) && !isset($_POST['body'])){
            if(isset($_GET['param2'])){
                $post = Answer::get_answer($_GET['param1']);
                $question = Question::get_question($_GET['param2']);
                if($post && $question){
                    (new View("addComment"))->show(array("post"=>$post, "user" => $user, "error" => $error));
                }else{
                    $this->redirect();
                }     
            }else{
                $post = Question::get_question($_GET['param1']);
                if($post){
                    (new View("addComment"))->show(array("post"=>$post, "user" => $user, "error" => $error));
                }else{
                    $this->redirect();
                }    
            }
        }elseif(isset($_GET['param1']) && isset($_POST['body'])){
            $body = $_POST['body'];
            if(isset($_GET['param2'])){
                $post = Answer::get_answer($_GET['param1']);
                $question = Question::get_question($_GET['param2']);
                if(!$question){
                    $this::redirect();
                }
            }else{
                $post = Question::get_question($_GET['param1']);
            }
            if($post){
                $comment = new Comment(null, $user->getUserId(), null, $post->getPostId(), $body, null);
                $error = Comment::validate($comment);
                if(count($error) == 0){
                    $comment->create();
                    if($question){
                        self::redirect("post", "show", $question->getPostId());
                    }else{
                        self::redirect("post", "show", $post->getPostId());
                    }
                }else{
                    (new View("addComment"))->show(array("post"=>$post, "user" => $user, "error" => $error));
                }
            }else{
                $this->redirect();
            }       
        }else{
            $this->redirect();
        }
    }

    //Découpe NECESSAIRE ET AMELIORATION!!!!!! (réduire aussi en utilisant le parentId au lieu de param3 pour un URL propre)
    public function edit(){
        $user = $this->get_user_or_redirect();
        $error = [];
        $question = null;
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $comment = Comment::get_comment($_GET['param1']);
            if(isset($_GET['param3'])){
                $post = Answer::get_answer($_GET['param2']);
                $question = Question::get_question($_GET['param3']);
                if(!$question){
                    $this::redirect();
                }
            }else{
                $post = Question::get_question($_GET['param2']);
            }
            if(!isset($_POST['body']) && $comment && $post && $comment->getPostId() === $post->getPostId()
                        && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                (new View("editComment"))->show(array("post"=> $post, "comment" => $comment, "question" => $question, "user" => $user, "error" => $error));    
            }elseif(isset($_POST['body']) && $comment && $post && $comment->getPostId() === $post->getPostId()
                        && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                $body = $_POST['body'];
                $comment = new Comment($comment->getCommentId(), null, null, null, $body, null);
                $error = Comment::validate($comment);
                if(count($error) == 0){
                    $comment->update();
                    if($question){
                        self::redirect("post", "show", $question->getPostId());
                    }else{
                        self::redirect("post", "show", $post->getPostId());                        
                    }
                }else{
                    (new View("editComment"))->show(array("post"=> $post, "comment" => $comment, "question" => $question,"user" => $user, "error" => $error));    
                }
            }else{
                $this::redirect();
            }
        }else{
            self::redirect();
        }
    }

    //Découpe NECESSAIRE ET AMELIORATION!!!!!! (réduire aussi en utilisant le parentId au lieu de param3 pour un URL propre)
    public function delete(){
        $user = $this->get_user_or_redirect();
        $error = [];
        $question = null;
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $comment = Comment::get_comment($_GET['param1']);
            if(isset($_GET['param3'])){
                $post = Answer::get_answer($_GET['param2']);
                $question = Question::get_question($_GET['param3']);
                if(!$question){
                    $this::redirect();
                }
            }else{
                $post = Question::get_question($_GET['param2']);
            }
            if(isset($_POST['cancel'])){
                if($question){
                    self::redirect("post", "show", $question->getPostId());
                }else{
                    self::redirect("post", "show", $post->getPostId());
                }
            }elseif(isset($_POST['delete_confirmation'])){
                if($comment->delete()){
                    if($question){
                        self::redirect("post", "show", $question->getPostId());
                    }else{
                        self::redirect("post", "show", $post->getPostId());
                    }
                } 
            }else{
                if($post && $comment && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                    (new View("deleteComment"))->show(array("post" => $post, "question" => $question, "comment" => $comment, "user" => $user));        
                }else{
                    $this->redirect();
                }
            }
        }else{
            $this->redirect();
        }
    }
}