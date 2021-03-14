<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('SELECT * FROM questions ORDER BY RAND() LIMIT 10');
    $i = 0;
    $questions = [];
    while($row = $res->fetch_assoc()) {
        $i = $i + 1;
        array_push($questions, $row["id"]);
        echo("
            <div class='question'>
                <h3>".$i.": ".$row["text"]."</h3>
                <label>A: ".$row["anwser_a"]."<input type='radio' value='a' name='q".$i."'></label><br/>
                <label>B: ".$row["anwser_b"]."<input type='radio' value='b' name='q".$i."'></label><br/>
                <label>C: ".$row["anwser_c"]."<input type='radio' value='c' name='q".$i."'></label><br/>
                <label>D: ".$row["anwser_d"]."<input type='radio' value='d' name='q".$i."'></label>
            </div>
        ");
    }
    $_SESSION["questions"] = $questions;
    echo("<div id='submit-test' class='menu-button selected'>Zakończ test</div>
    <div class='longer'></div>");
?>