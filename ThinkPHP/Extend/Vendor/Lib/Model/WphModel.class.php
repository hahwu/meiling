<?php
/**
 * 各竞品店铺销售一览表(完成)
 */

class WphModel extends Model
{
    private $DB_NAME = 'wph';

    function excelToMysql ($objPHPExcel)
    {
        //$this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        //M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue()));
            $data['class'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['class']))  $data['class']= $data['class']->__toString();
            $data['hz'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['xs']= $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $data['ylj']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['ndlj']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            $data['mb']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $data['dcl']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
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