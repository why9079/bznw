<?php
//require 'the_jpush.php';
use TheJpush\the_jpush;
$a = new the_jpush();

$message = "日你仙人板板~~~~";
        
$b = $a->push_all($message);

echo $b;