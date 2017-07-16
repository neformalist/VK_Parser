<?php

ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

$count = 100000;
$offset = 100000000;

$curl = new \Parser\Curl();

$parser = new \Parser\Parser(\DataSource\Database::getInstance(), $curl, $count, $offset);

$parser->run();


