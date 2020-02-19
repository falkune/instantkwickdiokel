<?php
$servname = 'localhost';
$dbname = 'instantkwick';
$user = 'diokel';
$password = '  diokel';

try{
   $connexion = new PDO("mysql:host=$servname;dbname=$dbname", $user, $password, array(PDO::ATTR_ERRMODE	=>PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e){
   echo "connexion error";
}