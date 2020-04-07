<?php

require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Utils.php';


class ControllerPost extends Controller{


    //Méthode posts pour les affichage ainsi que Tag
    public function index() {
        $user = null;
        $decode = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_POST['search'])) {
            $encode = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "index", $encode);
        }
        if(isset($_GET['param1'])){
            $decode = Utils::url_safe_decode($_GET['param1']);
        }
        (new View("index"))->show(array("posts"=> Question::get_questions($decode), "user" => $user,  "onglet" => 0));
    }

    public function unanswered() {
        $user = null;
        $decode = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_POST['search'])) {
            $encode = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "unanswered", $encode);
        }
        if(isset($_GET['param1'])){
            $decode = Utils::url_safe_decode($_GET['param1']);
        }
        (new View("index"))->show(array("posts"=> Question::get_questions_unanswered($decode), "user" => $user, "onglet" => 1));
    }

    public function votes(){
        $user = null;
        $decode = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_POST['search'])) {
            $encode = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "votes", $encode);
        }
        if(isset($_GET['param1'])){
            $decode = Utils::url_safe_decode($_GET['param1']);
        }
        (new View("index"))->show(array("posts"=> Question::get_questions_by_votes($decode), "user" => $user,  "onglet" => 2));    
    }


    public function show(){
        $user = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_false();
        if(isset($_GET['param1'])){       
            $postId = $_GET['param1'];
            $result = Question::get_question($postId);
            $error = [];
            if($result){
                (new View("show"))->show(array("post"=> $result, "error" => $error, "user" => $user));
            }else{
                self::redirect();
            }
        }else{
            self::redirect();
        }        
    }

    public function ask(){
        $title = '';
        $body = '';
        $errors = [];
        $user = self::get_user_or_redirect();
        if (isset($_POST['title']) && isset($_POST['body'])){
            $title = $_POST['title'];
            $body = $_POST['body'];
            $question = new Question(null, $user->getUserId(), $title , $body, null, null, null, null, null, null, null);
            $errors = Question::validate($question);
            if(count($errors) == 0){
                $question->create_question();   
                self::redirect();
            }
        }
        (new View("ask"))->show(array("title"=>$title, "body"=> $body, "errors" => $errors, "user" => $user)); 
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
                $answerId = $_GET['param3'];
                $vote = new Vote($userId, $answerId, null, null);
                $vote->update_vote($value);
            }else{
                $vote = new Vote($userId, $postId, null, null);
                $vote->update_vote($value);
            }
            self::redirect("post", "show", $postId);   
        }
        self::redirect();    
    }

    //Gère l'édition d'une post ou d'une réponse
    public function edit(){
        $questionId = '';
        $answerId = '';
        $errors = [];
        $user = self::get_user_or_redirect();
        if(!isset($_POST['body']) && isset($_GET['param1'])){
            $questionId = $_GET['param1'];
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
                self::view_answer_edition($questionId, $answerId, $user, $errors);
            }else{
                self::view_question_edition($questionId, $answerId, $user, $errors);
            }
        }else{
            self::edition_post($user);
        }
    }

    //Affiche la vue pour l'édition d'une réponse
    private function view_answer_edition($questionId, $answerId, $user, $errors){   
        if (Question::get_question($questionId) &&  Answer::get_answer($answerId)){
            if(!is_numeric($questionId) || !is_numeric($answerId) 
                                        || !($user->getUserId() === Answer::get_answer($answerId)->getAuthorId())){
                self::redirect();        
            }
        }else{
            self::redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, 
                                       "post" => Answer::get_answer($answerId),"user" => $user, "errors" => $errors));         
    }

    //Affiche la vue pour l'edition d'une question
    private function view_question_edition($questionId, $answerId, $user, $errors){
        if(!is_numeric($questionId)){
            self::redirect();
        }
        if (Question::get_question($questionId)){
            if(!($user->getUserId() === Question::get_question($questionId)->getAuthorId())){
                self::redirect();     
            }
        }else{
            self::redirect();
        }
        (new View("edit"))->show(array("parentId"=>$questionId, "answerId" => $answerId, 
                                       "post" => Question::get_question($questionId), "user" => $user, "errors" => $errors));         
    }
    
    //Sert à ajouter la modification du corps d'une réponse ou d'une question (à modifier).
    private function edition_post($user){
        if (isset($_GET['param1']) && isset($_POST['body'])){
            $questionId = $_GET['param1'];
            $body = $_POST['body'];
            $answerId = '';
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
                self::answer_edition($questionId, $answerId, $user, $body);
            }else if (isset($_POST['title'])){
                $title = $_POST['title'];
                self::question_edition($questionId, $answerId, $user, $title, $body);
            }                                    
        }else{
            self::redirect();
        }
    }
    
    private function answer_edition($parentId, $answerId, $user, $body){
        if($user->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            $answer = new Answer($body, null, $parentId, null, null, $answerId, null);
            $error = Answer::validate($answer);
            if(count($error) == 0){
                $answer->set_post();
                self::redirect("post", "show", $parentId);       
            }else{
                self::view_answer_edition($parentId, $answerId, $user, $error);
            }
        }   
    }

    private function question_edition($questionId, $answerId, $user, $title, $body){
        if($user->getUserId() === Question::get_question($questionId)->getAuthorId()){
            $question = new Question($questionId, null, $title, $body, null, null, null, null, null, null, null);
            $errors = Question::validate($question);
            if(count($errors) == 0){
                $question->set_post();
                self::redirect("post", "show", $questionId);       
            }else{
                self::view_question_edition($questionId, $answerId, $user, $errors);
            }
        }
    }

    //Gère la suppression d'un post ou d'une question (pas juste)
    public function delete(){
        $questionId = '';
        $answerId = '';
        $user = self::get_user_or_redirect();
        if(self::get_user_or_redirect() && isset($_GET['param1'])){
            $questionId = $_GET['param1'];
            if(isset($_POST['cancel'])){
                self::redirect("post", "show", $questionId);
            }
            if(isset($_GET['param2'])){
                $answerId = $_GET['param2'];
            }
            if(isset($_POST['delete'])){
                self::show_delete_questionOrAnswer($questionId, $answerId, $user);    
            }else if(isset($_POST['delete_confirmation'])){
                self::delete_questionOrAnswer($questionId, $answerId, $user);   
            }else{
                self::redirect("post", "show", $questionId);
            } 
        }else{
            self::redirect();
        }
    }

    private function show_delete_questionOrAnswer($questionId, $answerId, $user){
        if(is_numeric($answerId)){
            self::show_delete_answer($questionId, $answerId, $user);  
        }else{
            self::show_delete_question($questionId, $user);
        }
    }

    private function show_delete_question($questionId, $user){
        if($user->getUserId() === Question::get_question($questionId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=>$questionId, "user"=>$user));
        } 
    }
    
    private function show_delete_answer($questionId, $answerId, $user){
        if($user->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=>$questionId, "answerId"=>$answerId, "user"=>$user));
        }
    }  


    private function delete_questionOrAnswer($questionId, $answerId, $user){
        if(is_numeric($answerId)){
            self::delete_answer($questionId, $answerId, $user);
        }else{
            self::delete_question($questionId, $user);        
        }
    }
    
    private function delete_question($questionId, $user){
        $question = new Question($questionId, null, null, null, null, null, null, null, null, null, null);
        if($user->getUserId() === Question::get_question($questionId)->getAuthorId() && $question->delete()){
            self::redirect();    
        }
    }

    private function delete_answer($questionId, $answerId, $user){
        $answer = new Answer(null, null, $questionId, null, null, $answerId, null);
        if($user->getUserId() === Answer::get_answer($answerId)->getAuthorId() && $answer->delete()){
            self::redirect("post", "show", $questionId);    
        }
    }
                
    public function answer(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_POST['body'])){
            $parentId = $_GET['param1'];
            $body = $_POST['body'];
            $authorId = $user->getUserId();
            $answer = new Answer($body, $authorId, $parentId, null, null, null, null);
            $error = Answer::validate($answer);
            if(count($error) == 0){
                $answer->add_answer();
                self::redirect("post", "show", $parentId);
            }else{
                (new View("show"))->show(array("post"=> Question::get_question($parentId), "user"=> $user, "error" => $error)); 
            }           
        }else{
            self::redirect();
        }    
    }

    public function accept_answer(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            $question = Question::get_question($postId);
            if($user->getUserId() === $question->getAuthorId()){
                if($question->accept_answer($answerId)){
                    self::redirect("post", "show", $postId);    
                }
            }
        }else{
            self::redirect();
        }   
    }

    public function delete_accepted_answer(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_POST['delete_acceptation'])){
            $postId = $_GET['param1'];
            $question = Question::get_question($postId);
            if($user->getUserId() === $question->getAuthorId()){
                if($question->delete_accepted_answer()){
                    self::redirect("post", "show", $postId);
                }
            }
        }else{
            self::redirect();
        }   
    }
}

?>