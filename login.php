<?php 

session_start();

try{
    $pdo = new PDO("mysql:host=localhost;dbname=lys;charset=utf8","root","");
}catch(Exception $e){
    Die("Message d'erreur ".$e->getMessage());
}

$message = null;
$errors = [];

if(isset($_POST["send"])){

    if(isset($_POST['email']) && !empty($_POST["email"])){
        if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $email = htmlspecialchars($_POST['email']);
        }else{
            $errors["email"] = "Entrez une adresse email correcte";
        }
    }else{
        $errors["email"] = "Renseigner correctement l'email";
    }
    if(isset($_POST['password']) && !empty($_POST["password"])){
        $password = htmlspecialchars($_POST['password']);
    }else{
        $errors["password"] = "Renseigner correctement le mot de passe";
    }
    if(empty($errors)){
        $prepare = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $prepare->execute(array($email));
        $user_exist = $prepare->rowCount();
        if($user_exist){
            $db_user = $prepare->fetch(PDO::FETCH_OBJ);
            if(password_verify($password, $db_user->password)){
                $_SESSION["user"] = $db_user;
                header('Location:account.php');                    
            }else{
                $message = "Email ou mot de passe incorrect";
            }
        }else{
            $message = "Email ou mot de passe incorrect";        
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>CONNECTEZ VOUS</h1>
   <?php if(isset($_SESSION["flash"])):?>
        <span style="font-style:italic">
            <?php 
                echo $_SESSION["flash"];
                unset($_SESSION["flash"]);
            ?>
        </span>
    <?php elseif($message):?>
        <span style="font-style:italic"><?=$message?></span>
    <?php endif?>
    <form action="" method="post">
        <input type="email" name="email" id="" placeholder="Entrez votre email"/><br><br>
        <input type="password" name="password" id="" placeholder="Entrez votre mot de passe"/><br><br>
        <button type="submit" name="send">Envoyer</button>
    </form>
    <ul>
       <?php foreach($errors as $error):?>
        <li><?=$error?></li>
       <?php endforeach ?> 
    </ul>    
</body>
</html>


