<?php
echo "Hello from installer.php<br>";

if (function_exists('finfo_open')) {
    echo "finfo_open() is available ✅<br>";
} else {
    echo "<b style='color:red;'>⚠️ finfo_open() is NOT available!</b><br>";
}

if (function_exists('imagepng')) {
    echo "imagepng() is available ✅<br>";
} else {
    echo "<b style='color:red;'>⚠️ imagepng() is NOT available!</b><br>";
}