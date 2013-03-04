<?php

require '../vendor/autoload.php';

use RBM\Utils\Dsn;

$dsn = new Dsn(Dsn::SQL_SERVER, [
    "host" => "127.0.0.1",
    "port" => "3306",
    "dbname" => "kloook",
]);

echo $dsn;