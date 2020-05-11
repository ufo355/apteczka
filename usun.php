<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $id_apteczki = $_SESSION['id_apteczki'];
    require_once 'connect.php';
    try{  
        $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
        if($polaczenie->connect_errno!=0){
            throw new Exception(mysqli_connect_errno());
        }
        else{        
            $rezultaty = $polaczenie->query("SELECT leki.nazwa_leku, leki_w_apteczkach.cena, leki_w_apteczkach.data_waznosci, leki_w_apteczkach.id_leku_w_apteczce FROM leki,leki_w_apteczkach WHERE leki.id_leku=leki_w_apteczkach.id_leku AND Id_apteczki=$id_apteczki");
            while($row = $rezultaty->fetch_row()){
                if(isset($_POST[$row[3]]))
                {
                    echo 'dupa';
                    echo gettype($row[3]);
                    echo '<br>';
                    $polaczenie->query('DELETE FROM leki_w_apteczkach WHERE id_leku_w_apteczce='.$row[3]);
                }
            }
            $polaczenie->close(); 
            $_SESSION['po_usuwaniu'] = $id_apteczki;
            header('location: stan.php');
        }

    }
    catch(Exception $e){
        echo "blad polaczenia z baza";
    }
?>

