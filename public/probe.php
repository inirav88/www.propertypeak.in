<?php
header('Content-Type: text/plain');
echo "Max Input Vars: " . ini_get('max_input_vars') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";
