<?php
    session_start();
    if(isset($_SESSION['zalogowany']))
    {
        header('Location: menu.php');
        exit();
    }
    include 'include/nagl.php';
?>
<div class = "containter">
<?php
    if(isset($_SESSION['udanaRejestracja']))
    {
        echo 'Udana rejestracja. Zaloguj się.';
        echo '<br>';
        unset($_SESSION['udanaRejestracja']);
    }
?>

<?php
    if(isset($_SESSION['bladLogowania'])){
        echo '<div class = "alert alert-danger" role="alert">';
        echo $_SESSION['bladLogowania'];
        echo '</div>';
        session_unset();
    }
?>
<div class = "row">
    <form class="form-signin" action ="zaloguj.php" method = "post">
        <img class = "mb-4" src="img/eskulapa.jpg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Zaloguj się</h1>
        <label for="inputEmail" class="sr-only">Adres Email</label>
        <input class="form-control" id="inputEmail" type="email" name="email" placeholder="Email">
        <label for="inputPassword" class="sr-only">Hasło</label>
        <input class="form-control" id="inputPassword" type="password" name="haslo" placeholder="Hasło">
        <button class="btn btn-primary btn-block" type="submit">Logowanie</button>
        <a class="btn btn-primary btn-block"  href = "rejestracja.php">Rejestracja</a>
    </form>
</div>
</div>
<?php
    include 'include/stopka.php';   
?>