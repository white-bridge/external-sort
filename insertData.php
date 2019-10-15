<?php

$handle = fopen("resource.txt", "a");

for ($m = 0; $m < 10; $m++) {
    $str = "";
    for ($i = 0 ; $i < 10000000; $i++) {
        $str .= rand(1,10000) . " ";
    }

    fwrite($handle,$str);
}

fclose($handle);