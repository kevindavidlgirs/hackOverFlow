<?php

require_once("framework/Model.php");

class Tag extends Model{
    
    private $tagId;
    private $tagName;
    private $nbQuestAssoc;

    public function __construct($tagId, $tagName, $nbQuestAssoc){
        $this->tagId = $tagId;
        $this->tagName = $tagName;
        $this->nbQuestAssoc = $nbQuestAssoc;
    }

    public function getTagId(){
        return $this->tagId;
    }

    public function getTagName(){
        return $this->tagName;
    }

    public function getNbAssociatedQuestions(){
        return $this->nbQuestAssoc;
    }

    public static function getAllTags(){
        $query = self::execute("SELECT t.tagId as TagId, TagName, ifnull(count(p.tagid), 0) nbQuestAssoc 
                                FROM tag as t left join posttag as p on t.tagid = p.tagid GROUP BY t.tagid", array()); 
        $data = $query->fetchAll();
        $result = [];
        foreach($data as $row){
            $result[] = new Tag($row["TagId"], $row["TagName"], $row['nbQuestAssoc'] );
        }      
        return $result;
    }

    //C'est avec la table "posttag" que je compte les tags, j'aurai pu mettre cette fonction dans "Question.php"
    //vu que "posttag" est partagé ente "question.php" et "tag.php".
    public static function getNbTags($postId){
        $query = self::execute("SELECT count(*) as nbTags from posttag where PostId = :PostId", array("PostId" => $postId));
        $data = $query->fetch();
        return $result = $data['nbTags'];
    }

    public static function get_tags_by_postId($postId){
        $query = self::execute("SELECT * FROM posttag p, tag t WHERE t.TagId = p.TagId AND p.PostId = :PostId", array("PostId" => $postId));
        $data = $query->fetchAll();
        $results = [];
        foreach($data as $row){
            $results[] = new Tag($row['TagId'], $row['TagName'], null);
        }
        return $results;
    }
    public static function get_tag_by_name($tagName){
        $query = self::execute("SELECT * FROM tag WHERE tagName = :tagName", array("tagName" => $tagName));
        $data = $query->fetch();
        if($query->rowCount() == 0){
            return false;
        }else{
            return $tag = new Tag($data['TagId'], $data['TagName'], null);
        }
    }

    public static function get_tag_by_id($id){
        $query = self::execute("SELECT * FROM tag WHERE tagId = :tagId", array("tagId" => $id));
        $data = $query->fetch();
        if($query->rowCount() == 0){
            return false;
        }else{
            return $tag = new Tag($data['TagId'], $data['TagName'], null);
        }
    }

    public static function testExistenceByName($tagName){
        return self::get_tag_by_name($tagName);
    }

    public static function testExistenceById($tagId){
        return self::get_tag_by_id($tagId);
    }

    public static function validate_unicity($tagName, $id){
        $error = [];
        $tag = self::get_tag_by_name($tagName);
        if ($tag) {
            $error['unicity'] = "This tag already exists.";
            $error['tagId'] = $id;
            $error['tagName'] = $tagName;
        }
        return $error;
    }

    public function validate(){
        $error = [];
        if (!(isset($this->tagName) && is_string($this->tagName) && strlen($this->tagName) > 0)) {
            $error['tagName'] = "the tag is required.";
            $error['tagId'] = $this->tagId;
        }else if(strlen($this->tagName) > 10){
            $error['tagName'] = "the tag must have less than 10 letters.";
            $error['tagId'] = $this->tagId;
        }
        return $error;
    }

    public function update(){
        self::execute("UPDATE tag SET tagName = :tagName WHERE TagId = :TagId", array("tagName"=>$this->tagName, "TagId"=>$this->tagId));
        return true;
    }

    public function delete(){
        self::execute("DELETE FROM posttag WHERE tagId = :tagId", array("tagId"=>$this->tagId));
        self::execute("DELETE FROM tag WHERE tagId = :tagId", array("tagId"=>$this->tagId));
        return true;
    }

    public function save(){
        self::execute("INSERT INTO tag(TagName) VALUES(:TagName)", array("TagName" => $this->tagName));
        return true;    
    }

    
}