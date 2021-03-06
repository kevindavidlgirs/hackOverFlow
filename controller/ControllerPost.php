<?php

require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Utils.php';


class ControllerPost extends Controller{

    //                                                                  PHP

    public function index() {
        $user = null;
        $page = 1;
        $nb_pages = 0;
        $start_from = 0;
        $record_per_page = 5;
        $search_enc = null;
        $search_dec = null;
        $questions = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_GET['param1']) && isset($_POST['search'])) {
            $page = $_GET['param1'];
            $search_enc = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "index", $page, $search_enc);
        }elseif(isset($_GET['param1'])){
            if(is_numeric($_GET['param1'])){
                $page = $_GET['param1'];
                if(isset($_GET['param2'])){
                    $search_enc  = $_GET['param2'];
                    $search_dec = Utils::url_safe_decode($_GET['param2']);
                }
                $start_from = ($page-1)*$record_per_page;
                $questions = Question::get_questions($search_dec, $start_from, $record_per_page);
                $nb_pages = ceil(Question::count_questions($search_dec)/$record_per_page);
                if($page !=1 && ($page > $nb_pages || $page < 0))
                    self::redirect("post", "index", 1);
                if(!$questions){
                    $page = 1;
                    $start_from = ($page-1)*$record_per_page;
                    $questions = Question::get_questions($search_dec, $start_from, $record_per_page);
                    $nb_pages = ceil(Question::count_questions($search_dec)/$record_per_page);
                }
            }else{
                self::redirect("post", "index", 1);
            }
        }else{
            $questions = Question::get_questions($search_dec, $start_from, $record_per_page);
            $nb_pages = ceil(Question::count_questions($search_dec)/$record_per_page);
        }
        (new View("index"))->show(array("posts"=> $questions, "user" => $user, "filter" => 'newest', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));
    }

    public function active(){
        $user = null;
        $page = 1;
        $nb_pages = 0;
        $start_from = 0;
        $record_per_page = 5;
        $search_enc = null;
        $search_dec = null;
        $questions = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_GET['param1']) && isset($_POST['search'])) {
            $page = $_GET['param1'];
            $search_enc = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "active", $page, $search_enc);
        }elseif(isset($_GET['param1'])){
            if(is_numeric($_GET['param1'])){
                $page = $_GET['param1'];
                if(isset($_GET['param2'])){
                    $search_enc  = $_GET['param2'];
                    $search_dec = Utils::url_safe_decode($_GET['param2']);
                }
                $start_from = ($page-1)*$record_per_page;
                $questions = Question::get_questions_active($search_dec, $start_from, $record_per_page);
                $nb_pages = ceil(Question::count_questions_active($search_dec)/$record_per_page);
                if($page !=1 && ($page > $nb_pages || $page < 0))
                    self::redirect("post", "active", 1);
                if(!$questions){
                    $page = 1;
                    $start_from = ($page-1)*$record_per_page;
                    $questions = Question::get_questions_active($search_dec, $start_from, $record_per_page);
                    $nb_pages = ceil(Question::count_questions_active($search_dec)/$record_per_page);
                }
            }else{
                self::redirect("post", "active", 1);
            }
        }else{
            $questions = Question::get_questions_active($search_dec, $start_from, $record_per_page);
            $nb_pages = ceil(Question::count_questions_active($search_dec)/$record_per_page);
        }
        (new View("index"))->show(array("posts"=> $questions , "user" => $user, "filter" => 'active', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));
    }

    public function unanswered() {
        $user = null;
        $page = 1;
        $nb_pages = 0;
        $start_from = 0;
        $record_per_page = 5;
        $search_enc = null;
        $search_dec = null;
        $questions = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_GET['param1']) && isset($_POST['search'])) {
            $page = $_GET['param1'];
            $search_enc = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "unanswered", $page, $search_enc);
        }elseif(isset($_GET['param1'])){
            if(is_numeric($_GET['param1'])){
                $page = $_GET['param1'];
                if(isset($_GET['param2'])){
                    $search_enc  = $_GET['param2'];
                    $search_dec = Utils::url_safe_decode($_GET['param2']);
                }
                $start_from = ($page-1)*$record_per_page;
                $questions = Question::get_questions_unanswered($search_dec, $start_from, $record_per_page);
                $nb_pages = ceil(Question::count_questions_unanswered($search_dec)/$record_per_page);
                if($page !=1 && ($page > $nb_pages || $page < 0))
                    self::redirect("post", "unanswered", 1);
                if(!$questions){
                    $page = 1;
                    $start_from = ($page-1)*$record_per_page;
                    $questions = Question::get_questions_unanswered($search_dec, $start_from, $record_per_page);
                    $nb_pages = ceil(Question::count_questions_unanswered($search_dec)/$record_per_page);
                }
            }else{
                self::redirect("post", "unanswered", 1);
            }
        }else{
            $questions = Question::get_questions_unanswered($search_dec, $start_from, $record_per_page);
            $nb_pages = ceil(Question::count_questions_unanswered($search_dec)/$record_per_page);
        }
        (new View("index"))->show(array("posts"=> $questions, "user" => $user, "filter" => 'unanswered', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));
    }

    public function votes(){
        $user = null;
        $page = 1;
        $nb_pages = 0;
        $start_from = 0;
        $record_per_page = 5;
        $search_enc = null;
        $search_dec = null;
        $questions = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if (isset($_GET['param1']) && isset($_POST['search'])) {
            $page = $_GET['param1'];
            $search_enc = Utils::url_safe_encode($_POST['search']);
            self::redirect("post", "votes", $page, $search_enc);
        }elseif(isset($_GET['param1'])){
            if(is_numeric($_GET['param1'])){
                $page = $_GET['param1'];
                if(isset($_GET['param2'])){
                    $search_enc  = $_GET['param2'];
                    $search_dec = Utils::url_safe_decode($_GET['param2']);
                }
                $start_from = ($page-1)*$record_per_page;
                $questions = Question::get_questions_by_votes($search_dec, $start_from, $record_per_page);
                $nb_pages = ceil(Question::count_questions_by_votes($search_dec)/$record_per_page);
                if($page !=1 && ($page > $nb_pages || $page < 0))
                    self::redirect("post", "votes", 1);
                if(!$questions){
                    $page = 1;
                    $start_from = ($page-1)*$record_per_page;
                    $questions = Question::get_questions_by_votes($search_dec, $start_from, $record_per_page);
                    $nb_pages = ceil(Question::count_questions_by_votes($search_dec)/$record_per_page);
                }
            }else{
                self::redirect("post", "votes", 1);
            }
        }else{
            $questions = Question::get_questions_by_votes($search_dec, $start_from, $record_per_page);
            $nb_pages = ceil(Question::count_questions_by_votes($search_dec)/$record_per_page);
        }
        (new View("index"))->show(array("posts"=> $questions, "user" => $user, "filter" => 'votes', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));    
    }

    public function tags(){
        $user = null; 
        $page = 1;
        $nb_pages = 0;
        $start_from = 0;
        $record_per_page = 5;
        $search_enc = null;
        $search_dec = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_GET['param2']) && !isset($_GET['param3']) && !isset($_POST['search'])){
            if(!is_numeric($_GET['param2']) || !is_numeric($_GET['param1']))
                $this->redirect();  
            $tagId = $_GET['param1'];  
            $page = $_GET['param2'];
            $tag = Tag::get_tag_by_id($tagId);
            $nb_pages = ceil(Question::count_questions_by_tag($tagId)/$record_per_page);
            if($page !=1 && ($page > $nb_pages || $page < 0))
                self::redirect("post", "tags", $tagId, 1);
            $start_from = ($page-1)*$record_per_page;
            if($tag){
                (new View("index"))->show(array("posts"=> Question::get_questions_by_tag($tagId, $search_dec, $start_from, $record_per_page), "user" => $user, "tag" => $tag, "filter" => 'Question tagged', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));    
            }else{
                $this->redirect();
            }
        }else{
            if (isset($_GET['param1']) && isset($_GET['param2']) && !isset($_GET['param3']) && isset($_POST['search'])) {
                if(!is_numeric($_GET['param2']) || !is_numeric($_GET['param1']))
                    $this->redirect();
                $tagId = $_GET['param1'];
                $page = $_GET['param2'];
                $search_enc = Utils::url_safe_encode($_POST['search']);
                self::redirect("post", "tags", $tagId, $page, $search_enc);
            }elseif(isset($_GET['param1']) && isset($_GET['param2']) && isset($_GET['param3']) && !isset($_POST['search'])){            
                if(!is_numeric($_GET['param2']) || !is_numeric($_GET['param1']))
                    $this->redirect();
                $tagId = $_GET['param1'];
                $page = $_GET['param2'];
                $search_enc = $_GET['param3'];
                $search_dec = Utils::url_safe_decode($search_enc);
                $nb_pages = ceil(Question::count_questions_by_tag($tagId, $search_dec)/$record_per_page);
                if($page !=1 && ($page > $nb_pages || $page < 0))
                    self::redirect("post", "tags", $tagId, 1);
                $start_from = ($page-1)*$record_per_page;
                $tag = Tag::get_tag_by_id($tagId);
            }elseif(isset($_GET['param1']) && !isset($_GET['param2']) && !isset($_GET['param3']) && !isset($_POST['search'])){
                $tagId = $_GET['param1'];
                self::redirect("post", "tags", $tagId, $page);
            }
            if($tag){
                (new View("index"))->show(array("posts"=> Question::get_questions_by_tag($tagId, $search_dec, $start_from, $record_per_page), "user" => $user, "tag" => $tag, "filter" => 'Question tagged', "search_enc" => $search_enc, "nb_pages" => $nb_pages, "page" => $page));    
            }else{
                $this->redirect();
            }    
        }
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
                (new View("show"))->show(array("post"=> $result, "error" => $error, "user" => $user, "allTags" => Tag::getAllTags(), "max_tags" => Configuration::get("max_tags")));
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
            $tagsId = [];
            if(isset($_POST['choice'])){
                $tagsId = $_POST['choice'];
            }
            $title = $_POST['title'];
            $body = $_POST['body'];
            $question = new Question(null, $user->getUserId(), $title , $body, null, null, null, null, null, null, null, null, null);
            $errors = Question::validate($question, Configuration::get("max_tags"), $tagsId);
            if(count($errors) == 0){
                $question->create($tagsId);   
                self::redirect();
            }
        }
        (new View("ask"))->show(array("title"=>$title, "body"=> $body, "errors" => $errors, "user" => $user, "allTags" => Tag::getAllTags(), "max_tags" => Configuration::get("max_tags"))); 
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
        $question = Question::get_question($questionId);
        $answer = Answer::get_answer($answerId);
        if(!$question || !$answer || !($user->isAdmin() || $user->getUserId() === $answer->getAuthorId())
                                  || !($question->getPostId() === $answer->getParentId())){
            self::redirect();           
        }else{
            (new View("edit"))->show(array("post" => $answer, "user" => $user, "errors" => $errors));         
        }
    }

    //Affiche la vue pour l'edition d'une question
    private function view_question_edition($questionId, $answerId, $user, $errors){
        $question = Question::get_question($questionId);
        if(!$question || !($user->isAdmin() || $user->getUserId() === $question->getAuthorId())){
            self::redirect();     
        }else{
            (new View("edit"))->show(array("post" => $question, "user" => $user, "errors" => $errors));         
        }
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
        if($user->isAdmin() || $user->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            $answer = new Answer($body, null, $parentId, null, null, $answerId, null, null);
            $error = Answer::validate($answer);
            if(count($error) == 0){
                $answer->update();
                self::redirect("post", "show", $parentId);       
            }else{
                self::view_answer_edition($parentId, $answerId, $user, $error);
            }
        }   
    }

    private function question_edition($questionId, $answerId, $user, $title, $body){
        if($user->isAdmin() || $user->getUserId() === Question::get_question($questionId)->getAuthorId()){
            $question = new Question($questionId, null, $title, $body, null, null, null, null, null, null, null, null, null);
            $errors = Question::validate($question, null, null);
            if(count($errors) == 0){
                $question->update();
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
        if(isset($_GET['param1'])){
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

    private function show_delete_answer($questionId, $answerId, $user){
        if($user->isAdmin() || $user->getUserId() === Answer::get_answer($answerId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=>$questionId, "answerId"=>$answerId, "user"=>$user));
        }
    }  


    private function show_delete_question($questionId, $user){
        if($user->isAdmin() || $user->getUserId() === Question::get_question($questionId)->getAuthorId()){
            (new View("delete"))->show(array("postId"=>$questionId, "user"=>$user));
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
        $question = new Question($questionId, null, null, null, null, null, null, null, null, null, null, null, null);
        if(($user->isAdmin() || $user->getUserId() === Question::get_question($questionId)->getAuthorId()) && $question->delete($user)){
            self::redirect();    
        }
    }

    private function delete_answer($questionId, $answerId, $user){
        $answer = new Answer(null, null, $questionId, null, null, $answerId, null, null);
        if(($user->isAdmin() || $user->getUserId() === Answer::get_answer($answerId)->getAuthorId()) && $answer->delete($user)){
            self::redirect("post", "show", $questionId);    
        }
    }
                
    public function answer(){
        if(self::get_user_or_false()){
            $user = self::get_user_or_redirect();
            if(isset($_GET['param1']) && isset($_POST['body'])){
                $parentId = $_GET['param1'];
                $body = $_POST['body'];
                $authorId = $user->getUserId();
                $answer = new Answer($body, $authorId, $parentId, null, null, null, null, null);
                $error = Answer::validate($answer);
                if(count($error) == 0){
                    $answer->create();
                    self::redirect("post", "show", $parentId);
                }else{
                    (new View("show"))->show(array("post"=> Question::get_question($parentId), "user"=> $user, "error" => $error, "max_tags" => Configuration::get("max_tags"))); 
                }           
            }else{
                self::redirect();
            }
        }else{
            self::redirect("user", "signup");
        }     
    }

    public function accept_answer(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $answerId = $_GET['param2'];
            $question = Question::get_question($postId);
            if($user->isAdmin() || $user->getUserId() === $question->getAuthorId()){
                if($question->accept_answer($answerId)){
                    self::redirect("post", "show", $postId);    
                }
            }else{
                $this->redirect();
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
            if($user->isAdmin() || $user->getUserId() === $question->getAuthorId()){
                if($question->delete_accepted_answer()){
                    self::redirect("post", "show", $postId);
                }
            }else{
                $this::redirect();
            }
        }else{
            self::redirect();
        }   
    }

    public function addTag(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_POST['tag'])){
            $postId = $_GET['param1'];
            $tagName = $_POST['tag'];
            $max_tags = Configuration::get("max_tags");
            $question = Question::get_question($postId);
            if(($user->isAdmin() || $user->getUserId() === $question->getAuthorId()) 
                                && (int)$question->getNbTags() < (int)$max_tags && Tag::testExistenceByName($tagName)){
                $tag = Tag::get_tag_by_name($tagName);
                if($question->addTag($tag->getTagId())){
                    self::redirect("post", "show", $postId);    
                }
            }else{
                self::redirect("post", "show", $postId);    
            }
        }else{
            $this->redirect();
        }
    }

    public function removeTag(){
        $user = self::get_user_or_redirect();
        if(isset($_GET['param1']) && isset($_GET['param2'])){
            $postId = $_GET['param1'];
            $tagId = $_GET['param2'];
            $question = Question::get_question($postId);
            if(($user->isAdmin() || $user->getUserId() === $question->getAuthorId()) && Tag::testExistenceById($tagId)){
                $tag = Tag::get_tag_by_id($tagId);
                if($question->removeTag($tag->getTagId())){
                    self::redirect("post", "show", $postId);    
                }else{
                    self::redirect("post", "show", $postId);    
                }
            }else{
                self::redirect("post", "show", $postId);    
            }
        }else{
            $this->redirect();
        }
    }


    //                                                                      JAVASCRIPT

    //Découpe nécessaire.
    public function get_questions_service(){
        $search = null;
        $page = 1;
        $typeList;
        $start_from = 0;
        $record_per_page = 5;

        if(isset($_POST['search'])){
            $search = $_POST['search'];
        }
        if(isset($_GET['param1'])){
            $typeList = $_GET['param1'];
        }
        if(isset($_GET['param2']) && is_numeric($_GET['param2']) && $_GET['param2'] > 0){
            $page = $_GET['param2'];
        }       
        
        $start_from = ($page-1)*$record_per_page;

        if($typeList == 'newest'){
            $nb_pages = ceil(Question::count_questions($search)/$record_per_page);
            if($page != 1 && $page > $nb_pages){
                self::return_json_question(Question::get_questions($search, 0, $record_per_page), $nb_pages); 
            }
            self::return_json_question(Question::get_questions($search, $start_from, $record_per_page), $nb_pages);

        }else if ($typeList === 'active'){
            $nb_pages = ceil(Question::count_questions_active(null)/$record_per_page);
            if($page != 1 && $page > $start_from){
                self::return_json_question(Question::get_questions(null, 0, $record_per_page), $nb_pages); 
            }
            self::return_json_question(Question::get_questions_active(null, $start_from, $record_per_page), $nb_pages);

        }else if ($typeList === 'unanswered'){
            $nb_pages = ceil(Question::count_questions_unanswered(null)/$record_per_page);
            if($page != 1 && $page > $start_from){
                self::return_json_question(Question::get_questions(null, 0, $record_per_page), $nb_pages); 
            }
            self::return_json_question(Question::get_questions_unanswered(null, $start_from, $record_per_page), $nb_pages);

        }else if ($typeList === 'votes'){
            $nb_pages = ceil(Question::count_questions_by_votes(null)/$record_per_page);
            if($page != 1 && $page > $start_from){
                self::return_json_question(Question::get_questions(null, 0, $record_per_page), $nb_pages); 
            }
            self::return_json_question(Question::get_questions_by_votes(null, $start_from, $record_per_page), $nb_pages);

        }else if ($typeList === 'tags' && isset($_GET['param3']) && is_numeric($_GET['param3'])){
            $tagId = $_GET['param3'];
            $nb_pages = ceil(Question::count_questions_by_tag($tagId, null)/$record_per_page);
            if($page != 1 && $page > $start_from){
                self::return_json_question(Question::get_questions(null, 0, $record_per_page), $nb_pages); 
            }
            self::return_json_question(Question::get_questions_by_tag($tagId, null, $start_from, $record_per_page), $nb_pages);
        }
    }
    

    private function return_json_question($questions, $nb_pages){
        if($questions){
            echo $questions_json = Question::get_questions_as_json($questions, $nb_pages); 
        }else{
            echo "[]";
        }
    }
}

?>