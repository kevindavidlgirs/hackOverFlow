<?php

require_once 'model/Tag.php';
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';


class ControllerTag extends Controller{  
    
    public function index(){
        
        $user = null;
        $error = [];
        if(self::get_user_or_false()){
           $user = self::get_user_or_redirect();
        }
        (new View("browseTags"))->show(array("tags" => Tag::getAllTags(), "user" => $user, "error" => $error));    
    }

    //ATTENTION MANQUE DE SENSE
    public function edit(){
        if(self::get_user_or_false()){
            $user = self::get_user_or_redirect();
            if(isset($_POST['edit']) && isset($_POST['tagName']) && isset($_GET['param1']) && $user->isAdmin()){
                $tagName = $_POST['tagName'];
                $id = $_GET['param1'];  
                $error = Tag::validate_unicity($tagName, $id);
                if(count($error) == 0){
                    $tag = new Tag($id, ucfirst($tagName), null);
                    $error = $tag->validate();
                    if(count($error) == 0){
                        $tag->update();
                        self::redirect("tag", "index"); 
                    }else{
                        (new View("browseTags"))->show(array("tags" => Tag::getAllTags(), "user" => $user, "error" => $error));    
                    }
                }else{
                    (new View("browseTags"))->show(array("tags" => Tag::getAllTags(), "user" => $user, "error" => $error));    
                }
            }else{
                self::redirect("tag"); 
            }
        }else{
            self::redirect("user", "login"); 
        }
    }


    public function delete(){
        if(self::get_user_or_false()){
            $user = self::get_user_or_redirect();
            if(isset($_GET['param1']) && $user->isAdmin()){
                $id = $_GET['param1'];
                if(isset($_POST['cancel'])){
                    self::redirect("tag");     
                }else if(isset($_POST['delete'])){
                    (new View("deleteTag"))->show(array("tag"=>Tag::get_tag_by_id($id), "user"=>$user));
                }else if(isset($_POST['delete_confirmation'])){
                    self::delete_tag($id);
                    self::redirect("tag");     
                }else{
                    self::redirect("tag");     
                }
            }else{
                self::redirect("tag");     
            }
        }else{
            self::redirect("user", "login");     
        }
    }

    private function delete_tag($id){
        $tag = new Tag($id, null, null);
        if($tag->delete_and_assoc()){
            self::redirect("tag");     
        }
    }

    public function create(){
        if(self::get_user_or_false()){
            $user = self::get_user_or_redirect();
            if(isset($_POST['newTag']) && $user->isAdmin()){
                $tagName = $_POST['newTag'];
                $tag = new Tag(null, ucfirst($tagName), null);   
                $error = Tag::validate_unicity($tagName, null);
                if(count($error) == 0){
                    $error = $tag->validate();
                    if(count($error) == 0){
                        $tag->save();
                        self::redirect("tag"); 
                    }else{
                        (new View("browseTags"))->show(array("tags" => Tag::getAllTags(), "user" => $user, "error" => $error));        
                    }
                }else{
                    (new View("browseTags"))->show(array("tags" => Tag::getAllTags(), "user" => $user, "error" => $error));    
                }        
            }else{
                self::redirect("tag");         
            }
        }else{
            self::redirect("user", "login");     
        }
    
    }

}

?>