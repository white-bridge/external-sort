<?php

//设置内存
ini_set ('memory_limit', '512M');

//获取文件夹下所有文件
$files = [];
$dir_handle = opendir("tmp");
while (($filename = readdir($dir_handle)) !== false) {
    if ($filename != "." && $filename != "..") {
        $files[] = "tmp/" . $filename;
    }
}
closedir($dir_handle);

echo "正在合并，请稍后";

$handle = [];

$sort_handle = fopen('resource_sort.txt', 'w');

// 存放每个小文件的第一个数字
$data = [];

for ($i = 0; $i < count($files); $i++) {
    $handle[$i] = fopen( $files[$i], "r");
}

foreach ($handle as $k => $v){
    $data[$k] = fgets($v);
}

while (1) {

    // 获取 $data 中最小数字
    $min = min($data);
    $keys = array_keys($data,$min);

    // 将最小数字添加到大文件中，如果文件读取完毕，则删除$data[$v]
    foreach ($keys as $v){
        fwrite($sort_handle, $min ." ");
        $tmp = fgets($handle[$v]);
        if ($tmp == false) {
            unset($data[$v]);
        }else{
            $data[$v] = (int)$tmp;
        }
    }

    //  所有文件读取完毕
    if (empty($data)) {
        break;
    }
}

for ($i = 0; $i < count($files); $i++) {
    fclose($handle[$i]);
}

fclose($sort_handle);