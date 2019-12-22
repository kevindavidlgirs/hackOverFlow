<?php

require_once 'model/Member.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {
    
    const UPLOAD_ERR_OK = 0;

    //gestion de la connexion d'un utilisateur
    public function login() {
        $username = '';
        $password = '';
        $errors = [];
        if (isset($_POST['username']) && isset($_POST['password'])) { //note : pourraient contenir
        //des chaînes vides
            $username = $_POST['username'];
            $password = $_POST['password'];
            $errors = Member::validate_login($username, $password);
            if (empty($errors)) {
                $this->log_user(Member::get_member_by_userNameOrFullName($username));
            }
        }
        (new View("login"))->show(array("pseudo" => $username, "password" => $password, "errors" => $errors));
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
            $errors = Member::validate_unicity($username, $fullname, $email);
            $errors = array_merge($errors, $member->validate());
            $errors = array_merge($errors, Member::validate_passwords($password, $password_confirm));

            if (count($errors) == 0) { 
                $member->update(); //sauve l'utilisateur
               // $this->log_user(Member::get_member_by_pseudo($pseudo));
            }
        }
        (new View("signup"))->show(array("username" => $username, "password" => $password, "password_confirm" => $password_confirm, "fullname" => $fullname, "email" => $email, "errors" => $errors));   
    
    }
    //gestion de l'édition du profil
    public function edit_profile() {
        $member = $this->get_user_or_redirect();
        $errors = [];
        $success = "";

        // Il est nécessaire de vérifier le statut de l'erreur car, dans le cas où on fait un submit
        // sans avoir choisi une image, $_FILES['image'] est "set", mais le statut 'error' est à 4 (UPLOAD_ERR_NO_FILE).
        if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            $errors = Member::validate_photo($_FILES['image']);
            if (empty($errors)) {
                $saveTo = $member->generate_photo_name($_FILES['image']);
                $oldFileName = $member->picture_path;
                if ($oldFileName && file_exists("upload/" . $oldFileName)) {
                    unlink("upload/" . $oldFileName);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], "upload/$saveTo");
                $member->picture_path = $saveTo;
                $member->update();
                $success = "Your profile has been successfully updated.";
            } 
        }

        if (isset($_POST['profile'])) {
            //le profil peut être vide : pas de soucis.
            $profile = $_POST['profile'];
            $member->profile = $profile;
            $member->update();
            $success = "Your profile has been successfully updated.";
        }
        (new View("edit_profile"))->show(array("member" => $member, "errors" => $errors, "success" => $success));
    }


    //page d'accueil. 
    public function index() {
        $this->profile();
    }

    //profil de l'utilisateur connecté ou donné
    public function profile() {
        $member = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $member = Member::get_member_by_pseudo($_GET["param1"]);
        }
        (new View("profile"))->show(array("member" => $member));
    }
    public function members(){
        $member = $this->get_user_or_redirect();
        $members = Member::get_members();
        (new View("members"))->show(array("members"=> $members));
    }
}
