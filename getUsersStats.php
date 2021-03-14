<?php
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('SELECT * FROM users WHERE username="'.$_GET['username'].'"');
    while($row = $res->fetch_assoc()) {
        foreach($res as $user){
            $wrong = $user['given'] - $user['correct'];
            if($wrong != 0){
                $percent = ($user['correct'] / $user['given']) * 100;
                $percent = round($percent, 2);
                $percent = strval($percent)."%";
            }
            else{
                if($user['correct']>0){
                    $percent = "100%";
                }
                else{
                    $percent = "Brak statystyk";
                }
            }
            echo (
                "<div class='user'>
                    <h1>".$user['username']."</h1>
                    <p>Liczba wszystkich odpowiedzi: ".$user['given']."</p>
                    <p>Liczba poprawnych odpowiedzi: ".$user['correct']."</p>
                    <p>Liczba niepoprawnych odpowiedzi: ".$wrong."</p>
                    <p>Procent poprawnych odpowiedzi: ".$percent."</p>
                </div><br/>"
            );
        }
    }
?>