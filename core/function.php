<?php
//database connexion informations 
define("SERVER_NAME", "localhost");
define("DB_NAME", "instantkwick");
define("DB_USER", "diokel");
define("DB_PASSWORD", "  diokel");

//errors return by some functions
define("ERROR_0", "ok");
define("ERROR_1", "internal error");
define("ERROR_2", "login not available");
define("ERROR_3", "email not available");
define("ERROR_4", "problème d'insertion");
define("ERROR_5", "not connected");

//number of argument need for requesting
define("ARGS_ERROR", "invalide arguments");
define("SIGNUP_ARGS", 4);
define("LOGIN_ARGS", 3);
define("LOGED_ARGS", 2);
define("SENDTO_ARGS", 3);
define("LOGOUT_ARGS", 3);
define("LISTPOST_ARGS", 4);
define("FIND_ARGS", 2);
define("END_CONNEXION", 'expired');

//to connect the database 
function connexion(){

   try{
      $error = new PDO('mysql:host='.SERVER_NAME.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE	=>PDO::ERRMODE_EXCEPTION));
   }
   catch(PDOException $e){
      $error = ERROR_1;
   }

   return $error;

}

//this function allow us to create a new user
function signup($url){

	if(count($url) != SIGNUP_ARGS){
      return $error = json_encode([
         'status'  => 'failed',
         'message' => 'check your settings'
      ]);
   }
   elseif ($url[3] == '') {
      return $error = json_encode([
         'status'  => 'failed',
         'message' => 'no field should be empty'
      ]);
   }
   else
      return $error = create_new_user($url[1], $url[2], $url[3]);

}

//🔥 🔥 🔥 🔥 🔥 🔥 🔥 🔥 
function error($error){
	echo $error;
}

//this funtion check if login is available or not.
function check_login($connexion, $login){
      
   $requeste = $connexion->prepare("SELECT * FROM Users WHERE user_login = ?");
   $requeste->execute(array($login));
   $is_A_User = $requeste->fetch();

   if($is_A_User != 0){
      $error = ERROR_2;
   }
   else{
      $error = ERROR_0;
   }
   
   return $error;

}

//this fuction check the email if it is not allready use.
function check_email($connexion, $email){
      
   $requeste = $connexion->prepare("SELECT * FROM Users WHERE user_email = ?");
   $requeste->execute(array($email));
   $is_A_User = $requeste->fetch();

   if($is_A_User != 0){
      $error = ERROR_3;
   }
   else{
      $error = ERROR_0;
   }
   
   return $error;

}

//
function create_new_user($login, $email, $password){

   $connexion = connexion();

   if($connexion != ERROR_1){

      $error1 = check_login($connexion, $login);
      $error2 = check_email($connexion, $email);

      if($error1 == ERROR_2){
         return $error = json_encode([
            'status'  => ERROR_2,
            'message' => 'this user name already exist'
         ]);
      }
      elseif($error2 == ERROR_3){
         return $error = json_encode([
            'status'  => ERROR_3,
            'message' => 'this email already exists'
         ]);
      }
      else {
         $pass = password_hash($password, PASSWORD_DEFAULT);
         $requeste = $connexion->prepare("INSERT INTO Users (user_login, user_email, user_password) VALUES (?, ?, ?)");
         $requeste->execute(array($login, $email, $pass));
            
         return $error = json_encode([
            'status'  => ERROR_0,
            'message' => 'New user succesfully added'
         ]);
      }
      
   }
  
   return $error = json_encode([
      'status'  => 'failed',
      'message' => 'you can try later, there is an internal error. we are sorry for the inconvenience'
   ]);

}

