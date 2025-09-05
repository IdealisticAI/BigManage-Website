<?php
require '/var/www/.structure/library/base/utilities.php';
header('Content-type: text/plain');
$file = @timed_file_get_contents(
    "https://raw.githubusercontent.com/IdealisticAI/Legal-Information/refs/heads/main/terms/big_manage_terms_of_use.txt",
    3
);

if ($file === false) {
    echo "Error: Unable to retrieve the terms of service.";
} else {
    echo $file;
}