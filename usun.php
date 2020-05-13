<?php
    session_start();
    if(!isset($_SESSION['zalogowany']))
    {
        $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
        header('Location: index.php');
        exit();
    }
    $zalogowany = $_SESSION['zalogowany'];
    $id_apteczki = $_SESSION['id_apteczki'];
    require_once 'connect.php';
    try{  
        $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
        if($polaczenie->connect_errno!=0){
            throw new Exception(mysqli_connect_errno());
        }
        else{        
            $rezultaty = $polaczenie->query("SELECT leki.id_leku, leki_w_apteczkach.cena, leki_w_apteczkach.data_waznosci, leki_w_apteczkach.id_leku_w_apteczce FROM leki,leki_w_apteczkach WHERE leki.id_leku=leki_w_apteczkach.id_leku AND leki_w_apteczkach.Id_apteczki=$id_apteczki");
            $data = date('Y-m-d H:i:s');
            echo $_POST['action'];
            if($_POST['action'] == 'Usun/Utylizuj'){
                while($row = $rezultaty->fetch_row()){
                    if(isset($_POST[$row[3]]))
                    {
                        $operacja = $_POST[$row[3]];
                        $id_leku = $row[0];
                        $ilosc = 1;
                        $koszty = $row[1];
                        echo $operacja.' '.$id_leku.' '.$zalogowany.' '.$id_apteczki.' '.$data.'<br>';
                        $polaczenie->query("INSERT INTO operacje VALUES (NULL,'$operacja','$zalogowany','$id_leku','$id_apteczki','$ilosc','$koszty','$data')");
                        $polaczenie->query('DELETE FROM leki_w_apteczkach WHERE id_leku_w_apteczce='.$row[3]);
                    }
                }
            }
            else{
                echo 'test';
                while($row = $rezultaty->fetch_row()){
                    if(isset($_POST['zazyj'.$row[3]]))
                    {
                        $operacja = $_POST['zazyj'.$row[3]];
                        $id_leku = $row[0];
                        $ilosc = 1;
                        $koszty = $row[1];
                        echo $operacja.' '.$id_leku.' '.$zalogowany.' '.$id_apteczki.' '.$data.'<br>';
                        $polaczenie->query("INSERT INTO operacje VALUES (NULL,'$operacja','$zalogowany','$id_leku','$id_apteczki','$ilosc','$koszty','$data')");
                        $polaczenie->query('DELETE FROM leki_w_apteczkach WHERE id_leku_w_apteczce='.$row[3]);
                    }
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

