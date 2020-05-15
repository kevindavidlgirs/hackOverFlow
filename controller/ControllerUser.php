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
                    $user->save(); 
                    $this->log_user(User::get_user_by_userName($username));
                }else{
                    $user = null;
                }
            }
            (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm, 
                                             "fullname" => $fullname, "email" => $email,"user" => $user, "errors" => $errors));   

            }  
    }

    //profil de l'utilisateur connecté 
    public function profile() {
        $user = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_false();
        if($this->user_logged() && !isset($_GET["param1"])){
            (new View("profile"))->show(array("user" => $user, "profile" => $user));
        }else if (isset($_GET["param1"])) {
            $profile = User::get_user_by_id($_GET["param1"]);
            if(strlen($profile->getUserName())> 0) {
                (new View("profile"))->show(array("user" => $user, "profile" => $profile));    
            }else{
                $this->redirect();
            }
        }else{
            $this->redirect();
        }
        
    }

    public function stats(){
        $user = null;
        if(self::get_user_or_false())
            $user = self::get_user_or_false();   
        (new View("stats"))->show(array("user" => $user));   
    }

    public function get_stats_service(){
        if(isset($_GET['param1']) && is_numeric($_GET['param1']) && isset($_GET['param2']) && ctype_alpha($_GET['param2'])){
            $number = $_GET['param1'];
            $time = $_GET['param2'];
            if(($number > 0 && $number < 100) && ($time === 'day' || $time === 'week' || $time === 'month' || $time === 'year')){
                echo User::get_user_stats_as_json($number, $time);    
            }
        }
    }

    public function get_details_activity_service(){
        if(isset($_GET['param1']) && is_numeric($_GET['param1']) && isset($_GET['param2']) && ctype_alpha($_GET['param2']) && isset($_GET['param3']) && ctype_alpha($_GET['param3'])){
            $number = $_GET['param1'];
            $time = $_GET['param2'];
            $user = $_GET['param3'];
            if(($number > 0 && $number < 100) && ($time === 'day' || $time === 'week' || $time === 'month' || $time === 'year')){
                if(User::get_user_by_userName($user)){
                    echo User::get_user_activity_as_json($number, $time, $user);
                }  
            }
        }
    }
}
