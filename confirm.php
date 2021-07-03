<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=lys;charset=utf8', "root", "");
} catch (Exception $e) {
    Die('Message erreur : '.$e->getMessage());
}

$id = $_GET["id"];
$token = $_GET["token"];

$prepare = $pdo->prepare('SELECT * FROM users WHERE id=?');
$prepare->execute(array($id));
$user = $prepare->fetch(PDO::FETCH_OBJ);

if($user && $user->confirmation_token == $token){
    $update_request = $pdo->prepare("UPDATE users SET confirmation_token = NULL, confirm_at = NOW() WHERE id=?");
    $update_request->execute(array($id));
    $_SESSION['user'] = $user;
    header("Location:account.php");
}else{
    $_SESSION["flash"]="Le token n'est plus valide";
    header("Location:login.php");
}