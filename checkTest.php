<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

    $res = $conn->query('SELECT * FROM questions WHERE id IN (' . implode(',', array_map('intval', $_SESSION["questions"])).') ORDER BY field(id,'. implode(',', array_map('intval', $_SESSION["questions"])).' )');
    $user = $conn->query('SELECT * FROM users WHERE username="'.$_SESSION["username"].'"');
    $i = 0;
    $ok = 0;
    while($row = $res->fetch_assoc()) {
        $i = $i + 1;
        $row["given"] = $row["given"] + 1;
        if($_GET["q".strval($i)] == $row["right_anwser"]){
            $ok = $ok + 1;
            $row["correct"] = $row["correct"] + 1;
            echo("<p>".$i.". ".$row["text"]." - ".$_GET["q".strval($i)]." <span style='color: lime'>&#10004;</span></p>");
        }
        else{
            echo("<p>".$i.". ".$row["text"]." - ".$_GET["q".strval($i)]." <span style='color: crimson'>&#10007;<br/>Właściwa odpowiedź: ".$row["right_anwser"]."</span></p>");
        }
        $conn->query("UPDATE questions SET given=".$row["given"].", correct=".$row["correct"]." WHERE id='".$row['id']."'");
    }
    while($row = $user->fetch_assoc()) {
        $correct = $row["correct"] + $ok;
        $given = $row["given"] + 10;
        $conn->query("UPDATE users SET given=".$given.", correct=".$correct." WHERE username='".$_SESSION['username']."'");
    }
    echo("<p>Wynik: ".$ok."/10 (");
    $percent = round($ok/10 * 100, 2);
    echo($percent."%)<br/>Odśwież stronę, aby zobaczyć swoje zaktualizowane statystyki.</p>");
?>