<?php
require_once("framework/model.php");
require_once("model/User.php");
require_once("model/Vote.php");

class Post extends Model {
    private $PostId;
    private $AuthorId;
    private $Title;
    private $Body;
    private $Timestamp;
    private $FullNameUser;
    private $TotalVote;
    private $nbAnswers;
    
    public function getPostId(){
        return $this->PostId;
    }
    public function getAuthorId(){
        return $this->AuthorId;
    }
    public function getTitle(){
        return $this->Title;
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

    public function __construct($PostId, $AuthorId, $Title, $Body, $Timestamp, $FullNameUser, $TotalVote, $nbAnswers){
        $this->PostId = $PostId;
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->FullNameUser = $FullNameUser;
        $this->TotalVote = $TotalVote;
        $this->nbAnswers = $nbAnswers;
    }

    //Permet de récupérer tous les posts, le nom de l'auteur de chaque post, la somme des votes pour chaque post,  
    //et le nombre de réponse de chaque post.
    public static function get_posts(){
        $query = self::execute(" SELECT * FROM post WHERE title !='' ORDER BY timestamp DESC ", array());
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $getFullNameAuthor = User::get_user_by_id($row["AuthorId"]);
            $getSumVote = Vote::get_SumVote($row["PostId"]);
            $query = self::execute("SELECT count(*) as nbAnswers FROM post WHERE ParentId = :PostID GROUP BY(ParentId)", array("PostID"=>$row["PostId"]));
            $nbAnswers = $query->fetch();
            $results[] = new Post($row["PostId"], $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $getFullNameAuthor->getFullName(), $getSumVote->getTotalVote(), $nbAnswers['nbAnswers'] );
        }
        return $results;
    }

    public static function get_post($postId){
        $query = self::execute("SELECT * FROM post WHERE PostId = :PostId", array("PostId"=>$postId));
        $post = $query->fetch();
        $getFullNameAuthor = User::get_user_by_id($post["AuthorId"]);
        $getSumVote = Vote::get_SumVote($post["PostId"]);
        $query = self::execute("SELECT count(*) as nbAnswers FROM post WHERE ParentId = :PostID GROUP BY(ParentId)", array("PostID"=>$post["PostId"]));
        $nbAnswers = $query->fetch();
        $result = new Post($post["PostId"], $post["AuthorId"], $post["Title"], $post["Body"], $post["Timestamp"], $getFullNameAuthor->getFullName(), $getSumVote->getTotalVote(), $nbAnswers['nbAnswers'] );
        return $result;
    }

    public static function sum_of_questions_by_user($userId){
        $query = self::execute("SELECT count(*) as nbQuestions from post where title !='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbQuestion = $data['nbQuestions'];
    }
    public static function sum_of_answers_by_user($userId){
        $query = self::execute("SELECT count(*) as nbAnswers from post where title ='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbAnswers = $data['nbAnswers'];
    }

    public function create_post(){
        $query = self::execute("INSERT INTO post(AuthorId, Title, Body) values(:AuthorId, :Title, :Body)", 
                                array('AuthorId'=>$this->AuthorId, 'Title'=>$this->Title, 'Body'=>$this->Body));    
    }
    

}

?>