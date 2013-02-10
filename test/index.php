<?php

require '../vendor/autoload.php';

use RBM\Utils\DsnFactory;

$f = new DsnFactory(DsnFactory::MYSQL, [
    "host" => "127.0.0.1",
    "port" => "3306",
    "dbname" => "kloook",
]);

echo $f->getDsn();