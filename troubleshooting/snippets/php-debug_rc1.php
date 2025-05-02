<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>PHP Debug Info</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
    h1, h2 { color: #333; }
    .success { color: green; }
    .warning { color: red; }
    pre { background: #fff; padding: 10px; border: 1px solid #ccc; overflow-x: auto; }
  </style>
</head>
<body>
<h1>🔍 PHP Debug & Server Check</h1>";

// === PHP Version ===
$minPhpVersion = '7.4';
echo "<h2>PHP Version</h2>";
echo "Current version: <strong>" . phpversion() . "</strong><br>";
if (version_compare(phpversion(), $minPhpVersion, '>=')) {
    echo "<span class='success'>✅ Minimum required version ($minPhpVersion) met.</span>";
} else {
    echo "<span class='warning'>❌ PHP version too low. Please upgrade to at least $minPhpVersion.</span>";
}

// === Loaded Extensions ===
echo "<h2>Required PHP Extensions</h2>";
$requiredExtensions = ['fileinfo', 'gd', 'exif', 'openssl'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "$ext: <span class='success'>✅ Installed</span><br>";
    } else {
        echo "$ext: <span class='warning'>❌ NOT Installed</span><br>";
    }
}

// === Important Functions Availability ===
echo "<h2>Important Functions</h2>";
$importantFunctions = [
    'finfo_open' => 'Used for MIME type checking',
    'imagepng' => 'Used for image handling / icon generation',
    'exec' => 'Optional – used in some upload scripts',
    'shell_exec' => 'Optional – used in some upload scripts'
];
foreach ($importantFunctions as $func => $desc) {
    if (function_exists($func)) {
        echo "$func(): <span class='success'>✅ Available</span> – $desc<br>";
    } else {
        echo "$func(): <span class='warning'>❌ Not available</span> – $desc<br>";
    }
}

// === File System Permissions ===
echo "<h2>File System Permissions</h2>";
$testDir = __DIR__ . '/test-write-folder';
$testFile = $testDir . '/test.txt';

if (mkdir($testDir, 0777, true)) {
    if (file_put_contents($testFile, "Test content")) {
        echo "✅ Successfully created folder and wrote file.<br>";
        unlink($testFile);
    } else {
        echo "<span class='warning'>❌ Could not write file to folder.</span><br>";
    }
    rmdir($testDir);
} else {
    echo "<span class='warning'>❌ Could not create test folder. Check permissions!</span><br>";
}

// === PHP Settings ===
echo "<h2>PHP Settings</h2>";
$settings = [
    'display_errors' => 'Should be On',
    'file_uploads' => 'Should be On',
    'upload_max_filesize' => 'Recommended >= 20M',
    'post_max_size' => 'Recommended >= 20M',
    'max_execution_time' => 'Recommended >= 30',
    'memory_limit' => 'Recommended >= 64M',
];

foreach ($settings as $key => $note) {
    $val = ini_get($key);
    echo "$key: <strong>$val</strong> – $note<br>";
}

// === Temp Directory ===
echo "<h2>Temporary Directory</h2>";
$tmpDir = sys_get_temp_dir();
echo "sys_get_temp_dir(): <strong>$tmpDir</strong><br>";
if (is_writable($tmpDir)) {
    echo "<span class='success'>✅ Temp dir is writable.</span>";
} else {
    echo "<span class='warning'>❌ Temp dir is NOT writable.</span>";
}

// === Done
echo "<br><p>✅ PHP Debug check completed.</p></body></html>";