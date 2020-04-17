<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    if(isset($_POST['leki'])){

        
        $rodzaj = "dodanie";
        $id_uzytkownika = $_SESSION['zalogowany'];
        $id_leku = $_POST['leki'];
        $id_apteczki = $_POST['apteczka'];
        $ilosc = $_POST['ilosc'];
        $cenazasztuke = $_POST['cena'];
        $koszty = $cenazasztuke*$ilosc;
        $datawaznosci = $_POST['datawaznosci'];
        $data = date('Y-m-d H:i:s');
        unset($_POST['leki']);
        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
 
                $polaczenie->query("INSERT INTO operacje VALUES (NULL,'$rodzaj','$id_uzytkownika','$id_leku','$id_apteczki','$ilosc','$koszty','$data')");

                for($i = 0; $i<$ilosc; $i++){
                    $polaczenie->query("INSERT INTO leki_w_apteczkach VALUES (NULL,'$id_leku','$id_apteczki','$datawaznosci','$cenazasztuke')");
                }

                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "Błąd serwera! Przepraszamy za niedogodności i prosimy o rejsetracje w innym terminie";
            echo '<br>Bład'.$e;
        }
    }

    include 'nagl.php';
?>


<form method = 'post'>

    <label for="leki">Wybierz lek:</label>

    <select id="leki" name = "leki">

    <?php
        require_once 'connect.php';
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{  
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultaty = $polaczenie->query("SELECT * FROM leki");
                if(!$rezultaty) throw new Exception($polaczenie->error);
                else{
                    while($row = $rezultaty->fetch_row()){
                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                    }
                }
                $rezultaty->free_result();
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "blad polaczenia z baza";
        }
        
    ?>
    </select>
    <br>
<?php

?>
     <label for="apteczka">Wybierz apteczke do ktorej chcesz dodac lek:</label>
    <select id="apteczka" name = "apteczka">

    <?php
        require_once 'connect.php';
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{  
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultaty = $polaczenie->query("SELECT * FROM apteczki,apteczki_uzytkownicy WHERE apteczki.id_apteczki = apteczki_uzytkownicy.id_apteczki AND apteczki_uzytkownicy.id_uzytkownika='$zalogowany'");
                if(!$rezultaty) throw new Exception($polaczenie->error);
                else{
                    while($row = $rezultaty->fetch_row()){
                        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                    }
                }
                $rezultaty->free_result();
                $polaczenie->close();
            }
        }
        catch(Exception $e){
            echo "blad polaczenia z baza";
        }
    ?>

    </select> 
    <br>
        <label>
        Wprowadz date waznosci:
        <input type="date" name="datawaznosci">
        </label>
    <br>
    Cena za sztukę: <input type="number" name="cena" min = "0.00" value = "0.00" step="0.01">
    <br>
    Ilość: <input type="number" name="ilosc" min = "1" value = "0" step="1">
    <br>
    <input type="submit" value = "Dodaj lek do apteczki">    

</form>
<a href = 'menu.php'>Wróć do menu</a><br>
<a href = 'logout.php'>Wyloguj</a>
<?php
    include 'stopka.php';
?>


