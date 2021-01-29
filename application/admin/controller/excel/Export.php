<?php

namespace app\admin\controller\excel;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\Controller;

class Export extends Controller
{
    public function index($header=array(),$data)
    {
        $filename = 'export.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if(!empty($header)){
            switch(gettype($header)){
                case 'string':
                    $header = explode(',',$header);
            }
            foreach($header as $k => $v){
                $sheet->setCellValue($this -> toChar($k).'1', $v);
            }
        }
        if(!empty($data)) {
            switch(gettype($data)){
                case 'string':
                    $data = json_decode($data);
            }
            foreach ($data as $k => $v) {
                $num = 0;
                foreach ($v as $n) {
                    if ($n > 10000000000) {
                        $sheet->setCellValueExplicit($this->toChar($num) . ($k + 2), $n, DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValue($this->toChar($num) . ($k + 2), $n);
                    }
                    $spreadsheet->getActiveSheet()->getColumnDimension($this->toChar($num))->setAutoSize(true);
                    $num++;
                }
            }
        }
        $this -> load($spreadsheet,$filename);
//        unlink($filename);
    }

    public function toChar($int)
    {
        $i = 65+$int;
        $ch = chr($i);
        return $ch;
    }

    public function load($spreadsheet,$filename)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        header('Content-Disposition: attachment;filename="' . $filename.'"');//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output' );
    }
}