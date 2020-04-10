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

    //Découpe nécessaire...
    public function add(){
        $user = $this->get_user_or_redirect();
        $error = [];
        if(isset($_GET['param1']) && !isset($_POST['body'])){
            $postId = $_GET['param1'];
            $question = Question::get_question($postId);
            if($question){
                (new View("addComment"))->show(array("post"=>$question, "user" => $user, "error" => $error));
            }else{
                $this->redirect();
            }
        }elseif(isset($_GET['param1']) && isset($_POST['body'])){
            $postId = $_GET['param1'];
            $body = $_POST['body'];
            $question = Question::get_question($postId);
            if($question){
                $comment = new Comment(null, $user->getUserId(), null, $question->getPostId(), $body, null);
                $error = Comment::validate($comment);
                if(count($error) == 0){
                    $comment->create();
                    self::redirect("post", "show", $postId);
                }else{
                    (new View("addComment"))->show(array("post"=>$question, "user" => $user, "error" => $error));
                }
            }else{
                $this->redirect();
            }       
        }else{
            $this->redirect();
        }
    }

    public function edit(){
        $user = $this->get_user_or_redirect();
        $error = [];
        if(isset($_GET['param1']) && isset($_GET['param2']) && !isset($_POST['body'])){
            $commentId = $_GET['param1'];
            $postId = $_GET['param2'];
            $comment = Comment::get_comment($commentId);
            $question = Question::get_question($postId);
            if($comment && $question && $comment->getPostId() === $question->getPostId()
                        && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                (new View("editComment"))->show(array("post"=>$question, "comment" => $comment, "user" => $user, "error" => $error));    
            }else{
                self::redirect();
            }
        }elseif(isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['body'])){
            $commentId = $_GET['param1'];
            $postId = $_GET['param2'];
            $body = $_POST['body'];
            $comment = Comment::get_comment($commentId);
            $question = Question::get_question($postId);
            if($comment && $question && $comment->getPostId() === $question->getPostId()
                        && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                $comment = new Comment($comment->getCommentId(), null, null, null, $body, null);
                $error = Comment::validate($comment);
                if(count($error) == 0){
                    $comment->update();
                    self::redirect("post", "show", $postId);
                }else{
                    (new View("editComment"))->show(array("post"=>$question, "comment" => $comment, "user" => $user, "error" => $error));    
                }
            }
        }else{
            self::redirect();
        }
    }

    public function delete(){
        $user = $this->get_user_or_redirect();
        $error = [];
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $commentId = $_GET['param1'];
            $postId = $_GET['param2'];
            $comment = Comment::get_comment($commentId);
            $question = Question::get_question($postId);
            if(isset($_POST['cancel'])){
                self::redirect("post", "show", $postId);
            }elseif(isset($_POST['delete_confirmation'])){
                if($comment->delete()){
                    self::redirect("post", "show", $postId);
                }
            }else{
                if($question && $comment && ($user->isAdmin() || $user->getUserId() === $comment->getAuthorId())){
                    (new View("deleteComment"))->show(array("postId" => $postId, "comment" => $comment, "user" => $user));        
                }else{
                    $this->redirect();
                }
            }
        }else{
            $this->redirect();
        }
    }
}