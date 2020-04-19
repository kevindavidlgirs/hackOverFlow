<?php

require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once('framework/Utils.php');
require_once("framework/Model.php");


Class Comment extends Model{

    private $commentId;
    private $authorId;
    private $fullNameAuthor;
    private $postId;
    private $body;
    private $timeStamp;

    public function __construct($commentId, $authorId, $fullNameAuthor, $postId, $body, $timeStamp){
        $this->commentId = $commentId;
        $this->authorId = $authorId;
        $this->fullNameAuthor = $fullNameAuthor;
        $this->postId = $postId;
        $this->body = $body;
        $this->timeStamp = $timeStamp;
    }

    public function getCommentId(){
        return $this->commentId;
    }

    public function getAuthorId(){
        return $this->authorId;
    }

    public function getFullNameAuthor(){
        return $this->fullNameAuthor;
    }

    public function getPostId(){
        return $this->postId;
    }

    public function getBodyMarkedown(){
        return self::markdown($this->body);
    }

    public function getBody(){
        return $this->body;
    }

    public function getTimeStamp(){
        return $this->timeStamp;
    }


    public static function get_comment($commentId){
        $query = self::execute("SELECT * FROM comment WHERE CommentId = :CommentId", array("CommentId" => $commentId));
        $data = $query->fetch();
        if($query->rowCount() == 0){
            return false;
        }else{
            return $result = new Comment($data['CommentId'], $data['UserId'], null, $data['PostId'], $data['Body'], null);
        }
    }

    public static function get_comments_by_postId($postId){
        $query = self::execute("SELECT * FROM comment WHERE PostId = :PostId", array("PostId" => $postId));
        $data = $query->fetchAll();
        $results = [];
        if($query->rowCount() == 0){
            return false;
        }else{
            foreach($data as $row){
                $results[] = new Comment($row['CommentId'], $row['UserId'], User::get_user_by_id($row['UserId'])->getFullName(), $row['PostId'], $row['Body'], $row['Timestamp']);
            }
            return $results;
        }
    }

    public static function validate($comment){
        $error = [];
        if(strlen($comment->getBody()) < 10 ||  strlen($comment->getBody()) > 100){
            $error['body'] = "The length of the comment must be between 10 and 100 characters."; 
        }
        return $error;
    }

    public function create(){
        $query = self::execute("INSERT INTO comment(UserId, PostId, Body) values(:UserId, :PostId, :Body)", 
                                array('UserId'=> $this->authorId, 'PostId'=> $this->postId, 'Body'=> $this->body));
       
    }

    public function update(){
        self::execute("UPDATE comment SET Body = :Body WHERE CommentId = :CommentId", array("Body"=>$this->body, "CommentId" => $this->commentId));
        return true;
    }

    public function delete(){
        self::execute("DELETE FROM comment WHERE CommentId = :CommentId", array("CommentId"=>$this->commentId));
        return true;
    }

    public function deleteAll($user){
        if($user->isAdmin()){
            $comments = self::get_comments_by_postId($this->postId);
            foreach($comments as $comment){
                $comment->delete();
            }
        }
        return true;
    }

    public function nbComments_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbComments from comment where UserId = :UserId", array("UserId"=>$userId));
        $data = $query->fetch();
        return $result = $data['nbComments'];
    }

    public static function markdown($markedown){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true); 
        return $html = $Parsedown->text($markedown);  
    }
    

}