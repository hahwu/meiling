<?php

/**
 * 钻展模型(已完成)
 */

class StockModel extends Model
{
    private $DB_NAME = "stock";

    function excelToMysql ($objPHPExcel)
    {
        //$this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        M($this->DB_NAME)->where("id > 0")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {

            $data['nature'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            $data['class']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $data['cost']= $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['hsku']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $data['ksku']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['stock']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['stockv']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['stockr']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['xsku']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            $data['wp']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
            $data['tx']= $objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue();
            $data['tsale']= $objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();
            $data['salecost']= $objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();
            $data['sye']= $objPHPExcel->getActiveSheet()->getCell("P".$i)->getValue();
            $data['saler']= $objPHPExcel->getActiveSheet()->getCell("Q".$i)->getValue();
            $data['total']= $objPHPExcel->getActiveSheet()->getCell("T".$i)->getValue();
            $data['cxb']= $objPHPExcel->getActiveSheet()->getCell("U".$i)->getValue();
            $data['ml']= $objPHPExcel->getActiveSheet()->getCell("V".$i)->getValue();
            echo mysql_error();
            M($this->DB_NAME)->data($data)->add();
            echo $i/$highestRow;
        }
    }




}