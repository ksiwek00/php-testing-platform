<?php
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('SELECT * FROM questions WHERE text="'.$_GET['text'].'"');
    while($row = $res->fetch_assoc()) {
        foreach($res as $question){
            $wrong = $question['given'] - $question['correct'];
            if($wrong != 0){
                $percent = ($question['correct'] / $question['given']) * 100;
                $percent = round($percent, 2);
                $percent = strval($percent)."%";
            }
            else{
                if($question['correct']>0){
                    $percent = "100%";
                }
                else{
                    $percent = "Brak statystyk";
                }
            }
            echo (
                "<div class='user'>
                    <h2>Tekst pytania: ".$question['text']."</h2>
                    <p>Liczba wszystkich odpowiedzi: ".$question['given']."</p>
                    <p>Liczba poprawnych odpowiedzi: ".$question['correct']."</p>
                    <p>Liczba niepoprawnych odpowiedzi: ".$wrong."</p>
                    <p>Procent poprawnych odpowiedzi: ".$percent."</p>
                </div><br/>"
            );
        }
    }
?>