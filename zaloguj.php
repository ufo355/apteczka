<?php
    session_start();
    if(!isset($_POST['email']) || !isset($_POST['haslo']))
    {
        header('Location: index.php');
        exit();
    }
    $wszystkoOK = true;
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $wszystkoOK = false;
    }

    if($wszystkoOK){ 
        require_once "connect.php";
        try{
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                $_SESSION['bladLogowania'] = "Bład polaczenie z bazą";
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultat = $polaczenie->query("SELECT * FROM uzytkownicy WHERE email='$email'");
                if(!$rezultat){
                    $_SESSION['bladLogowania'] = "Bład polaczenie z bazą";

                    throw new Exception($polaczenie->error);
                }
                $ilu_userow = $rezultat->num_rows;
                if($ilu_userow>0){
                    $wiersz = $rezultat->fetch_assoc();
                    if(password_verify($haslo,$wiersz['hashhaslo']))
                    {
                        $_SESSION['zalogowany']=$wiersz['id_uzytkownika'];
                        $rezultat->free_result();
                        unset($_SESSION['bladLogowania']);
                        header('Location: menu.php');
                    }
                    else
                    {
                        $_SESSION['bladLogowania']="Niepoprawne haslo";
                        $rezultat->free_result();
                        header('Location: index.php');
                    }
                }
                else{
                    $_SESSION['bladLogowania']="Brak uzytkownika";
                    header('Location: index.php');
                }
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            header('Location: index.php');
        }
    }
    else{

        $_SESSION['bladLogowania'] = "Błąd logowania";
        header('Location: index.php');
    }
?>