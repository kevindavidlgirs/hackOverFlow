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

    public function getNbQuestionsAssociees(){
        return $this->nbQuestAssoc;
    }

    public static function getAllTags(){
        $query = self::execute("SELECT t.tagId as TagId, TagName, count(p.tagid) as nbQuestAssoc
                                FROM tag as t, posttag as p WHERE t.tagid = p.tagid 
                                GROUP BY t.tagid", array()); 
        $data = $query->fetchAll();
        $result = [];
        foreach($data as $row){
            $result[] = new Tag($row["TagId"], $row["TagName"], $row['nbQuestAssoc'] );
        }      
        return $result;
    }
}