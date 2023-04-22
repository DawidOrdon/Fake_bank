<?php
    session_start();
    include_once("./scripts/connect.php");
    $db=connect();
    function Generate_patterns($number_of_patterns,$password_length,$number_of_chars)
    {
        $patterns_array = array();
        for($i=0;$i<$number_of_patterns;$i++)
        {
            do //generowanie kolejnych patternow
            {
                $pattern=Generate_pattern($password_length,$number_of_chars,$patterns_array);
            }while($pattern==False);
            $patterns_array[]=$pattern;//dodanie kolejnego patternu do tablicy
        }
        return($patterns_array);
    }
    function Generate_pattern($password_length, $number_of_chars,$password_array)
    {
        $pass_array = array();
        for($i=0;$i<$number_of_chars;$i++)
        {
            do //generowanie kolejnych elementów patternu
            {
                $number=gen($password_length, $pass_array);
            }while($number==False);

            $pass_array[]=$number;// dodanie kolejnego numeru do patternu
        }
        sort($pass_array);
        
        if(is_numeric(array_search($pass_array,$password_array)))//dany pattern został odnaleziony
        {
            return(False);
        }
        else
        {
            return($pass_array); //zwracanie patternu
        }
        
    }
    function Gen($password_length,$pass_array)
    {
        $rand=rand(0,$password_length); //generowanie liczby
        if(is_numeric(array_search($rand,$pass_array,$stric=false)))//sprawdzanie czy liczba wczesniej nie wystapila 
        {
            return (false);
        }
        else
        {
            return ($rand);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        //generowanie patternów
        $number_of_patterns=100; //ilosc patternów dla danej dłuości hasła 
        $password_length=20; //długość hasła
        $number_of_chars=12; //ilość znaków uwzględniona w patternie
        
        $password_array=Generate_patterns($number_of_patterns,$password_length,$number_of_chars);
        foreach($password_array as $pattern_array)
        {
            //dodawanie nowego patternu
            $sql = $db->prepare("INSERT INTO `patterns` (`number_of_characters`) VALUES (?)");
            $sql->bind_param("i",$password_length);
            $sql->execute();//dodanie usera
            $pattern_id = $db->insert_id;
            //przypisywanie numerów znaków do patternu
            foreach($pattern_array as $pattern_char)
            {
                $sql = $db->prepare("INSERT INTO `pattern_char` (`id_pattern`, `pass_char`) VALUES (?, ?)");
                $sql->bind_param("is",$pattern_id,$pattern_char);
                $sql->execute();//dodanie usera
            }
            echo"<br />";
        }
    ?>
</body>
</html>