<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("framework/model.php");
require_once("model/User.php");
require_once("model/Vote.php");
require_once("model/Answer.php");


class Post extends Model {
    private $PostId;
    private $AuthorId;
    private $Title;
    private $Body;
    private $Timestamp;
    private $FullNameUser;
    private $TotalVote;
    private $nbAnswers;
    private $nbVote;
    private $Answers;
    private $AcceptedAnswerId;
    
    //Réduire le nombre de getters avec des function d'instance
    public function getPostId(){
        return $this->PostId;
    }
    public function getAuthorId(){
        return $this->AuthorId;
    }
    public function getTitle(){
        return $this->Title;
    }
    public function getBodyMarkedown(){
        return self::markdown($this->Body);
    }
    public function getBodyMarkedownRemoved(){
        return self::remove_markdown($this->Body);    
    }
    public function getBody(){
        return $this->Body;
    }
    public function getTimestamp(){
        return $this->Timestamp;
    }
    public function getFullNameUser(){
        return $this->FullNameUser;
    }
    public function getTotalVote(){
        return $this->TotalVote;
    }
    public function getNbAnswers(){
        return $this->nbAnswers;
    }
    public function getAnswers(){
        return $this->Answers;
    }
    public function getNbVote(){
        return $this->nbVote;
    }
    public function getAcceptedAnswerId(){
        return $this->AcceptedAnswerId;
    }

    public function __construct($PostId, $AuthorId, $Title, $Body, $Timestamp, $FullNameUser, $TotalVote, $nbAnswers, $AcceptedAnswerId, $Answers, $nbVote){
        $this->PostId = $PostId;
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->FullNameUser = $FullNameUser;
        $this->TotalVote = $TotalVote;
        $this->nbAnswers = $nbAnswers;
        $this->AcceptedAnswerId = $AcceptedAnswerId;
        $this->Answers = $Answers;
        $this->nbVote = $nbVote;
    }

    //Permet de récupérer tous les posts, le nom de l'auteur de chaque post, la somme des votes pour chaque post,  
    //et le nombre de réponse de chaque post.
    public static function get_posts(){
        $query = self::execute(" SELECT * FROM post WHERE title !='' ORDER BY timestamp DESC ", array());
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Post($row["PostId"], $row["AuthorId"], Tools::sanitize($row["Title"]), self::remove_markdown($row["Body"]), 
                                    $row["Timestamp"], User::get_user_by_id($row["AuthorId"])->getFullName(), Vote::get_SumVote($row["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($row["PostId"])['nbAnswers'], null, null, null);
        }
        return $results;
    }

    //Récupère un post grace à son id
    public static function get_post($postId){
        $query = self::execute("SELECT * FROM post WHERE PostId = :PostId", array("PostId"=>$postId));
        $post = $query->fetch();
        return $result = new Post($post["PostId"], $post["AuthorId"], Tools::sanitize($post["Title"]), $post["Body"], $post["Timestamp"], 
                                    User::get_user_by_id($post["AuthorId"])->getFullName(), Vote::get_SumVote($post["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($postId)['nbAnswers'], $post["AcceptedAnswerId"], Answer::get_answers($postId), Vote::get_nbVote($post["PostId"]));
    }

    //Fait la somme des questions pour le profile de l'utilisateur
    public static function sum_of_questions_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbQuestions from post where title !='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbQuestion = $data['nbQuestions'];
    }
    
    //Crée un post en bd. Attention méthode à utiliser
    public function create_post(){
        $query = self::execute("INSERT INTO post(AuthorId, Title, Body) values(:AuthorId, :Title, :Body)", 
                                array('AuthorId'=>$this->AuthorId, 'Title'=>$this->Title, 'Body'=>$this->Body));    
    }

    
    public function get_upDown_vote($userId, $postId){
        return $result = Vote::get_upDown($userId, $postId);    
    }

    private static function markdown($markedown){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true); 
        return $html = $Parsedown->text($markedown);  
    }

    private static function remove_markdown($value){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
	    $html = $Parsedown->text($value);
        return strip_tags($html);
    }

    public static function edit_post($postId, $body){
        self::execute("UPDATE post SET Body = :Body WHERE PostId = :PostId", array("PostId"=>$postId, "Body"=>$body));
        return true;
    }

    public static function delete($postId){
        if(Vote::delete($postId)){
            self::execute("DELETE FROM post WHERE PostId = :PostId", array("PostId"=>$postId));
            return true;
        }
        
    }
    public static function accept_question($postId, $answerId){
        self::execute("UPDATE post SET AcceptedAnswerId = :AcceptedAnswerId  WHERE PostId = :PostId", array("PostId"=>$postId,"AcceptedAnswerId"=> $answerId));
        return true;
    }
    public static function delete_accepted_question($postId){
        self::execute("UPDATE post SET AcceptedAnswerId = NULL WHERE PostId = :PostId", array("PostId"=>$postId));  
        return true;  
    }

}

?>