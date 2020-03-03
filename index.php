<?php
$filePath = 'matches.csv';
$matches = [];

$handle = fopen($filePath, 'r');
$headers = fgetcsv($handle, 1000);
while ($line = fgetcsv($handle, 1000)) {
    $matches[] = array_combine($headers, $line);
}

require('vue.php');
