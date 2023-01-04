<?php

$time = microtime(true);
$option = ['cost' => 10, ];
echo password_hash('ahmed', PASSWORD_BCRYPT, $option);
echo "\nTook ". (microtime(true)-$time) . " sec\n";
