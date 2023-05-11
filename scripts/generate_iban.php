<?php
    function generate_iban($fake_bank_iban_number=12344356,$country_code='PL')
    {
        require('./iban_letter.php');
        $iban_account_number=rand(1000000000000000,9999999999999999);//wygenerowanie losowej 16 cyfrowej liczby
        $account_number= $country_code.'00'.$fake_bank_iban_number.$iban_account_number;//stowrzenie podstawowego ciągu do obliczenia sumy kontrolnej
        $account_number=strtolower($account_number);//zmiana liter na male
        $account_number=str_split($account_number);//zmiana stringa na tablice
        $account_iban='';
        for($i=4;$i<count($account_number);$i++)//tworzenie ciągu znaków do obliczania sumy, pierwsze 4 znaki sa pominiete
        {
            $account_iban.=$account_number[$i];
        }
        for($i=0;$i<2;$i++)//zamiana liter kraju na wartość liczbowa i dodanie na koniec ciagu
        {
            $account_iban.=$litery[$account_number[$i]];
        }
        for($i=2;$i<4;$i++)//dodanie 3 i 4 znaku do ciągu
        {
            $account_iban.=$account_number[$i];
        }
        $account_iban=str_split($account_iban); //zamiana ciagu na tablice aby podzielic stringa na 1/2
        $account_iban1='';
        $account_iban2='';
        for($i=0;$i<15;$i++)
        {
            $account_iban1.=$account_iban[$i];
        }
        for($i=15;$i<30;$i++)
        {
            $account_iban2.=$account_iban[$i];
        }
        $account_iban1=$account_iban1%97;//obliczenie reszty z dzielenia 
        $account_iban2=$account_iban1.$account_iban2;
        $suma=98-$account_iban2%97;
        if($suma<10)
        {
            $suma='0'.$suma;
        }
        $iban= "PL".$suma.$fake_bank_iban_number.$iban_account_number;
        echo $iban."<br />";
        return($iban);
    }
    

    function check_iban($iban)
    {
        require('./iban_letter.php');
        $iban=strtolower($iban);//zmiana liter na male
        $iban=str_split($iban);//zmiana stringa na tablice
        $account_iban='';
        for($i=4;$i<count($iban);$i++)//tworzenie ciągu znaków do obliczania sumy, pierwsze 4 znaki sa pominiete
        {
            $account_iban.=$iban[$i];
        }
        for($i=0;$i<2;$i++)//zamiana liter kraju na wartość liczbowa i dodanie na koniec ciagu
        {
            $account_iban.=$litery[$iban[$i]];
        }
        for($i=2;$i<4;$i++)//dodanie 3 i 4 znaku do ciągu
        {
            $account_iban.=$iban[$i];
        }
        $account_iban=str_split($account_iban); //zamiana ciagu na tablice aby podzielic stringa na 1/2
        $account_iban1='';
        $account_iban2='';
        for($i=0;$i<15;$i++)
        {
            $account_iban1.=$account_iban[$i];
        }
        for($i=15;$i<30;$i++)
        {
            $account_iban2.=$account_iban[$i];
        }
        $account_iban1=$account_iban1%97;//obliczenie reszty z dzielenia 
        $account_iban2=$account_iban1.$account_iban2;
        $iban_modulo=$account_iban2%97;
        if($iban_modulo==1)//weryfikacja iban
        {
            echo"iban jest poprawny";
        }
        else
        {
            echo"cos poszlo nie tak";
        }
    }
    $iban=generate_iban();
    check_iban($iban);
?>