<?php
require_once 'vendor/autoload.php';
$d = Dotenv\Dotenv::createImmutable(__DIR__);
$d->load();
$p = new PDO('mysql:host='.$_ENV['DBHOST'].';dbname='.$_ENV['DBNAME'], $_ENV['DBUSER'], $_ENV['DBPASS']);

echo "=== therapist columns ===\n";
foreach($p->query('DESCRIBE therapist')->fetchAll(PDO::FETCH_ASSOC) as $c) {
    echo $c['Field'].' | '.$c['Type']."\n";
}

echo "\n=== active therapist rows ===\n";
echo $p->query('SELECT COUNT(*) FROM therapist WHERE deleted=0')->fetchColumn()."\n";

echo "\n=== sample therapist data ===\n";
foreach($p->query('SELECT id,name,service_id FROM therapist WHERE deleted=0 LIMIT 5')->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo $r['id'].' | '.$r['name'].' | service:'.$r['service_id']."\n";
}

echo "\n=== site_team_photo table ===\n";
try {
    echo $p->query('SELECT COUNT(*) FROM site_team_photo')->fetchColumn()." rows\n";
} catch(Exception $e) {
    echo "MISSING: ".$e->getMessage()."\n";
}
