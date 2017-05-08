<?php

//echo '<pre>';

spl_autoload_register();

$output = require './router.php';
echo $output;
