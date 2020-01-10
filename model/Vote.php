<?php

require_once("framework/Model.php");

class Vote extends Model{
    private $UserId;
    private $PostId;
    private $UpDown;
    private $sumVote;


    public function __construct($UserId, $PostId, $UpDown, $sumVote){
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        $this->UpDown = $UpDown;
        $this->sumVote = $sumVote;
    }

    public function getUserId(){
        return $this->UserId;
    }
    public function getPostId(){
        return $this->PostId;
    }
    public function getUpDown(){
        return $this->UpDown;
    }
    public function getTotalVote(){
        return $this->sumVote;
    }

    //Fait la somme des votes à partir d'un PostId provenant d'un post.
    public static function get_SumVote($PostId){
        $query = self::execute("SELECT SUM(UpDown) as sumVote FROM vote WHERE PostId = :PostId GROUP BY(PostId)", array("PostId" => $PostId));
        $data = $query->fetch();
        return $results = new Vote(null, null, null, $data['sumVote']);
    }

    public static function get_nbVote($PostId){
        $query = self::execute("SELECT count(*) as nbVote FROM vote WHERE PostId = :PostId", array("PostId" => $PostId));
        $data = $query->fetch();
        return $data['nbVote'];
    }

    public function update_vote($userId, $postId,  $value){
        $query = self::execute("SELECT * FROM vote WHERE PostId = :PostId and UserId= :UserId", array("PostId"=>$postId, "UserId"=>$userId));
        $data = $query->fetch();
        if($query->rowCount() == 0){
            self::execute("INSERT INTO vote(UserId, PostId, UpDown) VALUES(:UserId, :PostId, :UpDown)", array("UserId"=>$userId, "PostId"=>$postId, "UpDown"=>$value));    
        }else{
            if($data['UpDown'] === $value){
                self::execute("DELETE FROM vote WHERE UserId=:UserId AND PostId=:PostId", array("PostId"=>$postId, "UserId"=>$userId));
            }else{
                self::execute("UPDATE vote SET UpDown = :UpDown WHERE UserId=:UserId AND PostId=:PostId", array("UserId"=>$userId, "PostId"=>$postId, "UpDown"=>$value));
                
                //Pourquoi il n'accepte pas ces query ?
                //self::execute("DELETE FROM vote WHERE UserId=:UserId AND PostId=:PostId", array("PostId"=>$postId, "UserId"=>$userId));
                //self::execute("INSERT INTO vote(UserId, PostId, UpDown) VALUES(UserId=:UserId, PostId=:PostId, UpDown=:UpDown)", array("UserId"=>$userId, "PostId"=>$postId, "UpDown"=>$value));    
            }
        }
    }

    public static function get_upDown($userId, $postId){
        $query = self::execute("SELECT UpDown FROM vote WHERE UserId= :UserId and PostId = :PostId", array("UserId"=>$userId, "PostId"=>$postId));
        $data = $query->fetch();
        return $data['UpDown'];
    }

    public function delete($postId){
        $query = self::execute("DELETE FROM vote WHERE PostId = :PostId", array("PostId"=>$postId));
        return true;    
    }
}


?>