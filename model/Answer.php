<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("framework/model.php");

class Answer extends model{
    private $body; 
    private $authorId;
    private $parentId;
    private $timestamp;
    private $fullNameUser;
    private $postId;
    private $nbVote;

    public function __construct($body, $authorId, $parentId, $timestamp, $fullNameUser, $postId, $nbVote){
        $this->body = $body;
        $this->authorId = $authorId;
        $this->parentId = $parentId;
        $this->timestamp = $timestamp;
        $this->fullNameUser = $fullNameUser;
        $this->postId = $postId;
        $this->nbVote = $nbVote;
    }

    public function getBodyMarkedown(){
        return self::markdown($this->body);
    }

    public function getBody(){
        return $this->body;
    }

    public function getAuthorId(){
        return $this->authorId;
    }

    public function getParentId(){
        return $this->parentId;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getFullNameUser(){
        return $this->fullNameUser;
    }

    public function getPostId(){
        return $this->postId;
    }

    public function getNbVote(){
        return $this->nbVote;
    }


    //Récupère toutes les réponses pour un post 
    public static function get_answers($parentId){
        $query = self::execute("SELECT * FROM post WHERE ParentId = :ParentId AND postid = (SELECT AcceptedAnswerId FROM post WHERE PostId = :PostId )
                                UNION 
                                SELECT * FROM post WHERE ParentId = :ParentId", array("ParentId"=>$parentId,"PostId"=>$parentId)); 
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $value){
            $results[] = new Answer($value['Body'], $value['AuthorId'], $value['ParentId'], 
                                    $value['Timestamp'], User::get_user_by_id($value['AuthorId'])->getFullName(), 
                                        $value['PostId'], Vote::get_NbVote($value['PostId']));
        }
        return $results;
    }

    //Récupère une seule réponse
    public static function get_answer($answerId){
        $query = self::execute("SELECT * FROM post WHERE PostId = :PostId", array("PostId"=>$answerId));
        $data = $query->fetch();
        return $result = new Answer($data['Body'], $data['AuthorId'], $data['ParentId'], 
                                    $data['Timestamp'], User::get_user_by_id($data['AuthorId'])->getFullName(), 
                                        $data['PostId'], Vote::get_NbVote($data['PostId']));
        
        
    }
    
    //Récupère le nombre de question pour un post
    public static function get_nbAnswers($postId){
        $query = self::execute("SELECT count(*) as nbAnswers FROM post WHERE ParentId = :PostId GROUP BY(ParentId)", array("PostId"=>$postId));
        $data = $query->fetch();
        return $data;
    }

    //Ajoute une réponse en bd pour un post donné
    public static function add_answer($userId, $parentId, $answer){
        self::execute("INSERT INTO post(AuthorId, Title, Body, ParentId) VALUES(:AuthorId, '', :Body, :ParentId)", array("AuthorId"=>$userId, "Body"=>$answer, "ParentId"=>$parentId));
        return true;
    }

    //Fait la somme des réponses pour le profile d'un utilisateur
    public static function sum_of_answers_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbAnswers from post where title ='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbAnswers = $data['nbAnswers'];
    }

    //Edite la réponse en bd
    public static function edit_answer($postId, $body){
        self::execute("UPDATE post SET Body = :Body WHERE PostId = :PostId", array("PostId"=>$postId, "Body"=>$body));
        return true;
    }

    public static function delete($postId, $answerId){
        if(Vote::delete($answerId) && Post::delete_accepted_question($postId)){
            self::execute("DELETE FROM post WHERE PostId = :AnswerId", array("AnswerId"=>$answerId));
            return true;
        }
        
    }
    //Conversion d'un text brut en markdown
    private static function markdown($markedown){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true); 
        return $html = $Parsedown->text($markedown);  
    }


}
?>