//this function check the user name and password combinaison
function signin($url){

   $connexion = connexion();

   $requeste = $connexion->prepare("SELECT user_id, user_password FROM Users WHERE user_login = ? OR user_email = ?");
   $requeste->execute(array($url[1], $url[1]));
   $is_A_User = $requeste->fetch();

   if($is_A_User > 0){

      $password = $is_A_User[1];
      $id = $is_A_User[0];

      $requeste = $connexion->prepare("SELECT * FROM Connexion WHERE connected_user = ? AND token != 'expired'");
      $requeste->execute(array($id));
      $is_connected = $requeste->fetch();

      if($is_connected > 0){

         if(password_verify($url[2], $password)){

            $token = $is_connected[4];

            return $error = json_encode([
               'status' => 'allready connected',
               'id' => $id,
               'token' => $token
            ]);
         }
         else{
            return $error = json_encode([
               'status' => 'failed',
               'message' => 'bad password'
            ]);
         }

      }
      else{

         if(password_verify($url[2], $password)){
            
            $token = uniqid($url[1]);
            
            $connexion = connexion();
            $requeste = $connexion->prepare("INSERT INTO Connexion (connexion_start, user_ip_address, token, connected_user) VALUES (?, ?, ?, ?)");
            $requeste->execute(array(time(), $_SERVER['REMOTE_ADDR'], $token, $id));
            
            return $error = json_encode([
               'status'  => 'ok',
               'id' => $id,
               'token' => $token,
               'message' => 'successfully authenticate'
            ]);

         }
         return $error = json_encode([
            'status'  => 'failed',
            'message' => 'incorrect password'
         ]);

      }

   }

   return $error = json_encode([
      'status'  => 'failed',
      'message' => 'unknow user'
   ]);
   
}

//this fuction get user logout
function logout($url){

   $connexion = connexion();
   $requeste = $connexion->prepare("SELECT count(*) FROM Connexion WHERE connected_user = ? AND token = ? AND connexion_end IS NULL");
   $requeste->execute(array($url[1], $url[2]));

   $resultat = $requeste->fetch();

   if($resultat[0] > 0){

      $requeste = $connexion->prepare("UPDATE Connexion SET connexion_end = ?, token = ? WHERE connected_user = ?");
      $requeste->execute(array(time(), END_CONNEXION, $url[1]));

      return $error = json_encode([
         'status'  => 'ok',
         'message' => 'successfully disconnected'
      ]);

   }
   return $error = json_encode([
      'status'  => 'failed',
      'message' => 'pas la!'
   ]);
}

//this function list the loged user
function loged($url){

   $connexion = connexion();
   $requeste = $connexion->prepare("SELECT * FROM Users WHERE user_id IN (SELECT connected_user FROM Connexion WHERE connexion_end IS NULL AND token != 'expired') ORDER BY user_login");

   $requeste->execute();

   $resultat = $requeste->fetchAll();

   return json_encode([
      'status' => 'ok',
      'message' => 'voici la liste',
      'online' => $resultat
   ]);
   
}

//this function list all messages between someone and someone else
function listpost($url){

   $error = connected($url[2], $url[1]);

   if($error == ERROR_5){
      return $error = json_encode([
         'status' => 'failed',
         'message' => ERROR_5
      ]);
   }
   else{
      //here i liste all messages between the two users
      return $error = showdiscution($url[1], $url[3]);
   }
   
}

//
function connected($token, $id){

   $connexion = connexion();
   $requeste = $connexion->prepare("SELECT * FROM Connexion WHERE connexion_end IS NULL AND token = ? AND connected_user IN (SELECT user_id FROM Users WHERE user_id = ?)");
   $requeste->execute(array($token, $id));

   $resultat = $requeste->fetch();

   if($resultat > 0){
      return $error = ERROR_0;
   }

   return $error = ERROR_5;

}

//
function showdiscution($user_1, $user_2){

   $connexion = connexion();
   $requeste = $connexion->prepare("SELECT from_user_x, to_user_y, message_body, message_date from Posted_messages WHERE (from_user_x = ? AND to_user_y = ?) OR (from_user_x = ? AND to_user_y = ?)");
   $requeste->execute(array($user_1, $user_2, $user_2, $user_1));

   $messages = $requeste->fetchAll();

   return $error = json_encode([
      'status' => 'ok',
      'messages' => $messages
   ]);

}

//
function sendto($url){

   $connexion = connexion();
   $requeste = $connexion->prepare("INSERT INTO Posted_messages (message_body, message_date, from_user_x, TO_USER_Y) VALUES (?, ?, ?, ?)");
   $requeste->execute(array($url[3], time(), $url[1], $url[2]));

   return $error = json_encode([
      'status' => 'ok',
      'message' => 'evoyer avec succes'
   ]);

}