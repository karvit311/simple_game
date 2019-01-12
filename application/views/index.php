<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/application/js/site.js"></script>
        <link rel="stylesheet" href="/application/css/css.css" type="text/css">
    </head>
    <body>
        <ul id="main" style="margin: 0 auto;width: 400px;margin-top: 100px;"> 
            <li>
                <div class="form-group" style="margin: 0 auto;width: 400px;margin-top: 10px;">
                    <label for="inputdefault">Введите животное:</label>
                    <form action='' method='post'>
                        <input class="form-control form-control-sm" id="inputdefault" type="text" name="name" >
                        <input type="submit" name="submit" id="submit" >
                    </form>
                    <?php  
                    if(isset($_POST['submit'])){
                        $animal = new \Application\models\Animal($pdo);
                        $strolower_name = stripcslashes($_POST['name'] );
                        if ($strolower_name === strtoupper($strolower_name)){
                            $name = $strolower_name;
                        }else{
                            $name = mb_strtolower($strolower_name);
                        }
                        $first_letter = substr($name, 0,2);
                        $last_letter = substr($name, -2);
                        if(empty($_SESSION['next_animal']))
                        {
                            //////////////ГРАЄ З ПОЧАТКУ///////////////////
                            $session = $_SESSION['next_animal'];
                            $last_letter_next_animal = substr($session,-2);
                            $busy_animals_input = $animal->check_status($name);
                            $busy_animals_input->execute(array($name));
                            foreach ($busy_animals_input as $key => $value) { }
                            if(null === $value['name']) {
                                $animal = new \Application\models\Animal($pdo);
                                $animal->update($name);
                                $next_animals = $animal->get_nextAnimal($last_letter);
                                $next_animals->execute(array($last_letter));
                                foreach ($next_animals as $key => $next_animal) {}
                                if(NULL != $next_animal['name']){ 
                                    $busy_animals_next = $animal->check_status($next_animal['name']);
                                    $busy_animals_next->execute(array($next_animal['name']));
                                    foreach ($busy_animals_next as $key => $busy_animal_next) { }
                                    if(NULL == $busy_animal_next['name']){                                     
                                       
                                        $doesnt_exist_input = $animal->check_if_exist($name);
                                        $doesnt_exist_input->execute(array($name));
                                        foreach ($doesnt_exist_input as $key => $doesnt_exist) { }
                                        if(null == $doesnt_exist['name']) {
                                            $animal = new \Application\models\Animal($pdo);
                                            $animal->insert($name,$status);
                                        } 
                                        echo ' 
                                            <div class="alert alert-success" role="alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <p>Вы ввели: '; echo $_POST["name"]; echo '<br></p><hr>
                                                <p>Результат: '; echo $next_animal["name"]; echo '</p>
                                            </div>';
                                        $animal = new \Application\models\Animal($pdo);
                                        $animal->update($next_animal['name']);
                                        $_SESSION['next_animal'] = $next_animal['name'];
                                    }
                                }else{
                                    echo ' 
                                        <div class="alert alert-success" role="alert">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <p>Вы выиграли!</p>
                                        </div>
                                    ';
                                    $animal = new \Application\models\Animal($pdo);
                                    $animal->set_to_zero();
                                    unset($_SESSION['next_animal']);
                                    echo ' 
                                        <div class="alert alert-info" role="alert">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <p>Вы можете начать игру сначала!</p>
                                        </div>
                                    ';
                                }
                            }else{ 
                                $next_letter_must_be = substr($_SESSION['next_animal'],-2); 
                                echo ' 
                                <div class="alert alert-danger" role="alert">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <p>Слово уже было!</p>
                                </div>';
                                echo ' 
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <p>Вы ввели: "'; echo $_POST["name"]; echo '"<br></p><hr>
                                        <p>Компьютер ответил на предыдущее слово: "'; echo $_SESSION['next_animal'] .'"</p><hr>
                                        <p>Слово должно начинаться с буквы: "'; echo $next_letter_must_be; echo '"</p>
                                    </div>
                                    ';
                            }
                        }else{
                            //////ГРАЄ НЕ З ПОЧАТКУ///////////////
                            $session = $_SESSION['next_animal'];
                            $last_letter_next_animal = substr($session,-2);
                            $busy_animals_input = $animal->check_status($name);
                            $busy_animals_input->execute(array($name));
                            foreach ($busy_animals_input as $key => $value) {}
                            if(null == $value['name']) {
                                if($first_letter == $last_letter_next_animal){
                                    $animal->update($name);
                                    $animal = new \Application\models\Animal($pdo);
                                    $next_animals = $animal->get_nextAnimal($last_letter);
                                    $next_animals->execute(array($last_letter));
                                    foreach ($next_animals as $key => $next_animal) {}
                                    if(NULL != $next_animal['name']){ 
                                        $busy_animals_next = $animal->check_status($next_animal['name']);
                                        $busy_animals_next->execute(array($next_animal['name']));
                                        foreach ($busy_animals_next as $key => $busy_animal_next) { }
                                        if(NULL == $busy_animal_next['name']){   
                                            $doesnt_exist_input = $animal->check_if_exist($name);
                                            $doesnt_exist_input->execute(array($name));
                                            foreach ($doesnt_exist_input as $key => $doesnt_exist) { }
                                            if(null == $doesnt_exist['name']) {
                                                $animal = new \Application\models\Animal($pdo);
                                                $animal->insert($name,$status);
                                            }
                                            echo ' 
                                                <div class="alert alert-success" role="alert">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <p>Вы ввели: "'; echo $_POST["name"]; echo '"<br></p><hr>
                                                    <p>Результат: "'; echo $next_animal["name"]; echo '"</p>
                                                </div>';
                                            $animal = new \Application\models\Animal($pdo);
                                            $animal->update($next_animal['name']);
                                            $_SESSION['next_animal'] = $next_animal['name'];
                                        }
                                    }else{
                                        echo ' 
                                            <div class="alert alert-success" role="alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <p>Вы выиграли!</p>
                                            </div>
                                        ';
                                        $animal = new \Application\models\Animal($pdo);
                                        $animal->set_to_zero();
                                        unset($_SESSION['next_animal']);
                                        echo ' 
                                            <div class="alert alert-info" role="alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <p>Вы можете начать игру сначала!</p>
                                            </div>
                                        ';
                                    }
                                }else{
                                    $next_letter_must_be = substr($_SESSION['next_animal'],-2);
                                    echo ' 
                                        <div class="alert alert-danger" role="alert">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <p>Введенное слово неверно!</p>
                                        </div>';
                                    echo ' 
                                        <div class="alert alert-warning" role="alert">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                            <p>Вы ввели: "'; echo $_POST["name"]; echo '"<br></p><hr>
                                            <p>Слово должно начинаться с буквы: "'; echo  $next_letter_must_be.'"</p>
                                        </div>
                                        ';
                                }
                            }else{ 
                                $next_letter_must_be = substr($_SESSION['next_animal'],-2); 

                                echo ' 
                                <div class="alert alert-danger" role="alert">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <p>Слово уже было!</p>
                                </div>';
                                echo ' 
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <p>Вы ввели: "'; echo $_POST["name"]; echo '"<br></p><hr>
                                        <p>Компьютер ответил на предыдущее слово: "'; echo $_SESSION['next_animal'] .'"</p><hr>
                                        <p>Слово должно начинаться с буквы: "'; echo $next_letter_must_be; echo '"</p>
                                    </div>
                                    ';
                            }
                        }
                    }
                    ?>
                </div>
            </li>
            <li>
                <div>
                    <label id="rules-title" ><a href=""> Правила игры</a></label>
                    <div class="alert alert-info" role="alert" style="width: 400px;display: none;" id="rules" >
                        <p  >Участвуют человек и компьютер. Необходимо назвать животное, дальше получаем ответ от компьютера с вероятностью в 97.4% название животного, чьё название начинается на последнюю букву названного игроком животного. Далее ситуация повторяется, игрок должен назвать животное у которого название начинается с последней буквы названным опонентом животного. Имена не могут повторяться.Писать КИРИЛЛИЦЕЙ!</p>
                    </div>
                </div>
            </li>
        </ul>
    </body>
</html>

