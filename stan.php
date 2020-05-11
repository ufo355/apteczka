<?php
  session_start();
  if(!isset($_SESSION['zalogowany']))
  {
      $_SESSION['bladLogowania'] = "Najpierw sie zaloguj ;)";
      header('Location: index.php');
      exit();
  }
  $zalogowany = $_SESSION['zalogowany'];
  include 'nagl.php';
?>


<form method = 'post'>

    <label for="apteczka">Wybierz apteczkę, której stan chcesz sprawdzić</label>
        <select id="apteczka" name = "apteczka">

        <?php
            include 'wyswietlapteczki.php';
        ?>

    </select> 

    <input type="submit" value = "Sprawdź stan apteczki">    
</form>


<?php
    if(isset($_POST['apteczka']) || isset($_SESSION['po_usuwaniu'])){
        if(isset($_POST['apteczka'])){
            $id_apteczki = $_POST['apteczka'];
        }
        else{
            $id_apteczki = $_SESSION['po_usuwaniu'];
        }
        $_SESSION['id_apteczki'] = $id_apteczki;
        echo '<table><tr><th>Nazwa leku</th><th>Cena</th><th>Data ważności</th></tr>';
        require_once 'connect.php';
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{  
            $polaczenie = new mysqli($host, $db_user,$db_password, $db_name);
            if($polaczenie->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                $rezultaty = $polaczenie->query("SELECT leki.nazwa_leku, leki_w_apteczkach.cena, leki_w_apteczkach.data_waznosci, leki_w_apteczkach.id_leku_w_apteczce FROM leki,leki_w_apteczkach WHERE leki.id_leku=leki_w_apteczkach.id_leku AND Id_apteczki=$id_apteczki");
                if(!$rezultaty) throw new Exception($polaczenie->error);
               
                else{

                    echo '<form action = "usun.php" method = "post">';
                    while($row = $rezultaty->fetch_row()){
                        $data = new DateTime($row[2]);    
                        if($data->format('Y-m-d H:i:s')<date('Y-m-d H:i:s'))
                            echo '<tr style="background-color:red"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td><input type="checkbox" name='.$row[3].' value = "utylizuj"></td></tr>';
                        else{
                            echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td><input type="checkbox"  name='.$row[3].' value = "usun"></td></tr>';
                        }
                    }
                    echo '<input type="submit" value="Usun/Utylizuj">';
                    echo '</form>';
                }
                $rezultaty->free_result();
                $polaczenie->close();
                
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
            echo "blad polaczeniiiiiia z baza";
        }
        echo '</table>';
        unset($_POST['apteczka']);
        unset($_SESSION['po_usuwaniu']);
    }



?>
<a href = 'menu.php'>Wróć do menu</a><br>
<a href = 'logout.php'>Wyloguj</a>
<?php
    include 'stopka.php';
?>