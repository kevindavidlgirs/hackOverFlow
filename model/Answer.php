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

    public static function get_answers($postId){
        $query = self::execute("SELECT * FROM post WHERE ParentId = :PostId", array("PostId"=>$postId));
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $value){
            $results[] = new Answer(self::markdown($value['Body']), $value['AuthorId'], $value['ParentId'], 
                                    $value['Timestamp'], User::get_user_by_id($value['AuthorId'])->getFullName(), 
                                        $value['PostId'], Vote::get_NbVote($value['PostId']));
        }
        return $results;
    }

    public static function get_nbAnswers($postId){
        $query = self::execute("SELECT count(*) as nbAnswers FROM post WHERE ParentId = :PostID GROUP BY(ParentId)", array("PostID"=>$postId));
        $data = $query->fetch();
        return $data;
    }

    public static function sum_of_answers_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbAnswers from post where title ='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbAnswers = $data['nbAnswers'];
    }

    private static function markdown($markedown){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true); 
        return $html = $Parsedown->text($markedown);  
    }

}
?>



