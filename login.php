<?php

   @include 'config.php';

   session_start();

   if(isset($_POST['submit'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $pass = md5($_POST['password']);
      $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";
      $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);
      $_SESSION['user_name'] = $row['name'];
      header('location:user_page.php');
   }else{
         $error[] = 'email ou senha incorretos';

   }
}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, 
      initial-scale=1.0">
      
        <link rel="icon" href="img/icone_livro.png"> 
      <title> Login | Sistema</title>
      <link rel="stylesheet" href="css/style_login_cadastro.css">

   </head>

   <body>

       <nav>
         <img src="img/logo_nav.png" width="100px">
         <h3 class="nav"> FACULDADE CDL </h3>
      </nav>

      <div class="form-container">

         <form action="" method="post">
            <h3>Sistema Biblioteca</h3>

            <?php
               if(isset($error)){
                  foreach($error as $error){
                     echo '<span class="error-msg">'.$error.'</span>';
                  };
               };
            ?>
      

            <input type="email" name="email" required placeholder="Digite o email" autocomplete="OFF">
            <input type="password" name="password" required placeholder="Digite a senha" autocomplete="OFF">
            <input type="submit" name="submit" value="Entrar" class="form-btn">
            <p> <a href="register_form.php">Cadastrar Administrador</a></p>
         </form>
         
      </div>

   </body>
</html>
