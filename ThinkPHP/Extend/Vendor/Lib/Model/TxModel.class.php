<?php
/**
 * 各竞品店铺销售一览表(完成)
 */

class TxModel extends Model
{
    private $DB_NAME = 'tx';

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
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['zfje'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $data['fks']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['kdj']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['zfzhl']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['xszk']= $objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();
            $data['cgcb']= $objPHPExcel->getActiveSheet()->getCell("P".$i)->getValue();
            $data['tgf']= $objPHPExcel->getActiveSheet()->getCell("AA".$i)->getValue();
            $data['sshj']= $objPHPExcel->getActiveSheet()->getCell("AJ".$i)->getValue();
            $data['jl']= $objPHPExcel->getActiveSheet()->getCell("AK".$i)->getValue();
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