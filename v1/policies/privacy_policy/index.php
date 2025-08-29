<?php
require '/var/www/.structure/library/base/utilities.php';
header('Content-type: text/plain');
$file = @timed_file_get_contents(
    "https://raw.githubusercontent.com/IdealisticAI/Legal-Information/refs/heads/main/policies/privacy_policy.txt",
    3
);

if ($file === false) {
    echo "Error: Unable to retrieve the privacy policy.";
} else {
    echo $file;
}
