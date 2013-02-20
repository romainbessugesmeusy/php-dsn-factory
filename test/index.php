<?php

require '../vendor/autoload.php';

use RBM\Utils\Dsn;

$f = new Dsn(Dsn::SQL_SERVER_DBLIB, [
    "host" => "127.0.0.1",
    "port" => "3306",
    "dbname" => "kloook",
]);

echo $f;