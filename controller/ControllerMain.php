<?php

require_once 'model/Member.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller{
//si l'utilisateur est conecté, redirige vers son profil.
    //sinon, produit la vue d'accueil.
    public function index() {
        if ($this->user_logged()) {
            $this->redirect("member", "profile");
        } else {
            (new View("index"))->show();
        }
    }

    //gestion de la connexion d'un utilisateur
    public function login() {
        $pseudo = '';
        $password = '';
        $errors = [];
        if (isset($_POST['pseudo']) && isset($_POST['password'])) { //note : pourraient contenir
        //des chaînes vides
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];

            $errors = Member::validate_login($pseudo, $password);
            if (empty($errors)) {
                $this->log_user(Member::get_member_by_pseudo($pseudo));
            }
        }
        (new View("login"))->show(array("pseudo" => $pseudo, "password" => $password, "errors" => $errors));
    }

    public function signup(){
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
            $member = new Member($username, Tools::my_hash($password), $fullname, $email);
            $errors = Member::validate_unicity($username, $fullname);
            $errors = array_merge($errors, $member->validate());
            $errors = array_merge($errors, Member::validate_passwords($password, $password_confirm));

            if (count($errors) == 0) { 
                $member->update(); //sauve l'utilisateur
               // $this->log_user(Member::get_member_by_pseudo($pseudo));
            }
        }
        (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm, "fullname" => $fullname, "email" => $email, "errors" => $errors));   
    
    }

}


?>