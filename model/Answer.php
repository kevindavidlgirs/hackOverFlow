<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("model/Post.php");

class Answer extends Post{
    private $parentId;
    private $timestamp;
    private $nbVote;

    public function __construct($body, $authorId, $parentId, $timestamp, $fullNameAuthor, $postId, $totalVote, $comments){
        $this->postId = $postId;
        $this->body = $body;
        $this->authorId = $authorId;
        $this->fullNameAuthor = $fullNameAuthor;
        $this->parentId = $parentId;
        $this->timestamp = $timestamp;
        $this->totalVote = $totalVote;
        $this->comments = $comments;
    }

    public function getFullNameAuthor(){
        return $this->fullNameAuthor;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getParentId(){
        return $this->parentId;
    }

    //Récupère une seule réponse
    public static function get_answer($answerId){
        $query = self::execute("SELECT * FROM post WHERE PostId = :PostId", array("PostId"=>$answerId));
        $data = $query->fetch();
        if($query->rowCount() !== 0){
            return $result = new Answer($data['Body'], $data['AuthorId'], $data['ParentId'], 
                                    $data['Timestamp'], User::get_user_by_id($data['AuthorId'])->getFullName(), 
                                        $data['PostId'], Vote::get_SumVote($data['PostId']), null);  
        }
        return false;
    }

    //Récupère en premier lieu la réponse acceptée puis les réponses sur base du score des votes
    public static function get_answers($parentId){
        $results = [];
        $query = self::execute("SELECT * FROM post WHERE ParentId = :ParentId AND postid = (SELECT AcceptedAnswerId FROM post WHERE PostId = :PostId )", array("ParentId"=>$parentId,"PostId"=>$parentId));
        $data = $query->fetch(); 
        if($query->rowCount() !== 0){
            $results[] = new Answer($data['Body'], $data['AuthorId'], $data['ParentId'], 
                                $data['Timestamp'], User::get_user_by_id($data['AuthorId'])->getFullName(), 
                                $data['PostId'], Vote::get_SumVote($data['PostId'])->getTotalVote(), Comment::get_comments_by_postId($data['PostId'])); 
        } 
        $query = self::execute("SELECT post.*, max_score FROM post, 
                                    ( 
                                    SELECT postId, max(score) max_score FROM 
                                        ( 
                                        SELECT post.postid, ifnull(sum(vote.updown), 0) score FROM 
                                        post LEFT JOIN vote ON vote.postid = post.postid WHERE post.ParentId = :ParentId 
                                        and post.PostId != ifnull((SELECT AcceptedAnswerId FROM post WHERE post.PostId = :ParentId ), 0) 
                                        GROUP BY post.postid 
                                        ) AS tbl1 
                                        GROUP by postId 
                                    ) AS q1 WHERE post.postid = q1.postId ORDER BY q1.max_score DESC, timestamp DESC", array("ParentId"=>$parentId)); 
        $data1 = $query->fetchAll();
        foreach($data1 as $value){
            $results[] = new Answer($value['Body'], $value['AuthorId'], $value['ParentId'], 
                                    $value['Timestamp'], User::get_user_by_id($value['AuthorId'])->getFullName(), 
                                        $value['PostId'], Vote::get_SumVote($value['PostId'])->getTotalVote(), Comment::get_comments_by_postId($value['PostId']));
        }
        return $results;
    }
   
    //Récupère le nombre de question pour un post
    public static function get_nbAnswers($questionId){
        $query = self::execute("SELECT count(*) as nbAnswers FROM post WHERE ParentId = :PostId GROUP BY(ParentId)", array("PostId"=>$questionId));
        $data = $query->fetch();
        return $result = $data['nbAnswers'] ??= 0; //Opérateur D'affectation De Coalescence Nulle
                                                   //https://www.designcise.com/web/tutorial/whats-the-difference-between-null-coalescing-operator-and-ternary-operator-in-php 
    }

    public static function nbAnswers_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbAnswers from post where title ='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $result = $data['nbAnswers'];
    }

    public static function validate($answer){
        $error = [];
        if(strlen($answer->getBody()) < 30){
            $error['body'] = "The length of the body must be greater than or equal to 30 characters"; 
        }
        return $error;
    }

    //Ajoute une réponse en BD pour un post donné
    public function create(){
        self::execute("INSERT INTO post(AuthorId, Title, Body, ParentId) VALUES(:AuthorId, '', :Body, :ParentId)", array("AuthorId"=>$this->authorId, "Body"=>$this->body, "ParentId"=>$this->parentId));
        return true;
    }

    public function delete(){
        $vote = new Vote(null, $this->postId, null, null);
        $post = new Question($this->parentId, null, null, null, null, null, null, null, null, null, null, null, null);
        if($vote->delete() && $post->delete_accepted_answer()){
            self::execute("DELETE FROM post WHERE PostId = :AnswerId", array("AnswerId"=>$this->postId));
            return true;
        }
        
    }
        
    public function update(){
        self::execute("UPDATE post SET Body = :Body WHERE PostId = :PostId", array("PostId"=>$this->postId, "Body"=>$this->body));
        return true;
    }

    public function isQuestion(){
        return false;
    }
}
?>



