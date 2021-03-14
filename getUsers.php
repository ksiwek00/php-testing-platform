<?php
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('SELECT * FROM users WHERE username="'.$_GET['username'].'"');
    while($row = $res->fetch_assoc()) {
        foreach($res as $user){
            if ($user['admin'] == 1){
                $admin = " checked ";
            }
            else{
                $admin = "";
            }
            echo 
            "<div class='user'>
                <form method='POST'>
                    <label> ID: 
                        <input type='text' name='id' readonly value='".$user['id']."'>
                    </label>
                    <label> Login: 
                        <input type='text' name='username' value='".$user['username']."'>
                    </label>
                    <label> Wymuś zmianę hasła:
                        <input type='password' name='password' placeholder='NOWE HASŁO' value=''>
                    </label>
                    <label> Ilość danych odpowiedzi:
                        <input type='text' name='given' value='".$user['given']."'>
                    </label>
                    <label> Ilość poprawnych odpowiedzi:
                        <input type='text' name='correct' value='".$user['correct']."'>
                    </label>
                    <label> Prawa administracyjne: 
                        <input type='checkbox' value='admin' name='admin' ".$admin.">
                    </label>
                    <input class='update-user' type='submit' name='updateUser' value='AKTUALIZUJ' >
                    <input class='delete-user' type='submit' name='deleteUser' value='USUŃ' >
                </form>
            </div><br/>";
        }
    }
?>