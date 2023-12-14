<?php

   @include 'config.php';

   if(isset($_POST['submit'])){

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $pass = md5($_POST['password']);
      $cpass = md5($_POST['cpassword']);

      $select = " SELECT * FROM user_form WHERE email = '$email' ";

      $result = mysqli_query($conn, $select);


   if(mysqli_num_rows($result) > 0){

      $error[] = 'Email já cadastrado!';

   }else{

      if($pass != $cpass){
         $error[] = 'Senhas não condizem';
      }else{
         $insert = "INSERT INTO user_form(name, email, password) VALUES('$name','$email','$pass')";
         mysqli_query($conn, $insert);
         header('location:login.php');
      }
   }

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/icone_livro.png"> 
   <title>Cadastro | Sistema</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style_login_cadastro.css">

</head>

   <body>
   
      <nav>
         <img src="img/logo_nav.png" width="100px">
          <h3 class="nav"> FACULDADE CDL </h3>
      </nav>

      <div class="form-container">

         <form action="" method="post">
            <h3>Registre-se</h3>
            <?php
               if(isset($error)){
                  foreach($error as $error){
                     echo '<span class="error-msg">'.$error.'</span>';
                  };
               };
            ?>
            <input type="text" name="name" required placeholder="Insira seu nome" autocomplete="OFF">
            <input type="email" name="email" required placeholder="Insira seu email" autocomplete="OFF">
            <input type="password" name="password" required placeholder="Crie uma nova senha">
            <input type="password" name="cpassword" required placeholder="Confirme a senha">
            
            <input type="submit" name="submit" value="Criar Conta" class="form-btn">
         </form>

      </div>

   </body>
</html>