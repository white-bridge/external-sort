<?php

class File implements FileInterface {

    private $file;

    private $fileList = [];

    private $dir = "tmp";

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function read()
    {

        $handle = fopen($this->file, "r");

        echo "正在分割文件，请稍后\n";

        // 文件编号
        $fileNum = 1;
        // 创建文件夹
        if(!file_exists($this->dir)){
            mkdir($this->dir);
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

            $this->write($this->dir . '/' . $fileName,$list);

            ++$fileNum;

            $this->fileList[] = $fileName;
            echo "创建文件: {$this->dir}/$fileName\n";
        }

        fclose($handle);
        $fileNum--;
        echo "分割文件完成，共{$fileNum}个文件\n";
    }

    public function write($file,$list)
    {
        //
        $file = fopen($file, 'w');

        fwrite($file, implode("\n", $list));

        fclose($file);
    }

    public function merge(){

        echo "正在合并，请稍后\n";

        $handle = [];

        $sort_handle = fopen("sort_" . $this->file, 'w');

        // 存放每个小文件的第一个数字
        $data = [];

        for ($i = 0; $i < count($this->fileList); $i++) {
            $handle[$i] = fopen( $this->dir . "/" . $this->fileList[$i], "r");
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

        for ($i = 0; $i < count($this->fileList); $i++) {
            fclose($handle[$i]);
        }

        fclose($sort_handle);
        echo "排序完成\n";

        //删除所有小文件
        $this->deldir();
    }

    private function  deldir() {
        //先删除目录下的文件：
        $dh=opendir($this->dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$this->dir."/".$file;
                unlink($fullpath);
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($this->dir)) {
            return true;
        } else {
            return false;
        }
    }
}