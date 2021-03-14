<?php
session_start(); 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["admin"] !== true){
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
        $("#questions-button").on("click", function() {
            $("#questions").css("display", "flex")
            $("#users").css("display", "none")
            $("#stats").css("display", "none")
            var buttons = $("header .menu-button")
            buttons.removeClass("selected")
            $("#questions-button").addClass("selected")
        })
        $("#users-button").on("click", function() {
            $("#questions").css("display", "none")
            $("#users").css("display", "flex")
            $("#stats").css("display", "none")
            var buttons = $("header .menu-button")
            buttons.removeClass("selected")
            $("#users-button").addClass("selected")
        })
        $("#stats-button").on("click", function() {
            $("#questions").css("display", "none")
            $("#users").css("display", "none")
            $("#stats").css("display", "flex")
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
        // pytania
        $("#questions .new-question").on("click", function() {
            $.ajax({
                url: 'getQuestion.php',
                type: 'GET',
                data: {},
                success: function(res) {
                    $("#questions #edit-question").html(res);
                }
            });
        })
        $("#questions button.edit").on("click", function() {
            $.ajax({
                url: 'getQuestion.php',
                type: 'GET',
                data: {
                    id: this.value
                },
                success: function(res) {
                    $("#questions #edit-question").html(res);
                }
            });
        })
        // użytkownicy
        $("#users .user").on("click", function() {
            $.ajax({
                url: 'getUsers.php',
                type: 'GET',
                data: {
                    username: this.innerText
                },
                success: function(res) {
                    $("#users .rightcol").html(res);
                }
            });
        })
        // statystyki
        $("#questions-stats-button").on("click", function() {
            $("#questions-stats").css("display", "flex")
            $("#users-stats").css("display", "none")
            $("#rankings-stats").css("display", "none")
            var buttons = $(".menu-stats .menu-button")
            buttons.removeClass("selected")
            $("#questions-stats-button").addClass("selected")
        })
        $("#users-stats-button").on("click", function() {
            $("#questions-stats").css("display", "none")
            $("#users-stats").css("display", "flex")
            $("#rankings-stats").css("display", "none")
            var buttons = $(".menu-stats .menu-button")
            buttons.removeClass("selected")
            $("#users-stats-button").addClass("selected")
        })
        $("#rankings-stats-button").on("click", function() {
            $("#questions-stats").css("display", "none")
            $("#users-stats").css("display", "none")
            $("#rankings-stats").css("display", "flex")
            var buttons = $(".menu-stats .menu-button")
            buttons.removeClass("selected")
            $("#rankings-stats-button").addClass("selected")
        })
        $("#stats #users-stats .user").on("click", function() {
            $.ajax({
                url: 'getUsersStats.php',
                type: 'GET',
                data: {
                    username: this.innerText
                },
                success: function(res) {
                    $("#users-stats .rightcol").html(res);
                }
            });
        })
        $("#stats #questions-stats .user").on("click", function() {
            $.ajax({
                url: 'getQuestionsStats.php',
                type: 'GET',
                data: {
                    text: this.innerText
                },
                success: function(res) {
                    $("#questions-stats .rightcol").html(res);
                }
            });
        })
    })
    </script>
</head>

<body>
    <?php
        
        if(isset($_POST["deleteQuestion"])){
            $conn->query("DELETE FROM questions WHERE id='".$_POST['id']."'");
        }
        if(isset($_POST["updateUser"])){
            $admin = 0;

            if(isset($_POST["admin"])){
                $admin = 1;
            }
            if($_POST["password"] != ""){
                $passHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $conn->query("UPDATE users SET username='".$_POST['username']."', password='".$passHash."', correct=".$_POST['correct'].", given=".$_POST['given'].", admin=".$admin." WHERE id=".$_POST['id']);
            }
            else{
                $conn->query("UPDATE users SET username='".$_POST['username']."', correct=".$_POST['correct'].", given=".$_POST['given'].", admin=".$admin." WHERE id=".$_POST['id']);
            }
        }
        if(isset($_POST["deleteUser"])){
            if($_POST['username'] != "admin"){
                $conn->query("DELETE FROM users WHERE username='".$_POST['username']."'");
            }
        }
    ?>
    <div id="container">
        <header class="menu">
            <div id="questions-button" class="menu-button selected">Pytania i odpowiedzi</div>
            <div id="users-button" class="menu-button">Użytkownicy</div>
            <div id="stats-button" class="menu-button">Statystyki</div>
            <div id="logout-button" class="menu-button">Wyloguj się</div>
        </header>
        <div id="content">
            <div id="questions">
                <div class='question qheader'>
                    <div class='id'>ID</div>
                    <div class='text'>Pytanie</div>
                    <div class='anwser-a'>Odp. A</div>
                    <div class='anwser-b'>Odp. B</div>
                    <div class='anwser-c'>Odp. C</div>
                    <div class='anwser-d'>Odp. D</div>
                    <div class='right'>Właściwa odp.</div>
                    <div class='given'>Dane odp.</div>
                    <div class='correct'>Poprawne odp.</div>
                    <div class='edit'><button class='new-question'>DODAJ</button></div>
                </div>
                <?php
                    $res = $conn->query('SELECT * FROM questions');
                    while($row = $res->fetch_assoc()) {
                        foreach($res as $question){
                            echo "<div class='question'>
                                <div class='id'>".$question["id"]."</div>
                                <div class='text'>".$question["text"]."</div>
                                <div class='anwser-a'>".$question["anwser_a"]."</div>
                                <div class='anwser-b'>".$question["anwser_b"]."</div>
                                <div class='anwser-c'>".$question["anwser_c"]."</div>
                                <div class='anwser-d'>".$question["anwser_d"]."</div>
                                <div class='right'>".$question["right_anwser"]."</div>
                                <div class='given'>".$question["given"]."</div>
                                <div class='correct'>".$question["correct"]."</div>
                                <button class='edit' value=".$question["id"].">EDYTUJ</button>
                            </div><br/>";
                        }
                    }
                ?>
                <div class='error'>
                    <?php
                        if(isset($_POST["updateQuestion"])){
                            $arr = ["a","b","c","d"];
                            if(in_array($_POST['right_anwser'], $arr)){
                                if (is_numeric($_POST['correct']) && is_numeric($_POST['given'])){
                                    $conn->query("UPDATE questions SET text='".$_POST['text']."', anwser_a='".$_POST['anwser_a']."', anwser_b='".$_POST['anwser_b']."', anwser_c='".$_POST['anwser_c']."', anwser_d='".$_POST['anwser_d']."', right_anwser='".$_POST['right_anwser']."', given=".$_POST['given'].", correct=".$_POST['correct']." WHERE id=".$_POST['id']);
                                    echo("<meta http-equiv='refresh' content='0'>");
                                }
                                else{
                                    echo("Niewłaściwa wartość pola. Podano tekst, gdy należało podać liczbę.");
                                }
                            }
                            else{
                                echo("Właściwa wartość pola 'Właściwa odp.' to a,b,c lub d.");
                            }       
                        }
                    ?>
                </div>
                <div id='edit-question'></div>
            </div>
            <div id="users">
                <div class="leftcol">
                    <form method="GET">
                        <?php
                            $res = $conn->query('SELECT * FROM users');
                            while($row = $res->fetch_assoc()) {
                                foreach($res as $user){
                                    echo "<div class='user'>".$user["username"]."</div><br/>";
                                }
                            }
                        ?>
                    </form>
                </div>
                <div class="rightcol"></div>
            </div>
            <div id="stats">
                <div class="menu-stats">
                    <div id="questions-stats-button" class="menu-button selected">Pytania</div>
                    <div id="users-stats-button" class="menu-button">Użytkownicy</div>
                    <div id="rankings-stats-button" class="menu-button">Rankingi</div>
                </div>
                <div class="rightcol">
                    <div id="questions-stats">
                        <div class="leftcol">
                            <?php
                                $res = $conn->query('SELECT * FROM questions');
                                while($row = $res->fetch_assoc()) {
                                    foreach($res as $question){
                                        echo "<div class='user'>".$question["text"]."</div><br/>";
                                    }
                                }
                            ?>
                        </div>
                        <div class="rightcol"></div>
                    </div>
                    <div id="users-stats">
                        <div class="leftcol">
                            <?php
                                $res = $conn->query('SELECT * FROM users');
                                while($row = $res->fetch_assoc()) {
                                    foreach($res as $user){
                                        echo "<div class='user'>".$user["username"]."</div><br/>";
                                    }
                                }
                            ?>
                        </div>
                        <div class="rightcol"></div>
                    </div>
                    <div id="rankings-stats">
                        <div class="leftcol">
                            <h1>Najlepsi użytkownicy</h1>
                            <?php
                                $res = $conn->query('SELECT * FROM users WHERE given != 0 ORDER BY ( correct / given ) DESC LIMIT 10');
                                while($row = $res->fetch_assoc()) {
                                    foreach($res as $key=>$user){
                                        $value = $user["correct"] / $user["given"] * 100;
                                        $value = round($value, 2);
                                        $index = $key + 1;
                                        echo "<div class='data'>".$index.". ".$user["username"]." : ".$value."%</div><br/>";
                                    }
                                }
                            ?>
                        </div>
                        <div class="rightcol">
                            <h1>Najtrudniejsze pytania</h1>
                            <?php
                                $res = $conn->query('SELECT * FROM questions WHERE given != 0 ORDER BY ( correct / given ) ASC LIMIT 10');
                                while($row = $res->fetch_assoc()) {
                                    foreach($res as $key=>$question){
                                        $value = $question["correct"] / $question["given"] * 100;
                                        $value = round($value, 2);
                                        $index = $key + 1;
                                        echo "<div class='data'>".$index.". ".$question["text"]." : ".$value."%</div><br/>";
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <footer></footer>
        </div>


</body>

</html>