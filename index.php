<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Tester</title>
    <script>

    </script>
</head>

<body>
    <?php
        session_start();
    ?>
    <div id="container-center">
        <header>
            <h1>Tester - poznaj swoje umiejętności</h1>
        </header>
        <div id="content">
            <div id="form-cont">
                <h2>Zaloguj się</h2>
                <form id="login" method="POST">
                    <label>Login: <input type="text" name="login"></label>
                    <label>Hasło: <input type="password" name="password"></label>
                    <input type="submit" name="submit" value="LOG IN">
                </form>
                <p class="error">
                    <?php

                    $conn = new mysqli("localhost", "root", "", "4ic1");
                    if($conn -> connect_error) die('Nie można połączyć się z serwerem');

                    if(isset($_POST['submit'])){
                        
                        $res = $conn->query('SELECT * FROM users WHERE username="'.$_POST['login'].'"');

                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {

                                if(password_verify($_POST['password'], $row["password"])){
                                    if($row["admin"] == 1){
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["admin"] = true;
                                        $_SESSION["id"] = $row["id"];
                                        $_SESSION["username"] = $row["username"];
                                        header("location: admin.php");
                                    }
                                    else{
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["admin"] = false;
                                        $_SESSION["id"] = $row["id"];
                                        $_SESSION["username"] = $row["username"];
                                        header("location: test.php");
                                    }
                                } else {
                                    echo "Wrong password!";
                                }
                            }
                        } else {
                            if(strlen($_POST['login']) >= 6 && strlen($_POST['password']) >= 6){
                                $passHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $sql = 'INSERT INTO users (username, password, admin) VALUES ("'.$_POST['login'].'", "'.$passHash.'", "0")';
                                
                                if ($conn->query($sql) === TRUE) {

                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $row["id"];
                                    $_SESSION["username"] = $row["username"];
                                    header("location: test.php");
                                    //echo "<script>window.location = 'test.php'</script>";
                                } else {
                                    echo "Error: ".$conn->error;
                                }
                            }
                            else{
                                echo "Login i haslo muszą mieć co najmniej 6 znaków!";
                            }
                        }
                        
                    }
                ?>
                </p>
            </div>
        </div>
        <footer></footer>
    </div>


</body>

</html>