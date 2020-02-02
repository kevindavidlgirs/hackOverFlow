<?php

require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

//A voir s'il ne faut pas ajouter un controller Answer
class ControllerPost extends Controller{
    
    public function index() {
        (new View("index"))->show(array("posts"=> Question::get_questions()));
    }

    public function ask(){
        $title = '';
        $body = '';
        $errors = [];
        if (self::user_logged() && isset($_POST['title']) && isset($_POST['body'])){
            $title = $_POST['title'];
            $body = $_POST['body'];
            $question = new Question(null, $_SESSION['user']->getUserId(), $title , $body, null, null, null, null, null, null, null);
            $errors = Question::validate($question);
            if(count($errors) == 0){
                $question->create_question();   
                self::redirect();
            }
        }
        (new View("ask"))->show(array("title"=>$title, "body"=> $body, "errors" => $errors)); 
    }

    //sert à afficher la view_show
    public function show(){
        if(isset($_GET['param1'])){       
            $postId = $_GET['param1'];
            $result = Question::get_question($postId);
            $error = [];
            if($result){
                (new View("show"))->show(array("post"=> $result, "error" => $error));
            }else{
                self::redirect();
            }
        }else{
            self::redirect();
        }        
    }

    /*
    *Permet la gestion des boutons like et dislike. Si nous avons reçu un 'param3' alors 
    *le vote concerne une réponse sinon une question
    */
    public function like(){
        if(self::user_logged() && isset($_GET['param1']) && isset($_GET['param2'])){
            $value =  $_GET['param1'];
            $postId = $_GET['param2'];
            $userId = self::get_user_or_redirect()->getUserId();
            if(isset($_GET['param3'])){
                $AnswerId = $_GET['param3'];
                Vote::update_vote($userId, $AnswerId, $value); 
            }else{
                Vote::update_vote($userId, $postId, $value); 
            }
            self::redirect("post", "show", $postId);   
        }
        self::redirect();    
    }

    
    //Gère l'édition d'une post ou d'une réponse
    public function edit(){
        $questionId = '';
        $answerId = '';
        $error = [];
        if(!isset($_POST['body']) && isset($_GET['param1'])){
            $questionId = $_GET['param1'];
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
                self::view_answer_edition($questionId, $answerId, $error);
            }else{
                self::view_question_edition($questionId, $answerId, $error);
            }
        }else{
            self::edition_post();
        }
    }

    //Affiche la vue pour l'édition d'une réponse
    private function view_answer_edition($questionId, $answerId, $error){
        if (Question::get_question($questionId) &&  Answer::get_answer($answerId)){
            if(!is_numeric($questionId) || !is_numeric($answerId) 
                                        || !(self::get_user_or_false()->getUserId() === Answer::get_answer($answerId)->getAuthorId())){
                self::redirect();        
            }
        }else{
            self::redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, "post" => Answer::get_answer($answerId), 'error' => $error));         
    }

    //Affiche la vue pour l'edition d'une question
    private function view_question_edition($questionId, $answerId, $errors){
        if(!is_numeric($questionId)){
            self::redirect();
        }
        if (Question::get_question($questionId)){
            if(!(self::get_user_or_false()->getUserId() === Question::get_question($questionId)->getAuthorId())){
                self::redirect();     
            }
        }else{
            self::redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, "post" => Question::get_question($questionId), "error" => $errors));         
    }

    
    //Sert à ajouter la modification du corps d'une réponse ou d'une question (à modifier).
    private function edition_post(){
        if (isset($_GET['param1']) && isset($_POST['body'])){
            $questionId = $_GET['param1'];
            $body = $_POST['body'];
            $answerId = '';
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
                self::answer_edition($questionId, $answerId, $body);
            }else{
                self::question_edition($questionId, $answerId, $body);
            }                                    
        }else{
            self::redirect();
        }
    }
    
    private function answer_edition($parentId, $answerId, $body){
        if(self::get_user_or_redirect()->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            $answer = new Answer($body, null, $parentId, null, null, $answerId, null);
            $error = Answer::validate($answer);
            if(count($error) == 0){
                $answer->set_post();
                self::redirect("post", "show", $parentId);       
            }else{
                self::view_answer_edition($parentId, $answerId, $error);
            }
        }   
    }

    private function question_edition($questionId,  $answerId, $body){
        if(self::get_user_or_redirect()->getUserId() === Question::get_question($questionId)->getAuthorId()){
            $title = Question::get_question($questionId)->getTitle();
            $question = new Question($questionId, null, $title, $body, null, null, null, null, null, null, null);
            $errors = Question::validate($question);
            if(count($errors) == 0){
                $question->set_post();
                self::redirect("post", "show", $questionId);       
            }else{
                self::view_question_edition($questionId, $answerId, $errors);
            }
        }
 
    }

    //Gère la suppression d'un post ou d'une question (pas juste)
    public function delete(){
        $questionId = '';
        $answerId = '';
        if(self::get_user_or_redirect() && isset($_GET['param1'])){
            $questionId = $_GET['param1'];
            if(isset($_POST['cancel'])){
                self::redirect("post", "show", $questionId);
            }
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
            }
            if(isset($_POST['delete'])){
                self::show_delete_questionOrAnswer($questionId, $answerId);    
            }else if(isset($_POST['delete_confirmation'])){
                self::delete_questionOrAnswer($questionId, $answerId);   
            }else{
                self::redirect();
            } 
        }else{
            self::redirect();
        }
    }

    private function cancel_delete($postId){
        $postId = $_GET['param1'];
        self::redirect("post", "show", $postId);      
    }

    private function show_delete_questionOrAnswer($questionId, $answerId){
        if(is_numeric($answerId)){
            self::show_delete_answer($questionId, $answerId);  
        }else{
            self::show_delete_question($questionId);
        }
    }

    private function show_delete_question($questionId){
        if(self::get_user_or_redirect()->getUserId() === Question::get_question($questionId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=> $questionId));
        } 
    }
    
    private function show_delete_answer($questionId, $answerId){
        if(self::get_user_or_redirect()->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=> $questionId, "answerId"=> $answerId));
        }
    }  


    private function delete_questionOrAnswer($questionId, $answerId){
        if(is_numeric($answerId)){
            self::delete_answer($questionId, $answerId);
        }else{
            self::delete_question($questionId);        
        }
    }
    
    private function delete_question($questionId){
        $question = new Question($questionId, null, null, null, null, null, null, null, null, null, null);
        if(self::get_user_or_redirect()->getUserId() === Question::get_question($questionId)->getAuthorId() 
                                                     && $question->delete($questionId)){
            self::redirect();    
        }
    }

    private function delete_answer($questionId, $answerId){
        $answer = new Answer($answerId, null, $questionId, null, null, null, null);
        if(self::get_user_or_redirect()->getUserId() === Answer::get_answer($answerId)->getAuthorId() 
                                                    && $answer->delete($questionId, $answerId)){
            self::redirect("post", "show", $questionId);    
        }
    }
                
    public function answer(){
        if($this->user_logged() && isset($_GET['param1']) && isset($_POST['body'])){
            $parentId = $_GET['param1'];
            $body = $_POST['body'];
            $authorId = self::get_user_or_redirect()->getUserId();
            $answer = new Answer($body, $authorId, $parentId, null, null, null, null);
            $error = Answer::validate($answer);
            if(count($error) == 0){
                $answer->add_answer();
                self::redirect("post", "show", $parentId);
            }else{
                (new View("show"))->show(array("post"=> Question::get_question($parentId), "error" => $error)); 
            }           
        }else{
            self::redirect();
        }    
    }

    public function accept_answer(){
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            if(self::get_user_or_redirect()->getUserId() === Question::get_question($postId)->getAuthorId()){
                if(Question::accept_answer($postId, $answerId)){
                    self::redirect("post", "show", $postId);    
                }
            }
        }else{
            self::redirect();
        }   
    }

    // +/- mais pas juste !
    public function delete_accepted_answer(){
        if(isset($_GET['param1']) && isset($_POST['delete_acceptation'])){
            $postId = $_GET['param1'];
            if(self::get_user_or_redirect()->getUserId() === Question::get_question($postId)->getAuthorId()){
                if(Question::delete_accepted_answer($postId)){
                    self::redirect("post", "show", $postId);
                }
            }
        }else{
            self::redirect();
        }   
    }

    
}

?>