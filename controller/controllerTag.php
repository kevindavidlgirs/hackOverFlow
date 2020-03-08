<?php

require_once 'model/Tag.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class controllerTag extends controller{

    public function index(){
        $user = null;
        if(self::get_user_or_false())
        $user = self::get_user_or_redirect();
        $tags = Tag::getAllTags();
        (new View("browseTags"))->show(array("tags" => $tags, "user" => $user));    
    }
}