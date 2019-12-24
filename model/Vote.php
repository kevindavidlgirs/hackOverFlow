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

}


?>