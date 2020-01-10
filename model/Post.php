<?php
require_once("lib/parsedown-1.7.3/Parsedown.php");
require_once("framework/Model.php");

abstract class Post extends Model {

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