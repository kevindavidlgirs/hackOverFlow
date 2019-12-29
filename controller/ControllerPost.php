<?php

require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerPost extends Controller{
//si l'utilisateur est conecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() {
        if ($this->user_logged()) {
            (new View("index"))->show(array("posts"=> Post::get_posts()));
        } else {
            (new View("index"))->show(array("posts"=> Post::get_posts()));
        }
    }
}

?>