<?php
    $conn = new mysqli("localhost", "root", "", "4ic1");
    if($conn -> connect_error) die('Nie można połączyć się z serwerem');
    
    if(empty($_GET['id'])){
        $conn->query("INSERT INTO questions (id, text, anwser_a, anwser_b, anwser_c, anwser_d, right_anwser, given, correct) VALUES (NULL, 'Wpisz tekst pytania', 'Tekst odpowiedzi A', 'Tekst odpowiedzi B', 'Tekst odpowiedzi C', 'Tekst odpowiedzi D', 'a', '0', '0')");
        $id = mysqli_insert_id($conn);
        echo 
            "<div id='question-edit'>
                <form method='POST'>
                    <label> ID: 
                        <input type='text' name='id' readonly value='".$id."'>
                    </label>
                    <label> Pytanie: 
                        <textarea name='text'>Wpisz tekst pytania</textarea>
                    </label>
                    <label> Odp. A: 
                        <textarea name='anwser_a'>Tekst odpowiedzi A</textarea>
                    </label>
                    <label> Odp. B: 
                    <textarea name='anwser_b'>Tekst odpowiedzi B</textarea>
                    </label>
                    <label> Odp. C: 
                    <textarea name='anwser_c'>Tekst odpowiedzi C</textarea>
                    </label>
                    <label> Odp. D: 
                    <textarea name='anwser_d'>Tekst odpowiedzi D</textarea>
                    </label>
                    <label> Właściwa odp.: 
                        <input type='text' name='right_anwser' value='a'>
                    </label>
                    <label> Dane odp.: 
                        <input type='text' name='given' value='0'>
                    </label>
                    <label> Poprawne odp.: 
                        <input type='text' name='correct' value='0'>
                    </label>
                    <input class='update-user' type='submit' name='updateQuestion' value='DODAJ' >
                    <input class='delete-user' type='submit' name='deleteQuestion' value='ANULUJ' >
                </form>
            </div><br/>";
    }
    else{
        $res = $conn->query('SELECT * FROM questions WHERE id="'.$_GET['id'].'"');
        while($row = $res->fetch_assoc()) {
            foreach($res as $question){
                echo 
                "<div id='question-edit'>
                    <form method='POST'>
                        <label> ID: 
                            <input type='text' name='id' readonly value='".$question['id']."'>
                        </label>
                        <label> Pytanie: 
                            <textarea name='text'>".$question['text']."</textarea>
                        </label>
                        <label> Odp. A: 
                            <textarea name='anwser_a'>".$question['anwser_a']."</textarea>
                        </label>
                        <label> Odp. B: 
                        <textarea name='anwser_b'>".$question['anwser_b']."</textarea>
                        </label>
                        <label> Odp. C: 
                        <textarea name='anwser_c'>".$question['anwser_c']."</textarea>
                        </label>
                        <label> Odp. D: 
                        <textarea name='anwser_d'>".$question['anwser_d']."</textarea>
                        </label>
                        <label> Właściwa odp.: 
                            <input type='text' name='right_anwser' value='".$question['right_anwser']."'>
                        </label>
                        <label> Dane odp.: 
                            <input type='text' name='given' value='".$question['given']."'>
                        </label>
                        <label> Poprawne odp.: 
                            <input type='text' name='correct' value='".$question['correct']."'>
                        </label>
                        <input class='update-user' type='submit' name='updateQuestion' value='AKTUALIZUJ' >
                        <input class='delete-user' type='submit' name='deleteQuestion' value='USUŃ' >
                    </form>
                </div><br/>";
            }
        }
    }
?>