<?php

require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

//A voir s'il ne faut pas ajouter un controller Answer
class ControllerPost extends Controller{
    
    /*
    *si l'utilisateur est conecté, redirige vers son profil.
    *sinon, produit la vue d'accueil.
    */
    public function index() {
        (new View("index"))->show(array("posts"=> Question::get_questions()));
    }

    public function ask(){
        $title = '';
        $body = '';
        $errors = [];
        if ($this->user_logged() && isset($_POST['title']) && isset($_POST['body'])){
            $title = $_POST['title'];
            $body = $_POST['body'];
            $question = new Question(null, $_SESSION['user']->getUserId(), $title , $body, null, null, null, null, null, null, null);
            $errors = Question::valid_question($question);
            if(count($errors) == 0){
                $question->create_question();   
                $this->redirect();
            }
        }
        (new View("ask"))->show(array("title"=>$title, "body"=> $body, "errors" => $errors)); 
    }

    //sert à afficher la view_show (attention peut encore afficher un show inexistant !)
    public function show(){
        if(isset($_GET['param1'])){       
            $postId = $_GET['param1'];
            $result = Question::get_question($postId);
            if($result){
                (new View("show"))->show(array("post"=> $result));
            }else{
                $this->redirect();
            }
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

    //Gère l'édition d'une post ou d'une réponse
    public function edit(){
        $questionId = '';
        $answerId = '';
        $error = [];
        if(isset($_GET['param1']) && !isset($_GET['param2']) && !isset($_POST['body'])){
            $questionId = $_GET['param1']; 
            $this->view_edition_question($questionId, $answerId, $error);
        }else if(isset($_GET['param1']) && isset($_GET['param2']) && !isset($_POST['body'])){
            $questionId = $_GET['param1'];
            $answerId = $_GET['param2'];
            $this->view_edition_answer($questionId, $answerId, $error);
        }else{
            $this->edition_post();
        }
    }

    //Affiche la vue pour l'édition d'une réponse
    private function view_edition_answer($questionId, $answerId, $error){
        if (Question::get_question($questionId) &&  Answer::get_answer($answerId)){
            if(!is_numeric($questionId) || !is_numeric($answerId) 
                                        || !($this->get_user_or_false()->getUserId() === Answer::get_answer($answerId)->getAuthorId())
                                        || !($this->get_user_or_false()->getUserId() === Question::get_question($questionId)->getAuthorId())){
                $this->redirect();        
            }
        }else{
            $this->redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, "post" => Answer::get_answer($answerId), 'error' => $error));         
    }

    //Affiche la vue pour l'edition d'une question
    private function view_edition_question($questionId, $answerId, $error){
        if(!is_numeric($questionId)){
            $this->redirect();
        }
        if (Question::get_question($questionId)){
            if(!($this->get_user_or_false()->getUserId() === Question::get_question($questionId)->getAuthorId())){
                $this->redirect();     
            }
        }else{
            $this->redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, "post" => Question::get_question($questionId), 'error' => $error));         
    }

    //Sert à ajouter la modification du corps d'une réponse ou d'une question (à modifier).
    private function edition_post(){
        if ($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['body'])){
            $questionId = $_GET['param1'];
            $body = $_POST['body'];
            $post = new Question($questionId, null, null, $body, null, null, null, null, null, null, null);
            $error = Question::valid_edition($body);
            if(count($error) == 0){
                $post->edit_post();
                $this->redirect("post", "show", $questionId);       
            }
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['body'])){
            $questionId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(Answer::edit_answer($answerId, $_POST['body'])){
                $this->redirect("post", "show", $questionId);       
            }
        }else{
            $this->redirect();
        }
    }
    
    //Gère la suppression d'un post ou d'une question (pas juste)
    public function delete(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_POST['cancel'])){
            $postId = $_GET['param1'];
            $this->redirect("post", "show", $postId);     
        }
        else if($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['delete'])){
            $questionId = $_GET['param1'];
            if(self::get_user_or_false()->getUserId() === Question::get_question($questionId)->getAuthorId()){
                (new View("delete"))->show(array("postId"=> $questionId));
            }    
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['delete'])){
            $questionId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(self::get_user_or_false()->getUserId() === Question::get_question($answerId)->getAuthorId()){
                (new View("delete"))->show(array("postId"=> $questionId, "answerId"=> $answerId));
            }
        }else if ($this->user_logged() && isset($_GET['param1']) && !isset($_GET['param2']) && isset($_POST['delete_confirmation'])){
            $questionId = $_GET['param1'];
            $question = new Question($questionId, null, null, null, null, null, null, null, null, null, null);
            if($question->delete($questionId)){
                $this->redirect();    
            }
        }else if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2']) && isset($_POST['delete_confirmation'])){
            $questionId = $_GET['param1'];
            $answerId = $_GET['param2'];
            $answer = new Answer($answerId, null, $questionId, null, null, null, null);
            if($answer->delete($questionId, $answerId)){
                $this->redirect("post", "show", $questionId);    
            }
        }else{
            $this->redirect();
        }
    }

    //Ajoute une réponse à un post pas juste !
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

    //pas juste !
    public function accept_answer(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(Question::accept_answer($postId, $answerId)){
                $this->redirect("post", "show", $postId);    
            }
        }   
    }

    // +/- mais pas juste !
    public function delete_accepted_answer(){
        if(isset($_GET['param1']) && isset($_POST['delete_acceptation'])){
            $postId = $_GET['param1'];
            if($this->get_user_or_false()->getUserId() === Question::get_question($postId)->getAuthorId()){
                if(Question::delete_accepted_answer($postId)){
                    $this->redirect("post", "show", $postId);
                }
            }
        }else{
            $this->redirect();
        }   
    }

    
}

?>