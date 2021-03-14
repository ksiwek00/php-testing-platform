<?php
session_start(); 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
else{
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Tester</title>
    <script src="jquery.js"></script>
    <script>
    $(document).ready(function() {
        // menu
        $("#profile-button").on("click", function() {
            $("#profile").css("display", "flex")
            $("#test").css("display", "none")
            $("#stats-user").css("display", "none")
            var buttons = $("header .menu-button")
            buttons.removeClass("selected")
            $("#profile-button").addClass("selected")
        })
        $("#test-button").on("click", function() {
            $("#profile").css("display", "none")
            $("#test").css("display", "flex")
            $("#stats-user").css("display", "none")
            var buttons = $("header .menu-button")
            buttons.removeClass("selected")
            $("#test-button").addClass("selected")
        })
        $("#stats-button").on("click", function() {
            $("#profile").css("display", "none")
            $("#test").css("display", "none")
            $("#stats-user").css("display", "flex")
            var buttons = $("header .menu-button")
            buttons.removeClass("selected")
            $("#stats-button").addClass("selected")
        })
        $("#logout-button").on("click", function() {
            var confirm = window.confirm("Czy na pewno chcesz się wylogować?")
            if (confirm) {
                window.location.href = "logout.php";
            }
        })
        //test
        $("#start-test").on("click", function() {
            $.ajax({
                url: 'getTest.php',
                type: 'GET',
                data: {},
                success: function(res) {
                    $("#results").css("display", "none")
                    $("#questions").css("display", "flex")
                    $("#start-test").css("display", "none")
                    $("#submit-test").css("display", "block")
                    $("#test #questions").html(res);
                }
            });
        })
        $("#test").on("click", "#submit-test", function() {
            var ok = true;
            for (var i = 1; i <= 10; i++) {
                if (document.querySelector('input[name="q' + i + '"]:checked') == null) {
                    ok = false;
                    break;
                }
            }
            if (ok) {
                $.ajax({
                    url: 'checkTest.php',
                    type: 'GET',
                    data: {
                        q1: document.querySelector('input[name="q1"]:checked').value,
                        q2: document.querySelector('input[name="q2"]:checked').value,
                        q3: document.querySelector('input[name="q3"]:checked').value,
                        q4: document.querySelector('input[name="q4"]:checked').value,
                        q5: document.querySelector('input[name="q5"]:checked').value,
                        q6: document.querySelector('input[name="q6"]:checked').value,
                        q7: document.querySelector('input[name="q7"]:checked').value,
                        q8: document.querySelector('input[name="q8"]:checked').value,
                        q9: document.querySelector('input[name="q9"]:checked').value,
                        q10: document.querySelector('input[name="q10"]:checked').value,
                    },
                    success: function(res) {
                        $("#results").css("display", "block")
                        $("#questions").css("display", "none")
                        $("#start-test").css("display", "block")
                        $("#submit-test").css("display", "none")
                        $("#results").html(res)
                    }
                });
            } else {
                alert(
                    "Nie wszystkie pytania są wypełnione. Wypełnij wszysktie pytania zanim zakończysz test!"
                );
            }
        })
    })
    </script>
</head>

<body>
    <div id="container">
        <header class="menu">
            <div id="profile-button" class="menu-button selected">Mój profil</div>
            <div id="test-button" class="menu-button">Test</div>
            <div id="stats-button" class="menu-button">Statystyki</div>
            <div id="logout-button" class="menu-button">Wyloguj się</div>
        </header>
        <div id="content">
            <div id="profile">
                <h1>Witaj
                    <?php
                        echo($_SESSION["username"])
                    ?>
                </h1>
                <?php
                    $res = $conn->query('SELECT * FROM users WHERE username="'.$_SESSION["username"].'"');
                    foreach($res as $key=>$user){
                        $value = $user["correct"] / $user["given"] * 100;
                        $value = round($value, 2);
                        $index = $key + 1;
                        echo "<p>Odpowiedziałeś do tej pory na ".$user["given"]." pytań<br/>";
                        echo "Poprawnie rozwiązałeś ".$user["correct"]." z nich<br/>";
                        echo "Twój procent poprawnych odpowiedzi: ".$value."%<br/></p>";
                    }

                    $res = $conn->query('SELECT * FROM users WHERE given != 0 ORDER BY ( correct / given ) DESC LIMIT 10');
                    $id = null;
                    while($row = $res->fetch_assoc()) {
                        foreach($res as $key=>$user){
                            if($user["username"] == $_SESSION["username"]){
                                $id = $key;
                            }
                        }
                    }
                    if($id != null){
                        echo("<h2>Jesteś obecnie w pierwszej dziesiątce! Twoje miejsce to: ".($id+1)."</h2>");
                    }
                    else{
                        echo("<h2>Nie ma cię jeszcze w pierwszej dziesiątce, ale pracuj dalej!</h2>");
                    }
                ?>
            </div>
            <div id="test">
                <h1>Sprawdź swoje umiejętności</h1>
                <div id="start-test" class="menu-button selected">Rozpocznij nowy test</div>
                <form id='questions' target="test" method="GET"></form>
                <div id="results"></div>
            </div>
            <div id="stats-user">
                <h1>Najlepsi użytkownicy</h1>
                <?php
                    $res = $conn->query('SELECT * FROM users WHERE given != 0 ORDER BY ( correct / given ) DESC LIMIT 10');
                    while($row = $res->fetch_assoc()) {
                        foreach($res as $key=>$user){
                            $value = $user["correct"] / $user["given"] * 100;
                            $value = round($value, 2);
                            $index = $key + 1;
                            if($user["username"] == $_SESSION["username"]){
                                echo "<div class='data'><b>".$index.". ".$user["username"]." : ".$value."%</b></div><br/>";
                            }
                            else{
                                echo "<div class='data'>".$index.". ".$user["username"]." : ".$value."%</div><br/>";
                            }
                        }
                    }
                ?>
            </div>
        </div>
        <footer></footer>
    </div>


</body>

</html>