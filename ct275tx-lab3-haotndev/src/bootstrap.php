<?php

require_once 'functions.php';

require_once __DIR__ . '/../libraries/Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass;
$loader->register();

$loader->addNamespace('CT275\Labs', __DIR__ . '/classes');

try {
    $PDO = (new CT275\Labs\PDOFactory())->create([
        'dbhost' => 'localhost',
        'dbname' => 'ct275_lab3',
        'dbuser' => 'root',
        'dbpass' => '0000'
    ]);
} catch (\Throwable $th) {
    echo 'Không thể kết nối đến MySQL, kiểm tra lại thông tin lại thông tin kết nối.<br>';
    exit("<pre>$th</pre>");
}
