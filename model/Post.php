<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("framework/Model.php");

abstract class Post extends Model {
    //"protected" mauvaise pratique ? (impossible d'utiliser "private" dans ce cas...)
    protected $postId;
    protected $body;
    protected $authorId;
    protected $fullNameAuthor;
    protected $totalVote;
    //"protected" mauvaise pratique ? (impossible d'utiliser "private" dans ce cas...)

    public function getBodyMarkedown(){
        return self::markdown($this->body);
    }

    public function getBodyMarkedownRemoved(){
        return self::remove_markdown($this->body);    
    }
    
    public function getTotalVote(){
        return $this->totalVote;
    }

    public function getPostId(){
        return $this->postId;
    }

    public function getFullNameAuthor(){
        return $this->fullNameAuthor;
    }

    public function getBody(){
        return $this->body;
    }

    public function getAuthorId(){
        return $this->authorId;
    }

    abstract public function set_post();

    //Ajouter les méthodes valide, delete et autres.
    protected static function markdown($markedown){
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true); 
        return $html = $Parsedown->text($markedown);  
    }
    
    protected static function remove_markdown($value){
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
	    $html = $parsedown->text($value);
        return strip_tags($html);
    }
}
?>