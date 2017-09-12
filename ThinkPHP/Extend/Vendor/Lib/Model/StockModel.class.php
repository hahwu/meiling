<?php
/**
 * 各竞品店铺销售一览表(完成)
 */

class StockModel extends Model
{
    private $DB_NAME = 'stock';

    function excelToMysql ($objPHPExcel)
    {
        //$this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        //M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=3;$i<=$highestRow;$i++)
        {
            $data['nature'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if(is_object($data['nature']))  $data['nature']= $data['nature']->__toString();
            $data['class'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['class']))  $data['class']= $data['class']->__toString();
            $data['cost'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['hsku']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $data['stock']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['stockv']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['stockr']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['ksku']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
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
            if(M($this->DB_NAME)->where("store = '{$data['store']}' and date = '{$data['date']}'")->select()){
                M($this->DB_NAME)->data($data)->save();
            }else{
                M($this->DB_NAME)->data($data)->add();
            }
            echo round($i/$highestRow*100,2).'%<br>';
        }
    }

}