<?php
    include 'conexao.php';
    if(isset($_POST['submitted']) ) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        
        $query = "SELECT * from usuario WHERE email ='".$email."' AND senha ='".$senha."' ;";
        $result = pg_fetch_all(pg_query($query));

        if ($result){
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            pg_free_result($result);
            
            $sent = true;
            header('location:dashboard.php');
        }
        else{
            session_destroy();
            unset ($_SESSION['login']);
            unset ($_SESSION['senha']);
            header('location:index.php');
        }
    }
?>