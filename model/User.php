<?php

require_once "framework/Model.php";
require_once("model/Question.php");
require_once("model/Answer.php");


class User extends Model {
    private $userId;
    private $username;
    private $hashed_password;
    private $fullname;
    private $email;

    public function __construct($userId, $username, $hashed_password, $fullname, $email) {
        $this->userId = $userId;
        $this->username = $username;
        $this->hashed_password = $hashed_password;
        $this->fullname = $fullname;
        $this->email = $email;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function getUserName(){
        return $this->username;
    }

    public function getFullName(){
        return $this->fullname;
    }

    public function getEmail(){
        return $this->email;
    }
    
    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son userName
    public static function get_user_by_userName($username) {
        $query = self::execute("SELECT * FROM user WHERE username = :username", array("username"=>$username));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"]);
        }
    }

    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son fullname
    public static function get_user_by_fullName($fullname) {
        $query = self::execute("SELECT * FROM user WHERE fullname = :fullname", array("fullname"=>$fullname));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"]);
        }
    }
        
    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son email
    public static function get_user_by_email($email) {
        $query = self::execute("SELECT * FROM user WHERE email = :email", array("email"=>$email));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"]);
        }
    }

    //Récupère un objet user à partir d'un authorId provenant d'un post.
    public static function get_user_by_id($userId) {
        $query = self::execute("SELECT * FROM user WHERE UserId =:Id", array("Id" => $userId));
        $data = $query->fetch();
        return $results = new User($data['UserId'], $data['UserName'], $data['Password'], $data['FullName'], $data['Email']);
       
    }

    public function get_sum_questions(){
        return $getSumVote = Question::sum_of_questions_by_userId($this->userId);    
    }

    public function get_sum_answers(){
        return $getSumVote  = Answer::sum_of_answers_by_userId($this->userId);
           
    }
    //Devrais-je la nommer autrement ? Du genre : create profile ? (A voir ave l'evolution du projet)
    public function update() {
        self::execute("INSERT INTO user(username,password,fullname,email) VALUES(:username,:password,:fullname,:email)", 
            array("username"=>$this->username, "password"=>$this->hashed_password, "fullname"=>$this->fullname, "email"=>$this->email));
        return $this;
    }

    //Valide que le username, fullname, et email ont bien les longueurs et formats attendus.
    public function validate(){
        $errors = array();
        if (!(isset($this->username) && is_string($this->username) && strlen($this->username) > 0)) {
            $errors['user'] = "username is required.";
        } if (!(isset($this->username) && is_string($this->username) && strlen($this->username) >= 3 && strlen($this->username) <= 16)) {
            $errors['user'] = "username length must be between 3 and 16.";
        } if (!(isset($this->username) && is_string($this->username) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->username))) {
            $errors['user'] = "username must start by a letter and must contain only letters and numbers.";
        } if (!(isset($this->fullname) && is_string($this->fullname) && strlen($this->fullname) > 0)) {
                $errors['name'] = "fullname is required.";
        } if (!(isset($this->fullname) && is_string($this->fullname) && strlen($this->fullname) >= 3 && strlen($this->fullname) <= 30)) {
                $errors['name'] = "fullname length must be between 3 and 16.";
        } if (!(isset($this->username) && is_string($this->username) && preg_match("/^[a-zA-Z ]*$/", $this->fullname))) {
                $errors['name'] = "fullname contain only letters.";
        } if(!(isset($this->email) && strlen($this->email) > 0)){
            $errors['email'] = "email is required.";
        } else if(!preg_match("@^[a-z0-9-._]+\@[a-z0-9-._]{2,}\.[a-z]{2,4}$@", $this->email)){
            $errors['email'] = "invalid email.";
        }
        return $errors;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_login($username, $password) {
        $errors = [];
        $user = User::get_user_by_userName($username);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors['password'] = "Wrong password. Please try again.";
            }
        } else {
            $errors['user'] = "Can't find a member with the pseudo '$username'. Please sign up.";
        }
        return $errors;
    }

    public static function validate_unicity($username, $fullname, $email){
        $errors = [];
        $user = self::get_user_by_userName($username);
        if ($user) {
            $errors['user'] = "This user already exists.";
        }
        $user = self::get_user_by_fullName($fullname);
        if ($user) {
            $errors['name'] = "This fullname already exists.";
        } 
        $user = self::get_user_by_email($email);
        if ($user) {
            $errors['email'] = "This email already exists.";
        }
        return $errors;
    }

    private static function validate_password($password){
        $errors = [];
        if (strlen($password) < 8 || strlen($password) > 30) {
            $errors['password'] = "Password length must be between 8 and 30.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";!:,.=+%£µ$)}{\/?\\-]/", $password))) {
            $errors['password'] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }
    
    public static function validate_passwords($password, $password_confirm){
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors['password_confirm'] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }

    





















    
    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public static function validate_photo($file) {
        $errors = [];
        if (isset($file['name']) && $file['name'] != '') {
            if ($file['error'] == 0) {
                $valid_types = array("image/gif", "image/jpeg", "image/png");
                if (!in_array($_FILES['image']['type'], $valid_types)) {
                    $errors[] = "Unsupported image format : gif, jpg/jpeg or png.";
                }
            } else {
                $errors[] = "Error while uploading file.";
            }
        }
        return $errors;
    }

    //pre : validate_photo($file) returns true
    public function generate_photo_name($file) {
        //note : time() est utilisé pour que la nouvelle image n'aie pas
        //       le meme nom afin d'éviter que le navigateur affiche
        //       une ancienne image présente dans le cache
        if ($_FILES['image']['type'] == "image/gif") {
            $saveTo = $this->pseudo . time() . ".gif";
        } else if ($_FILES['image']['type'] == "image/jpeg") {
            $saveTo = $this->pseudo . time() . ".jpg";
        } else if ($_FILES['image']['type'] == "image/png") {
            $saveTo = $this->pseudo . time() . ".png";
        }
        return $saveTo;
    }
    public function write_message($message) {
        return $message->update();
    }

    public function delete_message($message) {
        return $message->delete($this);
    }

    public function get_messages() {
        return Message::get_messages($this);
    }

    public function get_other_members_and_relationships() {
        $query = self::execute("SELECT pseudo,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE follower=:user and followee=Members.pseudo) as follower,
                     (SELECT count(*) 
                      FROM Follows 
                      WHERE followee=:user and follower=Members.pseudo) as followee
              FROM Members 
              WHERE pseudo <> :user 
              ORDER BY pseudo ASC", array("user" => $this->pseudo));
        return $query->fetchAll();
    }

    public function follow($followee) {
        self::add_follower($this->pseudo, $followee->pseudo);
    }

    public function unfollow($followee) {
        self::delete_follower($this->pseudo, $followee->pseudo);
    }

    private static function add_follower($user, $followee) {
        self::execute("INSERT INTO Follows VALUES (:user,:other)", array("user"=>$user, "other"=>$followee));
        return true;
    }

    private static function delete_follower($user, $followee) {
        self::execute("DELETE FROM Follows WHERE follower = :user AND followee = :other", array("user"=>$user, "other"=>$followee));
        return true;
    }


}
