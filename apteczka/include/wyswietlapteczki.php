<?php
        require_once 'apteczka/include/connect.php';
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