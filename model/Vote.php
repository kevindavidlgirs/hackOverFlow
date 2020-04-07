<?php

require_once("framework/Model.php");

class Vote extends Model{
    private $userId;
    private $postId;
    private $upDown;
    private $sumVote;


    public function __construct($UserId, $PostId, $UpDown, $sumVote){
        $this->userId = $UserId;
        $this->postId = $PostId;
        $this->upDown = $UpDown;
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
    public static function get_SumVote($postId){
        $query = self::execute("SELECT SUM(UpDown) as sumVote FROM vote WHERE PostId = :PostId GROUP BY(PostId)", array("PostId" => $postId));
        $data = $query->fetch();
        if($query->rowcount()== 0){
            $data['sumVote'] = 0;     
        }
        return $results = new Vote(null, null, null, $data['sumVote']);
    }

    public static function get_upDown($userId, $postId){
        $query = self::execute("SELECT UpDown FROM vote WHERE UserId= :UserId and PostId = :PostId", array("UserId"=>$userId, "PostId"=>$postId));
        $data = $query->fetch();
        return $data['UpDown'];
    }

    public function update_vote($value){
        $query = self::execute("SELECT * FROM vote WHERE PostId = :PostId and UserId= :UserId", array("PostId"=>$this->postId, "UserId"=>$this->userId));
        $data = $query->fetch();
        if($query->rowCount() == 0){
            self::execute("INSERT INTO vote(UserId, PostId, UpDown) VALUES(:UserId, :PostId, :UpDown)", array("UserId"=>$this->userId, "PostId"=>$this->postId, "UpDown"=>$value));    
        }else{
            if($data['UpDown'] === $value || $data['UpDown'] !== $value){
                self::execute("DELETE FROM vote WHERE UserId=:UserId AND PostId=:PostId", array("PostId"=>$this->postId, "UserId"=>$this->userId));
            }else{
                self::execute("UPDATE vote SET UpDown = :UpDown WHERE UserId=:UserId AND PostId=:PostId", array("UserId"=>$this->userId,"PostId"=>$this->postId, "UpDown"=>$value));
           }
        }
    }

    public function delete(){
        $query = self::execute("DELETE FROM vote WHERE PostId = :PostId", array("PostId"=>$this->postId));
        return true;    
    }
}


?>