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
?>

<div class = "container">
        <div class="row">
            <form method = 'post'>
                <div class ="form-group row">
                    <div class="form-group col-md-6">
                        <label for="apteczka">Wybierz apteczkę</label>
                        <select id="apteczka" name = "apteczka">

                            <?php
                                include 'include/wyswietlapteczki.php';
                            ?>

                        </select> 
                    </div>
                    <div class="form-group col-md-6">
                        <input class = "btn btn-primary btn-block" type="submit" value = "Sprawdź">  
                    </div>
                </div> 
            </form>
        </div>

        <?php
            if(isset($_POST['apteczka']) || isset($_SESSION['po_usuwaniu'])){
                if(isset($_POST['apteczka'])){
                    $id_apteczki = $_POST['apteczka'];
                }
                else{
                    $id_apteczki = $_SESSION['po_usuwaniu'];
                }
                $_SESSION['id_apteczki'] = $id_apteczki;
                
                    require_once 'include/connect.php';
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
                                echo '<div id = "1" class="row">';
                                echo '<form action = "usun.php" method = "post">';
                                echo '<div class = "form-group row">';
                                echo '<table class="table"><tr class="thead-dark"><th>Nazwa leku</th><th>Cena</th><th>Data ważności</th><th><div><input type="submit" name = "action" value="Usun/Utylizuj"></div></th><th><div ><input type="submit" name = "action" value="Zażyj"></div></th></tr>';
                                while($row = $rezultaty->fetch_row()){
                                    $data = new DateTime($row[2]);    
                                    if($data->format('Y-m-d H:i:s')<date('Y-m-d H:i:s'))
                                        echo '<tr style="background-color:red"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td><input type="checkbox" name='.$row[3].' value = "utylizacja"></td></tr>';
                                    else{
                                        echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td><input type="checkbox"  name='.$row[3].' value = "usuniecie"></td><td><input type="checkbox"  name=zazyj'.$row[3].' value = "zazycie"></td></tr>';
                                    }
                                }
                                echo '</table>';
                                echo '</div>';
                                echo '</form>';
                                echo '</div>';
                            }
                            $rezultaty->free_result();
                            $polaczenie->close();
                            
                        }
                    }
                    catch(Exception $e){
                        echo $e->getMessage();
                        echo "blad polaczeniiiiiia z baza";
                    }

                    unset($_POST['apteczka']);
                    unset($_SESSION['po_usuwaniu']);
            }
        ?>
        <div class="row">
                <div class = "col-md-4">
                    <a class="btn btn-primary btn-block" href = 'menu.php'>Wróć do menu</a>
                    <a class="btn btn-primary btn-block" href = 'logout.php'>Wyloguj</a>
                </div>
        </div>
   
</div>
        

<?php
    include 'include/stopka.php';
?>