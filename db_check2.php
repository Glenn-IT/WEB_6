<?php
error_reporting(0);
require 'vendor/autoload.php';
$d = Dotenv\Dotenv::createImmutable(__DIR__);
$d->load();
$p = new PDO('mysql:host='.$_ENV['DBHOST'].';dbname='.$_ENV['DBNAME'], $_ENV['DBUSER'], $_ENV['DBPASS'] ?? '');
echo "=== main_order columns ===\n";
foreach($p->query('DESCRIBE main_order')->fetchAll(PDO::FETCH_ASSOC) as $c) {
    echo $c['Field'].' | '.$c['Type']."\n";
}
