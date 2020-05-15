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
    private $role;

    public function __construct($userId, $username, $hashed_password, $fullname, $email, $role = null) {
        $this->userId = $userId;
        $this->username = $username;
        $this->hashed_password = $hashed_password;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->role = $role;
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

    public function isAdmin(){
        return $this->role == "admin";
    }
    
    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son userName
    public static function get_user_by_userName($username) {
        $query = self::execute("SELECT * FROM user WHERE username = :username", array("username"=>$username));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data['Role']);
        }
    }

    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son fullname
    public static function get_user_by_fullName($fullname) {
        $query = self::execute("SELECT * FROM user WHERE fullname = :fullname", array("fullname"=>$fullname));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data['Role']);
        }
    }
        
    //Recherche à voir si un utilisateur existe déjà dans BD sur base de son email
    public static function get_user_by_email($email) {
        $query = self::execute("SELECT * FROM user WHERE email = :email", array("email"=>$email));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data['UserId'], $data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data['Role']);
        }
    }

    //Récupère un objet user à partir d'un authorId provenant d'un post.
    public static function get_user_by_id($userId) {
        $query = self::execute("SELECT * FROM user WHERE UserId =:Id", array("Id" => $userId));
        $data = $query->fetch();
        return $results = new User($data['UserId'], $data['UserName'], $data['Password'], $data['FullName'], $data['Email'], $data['Role']);
       
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

    public function get_sum_questions(){
        return $result = Question::nbQuestions_by_userId($this->userId);    
    }

    public function get_sum_answers(){
        return $result  = Answer::nbAnswers_by_userId($this->userId);
    }

    public function get_sum_comments(){
        return $result = Comment::nbComments_by_userId($this->userId);
    }

    public function save() {
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

    public static function get_user_stats_as_json($number, $time){
        $query = self::execute("SELECT U.username userName, (ifnull(t1.s, 0) + ifnull(t2.s2, 0)) sumActions FROM 
                                (select authorId, count(*) s from post where Timestamp >= (NOW() - INTERVAL ".$number." ".$time.") group by(authorId)) t1 
                                LEFT JOIN 
                                (select userId, count(*) s2 from comment where Timestamp >= (NOW() - INTERVAL ".$number." ".$time.") group by(userId)) 
                                t2 ON (t1.authorId = t2.userId), user u where u.userId = t1.authorId order by ((ifnull(t1.s, 0) + ifnull(t2.s2, 0))) DESC",array());
        $data = $query->fetchAll();
        $limit = Configuration::get("member_stats_limit");
        if(sizeof($data) < $limit)
            $limit = sizeof($data);
        $str = "";
        for($i = 0; $i < $limit; ++$i){
            $userName = json_encode($data[$i]['userName']);
            $sumActions = json_encode($data[$i]['sumActions']);
            $str .="{\"userName\":$userName,\"sumActions\":$sumActions},";   
        }
        if($str !== "")
            $str = substr($str,0,strlen($str)-1);
        return "[$str]";
    }

    public static function get_user_activity_as_json($number, $time, $user){
        $query = self::execute("select title ttl,timestamp, 'question' as type from post, user where AuthorId = UserId and userName = :user and title!='' and Timestamp >= (NOW() - INTERVAL ".$number." ".$time.")
                                UNION
                                select body as ttl, timestamp, 'reponse' as type from post, user where AuthorId = UserId and userName =  :user and (title='' or title is null) and Timestamp >= (NOW() - INTERVAL ".$number." ".$time.")
                                UNION
                                select body as ttl, timestamp, 'comment' as type from comment c, user u where c.UserId=u.UserId and userName = :user and Timestamp >= (NOW() - INTERVAL ".$number." ".$time.")
                                order by Timestamp desc", array("user" => $user));
        $data = $query->fetchAll();
        $str = "";
        $user = json_encode($user);
        for($i = 0; $i < sizeof($data); ++$i){
            $title = json_encode($data[$i]['ttl']);
            $timestamp = json_encode(Utils::time_elapsed_string($data[$i]['timestamp']));
            $type = json_encode($data[$i]['type']);
            $str .= "{\"user\":$user,\"title\":$title,\"timestamp\":$timestamp,\"type\":$type},";
        }
        if($str !== "")
            $str = substr($str,0,strlen($str)-1);
        return "[$str]";
    }

    
}



















