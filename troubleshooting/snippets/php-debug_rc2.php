<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start HTML output
echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>🔍 PHP Module & Server Debugger</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
    h1, h2 { color: #333; }
    .success { color: green; }
    .warning { color: red; }
    pre { background: #fff; padding: 10px; border: 1px solid #ccc; overflow-x: auto; }
  </style>
</head>
<body>
<h1>🔍 PHP Module & Server Debugger</h1>";

$debugData = [];

// === PHP Version ===
$minPhpVersion = '7.4';
$phpVersion = phpversion();
echo "<h2>🔧 PHP Version</h2>";
echo "Current version: <strong>$phpVersion</strong><br>";
$debugData['php_version'] = $phpVersion;

if (version_compare($phpVersion, $minPhpVersion, '>=')) {
    echo "<span class='success'>✅ Minimum required version ($minPhpVersion) met.</span>";
} else {
    echo "<span class='warning'>❌ PHP version too low. Please upgrade to at least $minPhpVersion.</span>";
}

// === Required Extensions from apt install ===
$requiredExtensions = [
    'curl'        => 'Used for HTTP requests',
    'mbstring'    => 'Multibyte string handling',
    'xml'         => 'XML parsing and generation',
    'zip'         => 'Zip file handling',
    'intl'        => 'Internationalization support',
    'bcmath'      => 'Arbitrary precision math functions',
    'mysqli'      => 'MySQL database driver',
    'pgsql'       => 'PostgreSQL database driver',
    'sqlite3'     => 'SQLite3 database support',
    'redis'       => 'Redis extension',
    'gd'          => 'Image processing and GD library',
    'imagick'     => 'ImageMagick integration',
    'xdebug'      => 'Debugging tool',
    'soap'        => 'SOAP web services',
    'json'        => 'JSON encoding/decoding',
    'tokenizer'   => 'Tokenize PHP source code',
    'fileinfo'    => 'File type detection',
    'exif'        => 'Image meta-data parser',
    'opcache'     => 'PHP byte-code cache'
];

echo "<h2>🧩 Installed PHP Extensions</h2>";
$debugData['extensions'] = [];

foreach ($requiredExtensions as $ext => $desc) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? '✅ Installed' : '❌ NOT Installed';
    $color = $loaded ? 'success' : 'warning';

    echo "$ext: <span class='$color'>$status</span> – $desc<br>";
    $debugData['extensions'][$ext] = $loaded;
}

// === Important Functions Availability ===
echo "<h2>⚙️ Important Functions</h2>";
$importantFunctions = [
    'finfo_open' => 'Used for MIME type checking',
    'imagepng' => 'Used for image handling / icon generation',
    'exec' => 'Optional – used in some upload scripts',
    'shell_exec' => 'Optional – used in some upload scripts'
];
$debugData['functions'] = [];

foreach ($importantFunctions as $func => $desc) {
    $exists = function_exists($func);
    $status = $exists ? '✅ Available' : '❌ Not available';
    $color = $exists ? 'success' : 'warning';

    echo "$func(): <span class='$color'>$status</span> – $desc<br>";
    $debugData['functions'][$func] = $exists;
}

// === File System Permissions ===
echo "<h2>💾 File System Permissions</h2>";
$testDir = __DIR__ . '/test-write-folder';
$testFile = $testDir . '/test.txt';
$debugData['permissions'] = [];

if (mkdir($testDir, 0777, true)) {
    if (file_put_contents($testFile, "Test content")) {
        echo "✅ Successfully created folder and wrote file.<br>";
        $debugData['permissions']['write_success'] = true;
        unlink($testFile);
    } else {
        echo "<span class='warning'>❌ Could not write file to folder.</span><br>";
        $debugData['permissions']['write_success'] = false;
    }
    rmdir($testDir);
} else {
    echo "<span class='warning'>❌ Could not create test folder. Check permissions!</span><br>";
    $debugData['permissions']['folder_create'] = false;
}

// === PHP Settings ===
echo "<h2>🛠️ PHP Settings</h2>";
$settings = [
    'display_errors' => 'Should be On',
    'file_uploads' => 'Should be On',
    'upload_max_filesize' => 'Recommended >= 20M',
    'post_max_size' => 'Recommended >= 20M',
    'max_execution_time' => 'Recommended >= 30',
    'memory_limit' => 'Recommended >= 64M',
];

$debugData['php_settings'] = [];

foreach ($settings as $key => $note) {
    $val = ini_get($key);
    echo "$key: <strong>$val</strong> – $note<br>";
    $debugData['php_settings'][$key] = $val;
}

// === Temp Directory ===
echo "<h2>📁 Temporary Directory</h2>";
$tmpDir = sys_get_temp_dir();
echo "sys_get_temp_dir(): <strong>$tmpDir</strong><br>";
$writable = is_writable($tmpDir);
$debugData['temp_dir'] = [
    'path' => $tmpDir,
    'writable' => $writable
];

if ($writable) {
    echo "<span class='success'>✅ Temp dir is writable.</span>";
} else {
    echo "<span class='warning'>❌ Temp dir is NOT writable.</span>";
}

// === JSON Summary Output ===
echo "<h2>📊 JSON Summary Output</h2>";
echo "<pre>" . json_encode($debugData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>";

// End HTML
echo "<br><p>✅ PHP Debug check completed.</p></body></html>";