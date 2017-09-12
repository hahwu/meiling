<?php

/**
 * 钻展模型(已完成)
 */

class WphModel extends Model
{
    private $DB_NAME = "wph";

    function excelToMysql ($objPHPExcel)
    {
        //$this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {

            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue()));
            $data['class']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $data['hz']= $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['xs']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $data['ylj']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['ndlj']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            $data['mb']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $data['dcl']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            echo mysql_error();
            M($this->DB_NAME)->data($data)->add();
            echo $i/$highestRow;
        }
    }




}