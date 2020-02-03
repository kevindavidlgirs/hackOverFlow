<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {
    
    const UPLOAD_ERR_OK = 0;

    public function index(){
        $this->redirect();
    }

    //gestion de la connexion d'un utilisateur
    public function login() {
        if($this->user_logged()){
            $this->redirect();
        }else{
            $user = null;
            $username = '';
            $password = '';
            $errors = [];
            if (isset($_POST['username']) && isset($_POST['password'])) { 
                $username = $_POST['username'];
                $password = $_POST['password'];
                $errors = User::validate_login($username, $password);
                if (empty($errors)) {
                    $this->log_user(User::get_user_by_userName($username));
                    $user = get_user_or_redirect();
                }
            }
            (new View("login"))->show(array("username" => $username, "password" => $password, "user" => $user, "errors" => $errors));
        }
    }

    public function signup(){
        if($this->user_logged()){
            $this->redirect();
        }else{
            $user = null;
            $username = '';
            $password = '';
            $password_confirm = ''; 
            $fullname = '';
            $email = '';
            $errors = [];
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['fullname']) && isset($_POST['email'])){
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];
                $fullname = $_POST['fullname'];
                $email = $_POST['email'];
                $user = new User(null, $username, Tools::my_hash($password), $fullname, $email);
                $errors = User::validate_unicity($username, $fullname, $email);
                $errors = array_merge($errors, $user->validate());
                $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

                if (count($errors) == 0) { 
                    $user->update(); //sauve l'utilisateur
                    $this->log_user(User::get_user_by_userName($username));
                }
            }
            (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm, 
                                             "fullname" => $fullname, "email" => $email,"user" => $user, "errors" => $errors));   

            }  
    }

    //profil de l'utilisateur connecté ou donné 'faux'
    public function profile() {
        if($this->user_logged() && !isset($_GET["param1"])){
            $user = $this->get_user_or_redirect();
            (new View("profile"))->show(array("user" => $user));
        }else if ($this->user_logged() && isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $user = User::get_user_by_id($_GET["param1"]);
            if(strlen($user->getUserName())> 0) {
                (new View("profile"))->show(array("user" => $user));    
            }else{
                $this->redirect();
            }
        }else if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $user = User::get_user_by_id($_GET["param1"]);
            if(strlen($user->getUserName())> 0){
                (new View("profile"))->show(array("user" => $user));    
            }else{
                $this->redirect();
            }
        }else{
            $this->redirect();
        }
        
    }

}
