<?php
    session_start();
    if(isset($_SESSION['zalogowany']))
    {
        header('Location: apteczka.php');
        exit();
    }
    include 'apteczka/include/nagl.php';
?>
<?php
    if(isset($_SESSION['udanaRejestracja']))
    {
        echo 'Udana rejestracja. Zaloguj się.';
        echo '<br>';
        unset($_SESSION['udanaRejestracja']);
    }
?>
<h2>Zaloguj sie</h2>

<?php
    if(isset($_SESSION['bladLogowania'])){
        echo '<div class = "alert alert-danger" role="alert">';
        echo $_SESSION['bladLogowania'];
        echo '</div>';
        session_unset();
    }
?>
<form action ="zaloguj.php" method = "post">
    E-mail : <input type="email" name="email" placeholder = "Wpisz swój email" required><br>
    Hasło : <input type="password" name="haslo" placeholder="wprowadź hasło"><br>
    <input type="submit" value="Dalej">
</form>
<a href = "rejestracja.php">Zarejestruj sie</a>
<?php
    include 'apteczka/include/stopka.php';   
?>