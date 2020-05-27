<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    include 'include/nagl.php';

    
    if(isset($_POST['kit'])){
        $nazwa = $_POST['kit'];


        require_once "include/connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
 
                $polaczenie->query("INSERT INTO apteczki VALUES (NULL,'$nazwa')");
                $rezultat = $polaczenie->query("SELECT id_apteczki FROM apteczki WHERE nazwa_apteczki='$nazwa'");
                if(!$rezultat){
                    throw new Exception($polaczenie->error);
                }
                else{
                    $wynik = $rezultat->fetch_assoc();
                    $id = $wynik['id_apteczki'];
                    $polaczenie->query("INSERT INTO apteczki_uzytkownicy VALUES (NULL,'$zalogowany','$id')");
                }
                
                
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "Błąd serwera! Przepraszamy za niedogodności i prosimy o rejsetracje w innym terminie";
            echo '<br>Bład'.$e;
        }

        unset($_POST['kit']);
    }




?>

<div class = "container">
    <div class = "row">
        <form method="post">
            <div class = "form-group row">
                <div class = "form-group col-md-8">
                    <input  class="form-control" type="text" placeholder = "Name of first-aid-kit" name = "kit">
                </div>
                <div class = "form-group col-md-4">
                    <input  class="btn btn-primary btn-block" type="submit" value = "Create">
                </div>
            </div>
        </form>
    </div>
    <div class="row">
                <div class = "col-md-4">
                    <a class="btn btn-primary btn-block" href = 'dodajlek.php'>Back</a>
                    <a class="btn btn-primary btn-block" href = 'logout.php'>Logout</a>
                </div>
    </div>
</div>


<?php
    include 'include/stopka.php';
?>