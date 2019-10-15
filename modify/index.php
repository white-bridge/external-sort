<?php

//设置内存
ini_set ('memory_limit', '512M');

//自动加载
spl_autoload_register(function ($class_name) {
    require_once $class_name . '.php';
});

if (empty($argv[1])){
    echo "need a filename\n";die;
}

if (!file_exists($argv[1])){
    echo "file not exsit\n";die;
}

$file = new File($argv[1]);

$file->read();

$file->merge();