<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("model/Post.php");
require_once("model/User.php");
require_once("model/Vote.php");
require_once("model/Answer.php");


class Question extends Post {
    private $title;
    private $timestamp;
    private $nbAnswers;
    private $answers;
    private $acceptedAnswerId;
    
    public function __construct($postId, $authorId, $title, $body, $timestamp, $fullNameAuthor, $totalVote, $nbAnswers, $acceptedAnswerId, $answers){
        $this->postId = $postId;
        $this->body = $body;
        $this->authorId = $authorId;
        $this->fullNameAuthor = $fullNameAuthor;
        $this->title = $title;
        $this->timestamp = $timestamp;
        $this->totalVote = $totalVote;
        $this->nbAnswers = $nbAnswers;
        $this->acceptedAnswerId = $acceptedAnswerId;
        $this->answers = $answers;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }
    
    public function getTitle(){
        return $this->title;
    }

    public function getNbAnswers(){
        return $this->nbAnswers;
    }

    public function getAnswers(){
        return $this->answers;
    }

    public function getAcceptedAnswerId(){
        return $this->acceptedAnswerId;
    }

    //Récupère un post grace à son id
    public static function get_question($postId){
        $query = self::execute("SELECT * FROM post WHERE PostId = :PostId and title != \"\" ", array("PostId"=>$postId));
        $post = $query->fetch();
        if($query->rowCount() == 0){
            return false;
        }else{
            return $result = new Question($post["PostId"], $post["AuthorId"], Tools::sanitize($post["Title"]), $post["Body"], $post["Timestamp"], 
                                    User::get_user_by_id($post["AuthorId"])->getFullName(), Vote::get_SumVote($post["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($postId)['nbAnswers'], $post["AcceptedAnswerId"], Answer::get_answers($postId));
        }
            
    }

    //Permet de récupérer tous les posts, le nom de l'auteur de chaque post, la somme des votes pour chaque post,  
    //et le nombre de réponse de chaque post.
    public static function get_questions($decode){
        if($decode === null){
            $query = self::execute("SELECT * FROM post WHERE title !='' ORDER BY timestamp DESC ", array());
        }else{
            $query = self::execute("SELECT distinct PostId, AuthorId, Title, Body, Timestamp FROM post, user 
                                        WHERE UserId=authorid  and ((UserName like '%$decode%' or FullName like '%$decode%' or Email like '%$decode%' or Title like '%$decode%' or Body like '%$decode%') 
                                                or postid in (select ParentId from post where (UserName like '%$decode%' or FullName like '%$decode%' or Email like '%$decode%' or Title like '%$decode%' or Body like '%$decode%') 
                                                and Title = '')) and Title !='' ORDER BY timestamp DESC ", array());     
        }
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Question($row["PostId"], $row["AuthorId"], Tools::sanitize($row["Title"]), self::remove_markdown($row["Body"]), 
                                    $row["Timestamp"], User::get_user_by_id($row["AuthorId"])->getFullName(), Vote::get_SumVote($row["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($row["PostId"])['nbAnswers'], null, null, null);
        }
        return $results;
    }

    public static function get_questions_unanswered($decode){
        if($decode === null){
            $query = self::execute("SELECT * FROM post WHERE title !='' and AcceptedAnswerId IS NULL ORDER BY timestamp DESC ", array());
        }else{
            $query = self::execute("SELECT distinct PostId, AuthorId, Title, Body, Timestamp FROM post WHERE AcceptedAnswerId IS NULL 
                                        and ((Title like '%$decode%' or Body like '%$decode%') 
                                            or postid in (select ParentId from post where (Title like '%$decode%' or Body like '%$decode%') 
                                                and Title = '')) and Title != '' ORDER BY timestamp DESC", array());    
        }
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Question($row["PostId"], $row["AuthorId"], Tools::sanitize($row["Title"]), Tools::sanitize(self::remove_markdown($row["Body"])), 
                                    $row["Timestamp"], User::get_user_by_id($row["AuthorId"])->getFullName(), Vote::get_SumVote($row["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($row["PostId"])['nbAnswers'], null, null);
        }
        return $results;
            
    }

    public static function get_questions_by_votes($decode){
        if($decode === null){
            $query = self::execute("SELECT post.*, max_score
                                    FROM post, (
                                        SELECT parentid, max(score) max_score
                                        FROM (
                                            SELECT post.postid, ifnull(post.parentid, post.postid) parentid, ifnull(sum(vote.updown), 0) score
                                            FROM post LEFT JOIN vote ON vote.postid = post.postid 
                                            GROUP BY post.postid
                                        ) AS tbl1
                                        GROUP by parentid
                                    ) AS q1
                                    WHERE post.postid = q1.parentid
                                    ORDER BY q1.max_score DESC, timestamp DESC", array());
        }else{
            $query = self::execute("SELECT post.*, max_score
                                    FROM post, (
                                        SELECT parentid, max(score) max_score
                                        FROM (
                                            SELECT post.postid, ifnull(post.parentid, post.postid) parentid, ifnull(sum(vote.updown), 0) score
                                            FROM post LEFT JOIN vote ON vote.postid = post.postid WHERE (Title like \"%$decode%\" or Body like \"%$decode%\")
                                            GROUP BY post.postid
                                        ) AS tbl1
                                        GROUP by parentid
                                    ) AS q1
                                    WHERE post.postid = q1.parentid
                                    ORDER BY q1.max_score DESC, timestamp DESC", array());    
        }
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){                                     
            $results[] = new Question($row["PostId"], $row["AuthorId"], Tools::sanitize($row["Title"]), self::remove_markdown($row["Body"]), 
                                    $row["Timestamp"], User::get_user_by_id($row["AuthorId"])->getFullName(), Vote::get_SumVote($row["PostId"])->getTotalVote(), 
                                        Answer::get_nbAnswers($row["PostId"])['nbAnswers'], null, null);
        }
        return $results;    
    }

    //Fait la somme des questions pour le profile de l'utilisateur
    public static function sum_of_questions_by_userId($userId){
        $query = self::execute("SELECT count(*) as nbQuestions from post where title !='' and AuthorId = :AuthorId", array("AuthorId"=>$userId));
        $data = $query->fetch();
        return $nbQuestion = $data['nbQuestions'];
    }
    
    //Crée un post en bd. Attention méthode à utiliser
    public function create_question(){
        $query = self::execute("INSERT INTO post(AuthorId, Title, Body) values(:AuthorId, :Title, :Body)", 
                                array('AuthorId'=>$this->authorId, 'Title'=>$this->title, 'Body'=>$this->body));    
    }

    
    public static function get_upDown_vote($userId, $postId){
        return $result = Vote::get_upDown($userId, $postId);    
    }

    public static function validate($question){
        $errors = [];
        if(strlen($question->getTitle()) < 10){
            $errors['title'] = "The length of the title must be greater than or equal to 10 characters"; 
        }
        if(strlen($question->getBody()) < 30){
            $errors['body'] = "The length of the body must be greater than or equal to 30 characters"; 
        }
        return $errors;
    }

    public static function valide_existence($question){
        $error = [];
        if($question === 'null'){
            $error = 'la question n\'existe pas ';
        } 
        return $error;
    }

    public function delete($postId){
        if(Vote::delete($postId)){
            self::execute("DELETE FROM post WHERE PostId = :PostId", array("PostId"=>$postId));
            return true;
        }
        
    }

    public function accept_answer($postId, $answerId){
        self::execute("UPDATE post SET AcceptedAnswerId = :AcceptedAnswerId  WHERE PostId = :PostId", array("PostId"=>$postId,"AcceptedAnswerId"=> $answerId));
        return true;
    }
    
    public function delete_accepted_answer($postId){
        self::execute("UPDATE post SET AcceptedAnswerId = NULL WHERE PostId = :PostId", array("PostId"=>$postId));  
        return true;  
    }

    public function set_post(){
        self::execute("UPDATE post SET Title = :Title, Body = :Body WHERE PostId = :PostId", array("PostId"=>$this->postId,"Title"=>$this->title, "Body"=>$this->body));
        return true;
    }
}

?>