<?php

ob_start();
$_GET['do'] = 'getFullStatistik';
require_once(dirname(__FILE__) . '/api.php');

$content = ob_get_contents();
ob_end_clean();

$content = json_decode($content, true);

echo '<pre>';
print_r($content);
echo '</pre>';
