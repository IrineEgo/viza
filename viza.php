<?php
    if(!isset($argv[1])) { exit("Укажите страну.\n"); }
    $csv = file('https://data.gov.ru/opendata/7704206201-country/data-20180609T0649-structure-20180609T0649.csv?encoding=UTF-8');
    $file = file_put_contents('./viza.csv',$csv);
    $input = getCorrectCountry($csv, $argv[1]);
    $result = "Страна не найдена.\n"; 
    foreach($csv as $country) {
        $country = str_getcsv($country,','); 
        if($country[1] == $input) {
            $result = $country[1] . ": " . $country[2] . "\n";
        }
    }
 
    echo $result;
 
    /* levenshtein */
 
    function getCorrectCountry($csv, $input) 
    {
        $closest = ''; 
        $shortest = -1;
        foreach ($csv as $row) {
            $country = str_getcsv($row,',');
            $lev = levenshtein($input, $country[1]);
            if ($lev == 0) {
                $closest = $country[1];
                $shortest = 0;
                return $closest;
            }
            if ($lev <= $shortest || $shortest < 0) {
                $closest  = $country[1];
                $shortest = $lev;
            }
        }
        return $closest;
    }
