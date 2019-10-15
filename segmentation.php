<?php

//设置内存
ini_set ('memory_limit', '512M');

$handle = fopen("resource.txt", "r");

echo "正在分割文件，请稍后\n";

// 文件编号
$fileNum = 1;
// 创建文件夹
if(!file_exists("tmp")){
    mkdir("tmp");
}

while (!feof($handle)) {
    // 每个文件10M
    $length = 1024 * 1024 * 10;

    $buffer = fgets($handle, $length);
    // 防止数字被分割开
    while (substr($buffer,-1) !== ' '){
        $char = fgetc($handle);
        $buffer .= $char;
    }
    $buffer = trim($buffer);
    $list = explode(' ', $buffer);

    sort($list);

    $fileName = $fileNum . '.txt';


    $file = fopen("tmp/$fileName", 'w');

    // 以 \n 分割是为了方便用fgets读取数据
    $string = fwrite($file, implode("\n", $list) );

    fclose($file);

    ++$fileNum;

    echo "创建文件: tmp/$fileName\n";
}

fclose($handle);
$fileNum--;
echo "分割文件完成，共{$fileNum}个文件\n";