<?php
/**
 * 各竞品店铺销售一览表(完成)
 */

class CompetitionModel extends Model
{
    private $DB_NAME = 'competition';

    function excelToMysql ($objPHPExcel)
    {
        $this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        //M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['industry'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if(is_object($data['industry']))  $data['industry']= $data['industry']->__toString();
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue()));
            $data['sales']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['money']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            //$data['num']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            //$data['rate']= $objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();
            echo mysql_error();
            if(M($this->DB_NAME)->where("store = '{$data['store']}' and date = '{$data['date']}'")->select()){
                M($this->DB_NAME)->data($data)->save();
            }else{
                M($this->DB_NAME)->data($data)->add();
            }
            echo round($i/$highestRow*100,2).'%<br>';
        }
    }
    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '日期'){echo 'A1为日期';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '行业'){echo 'C1为行业';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '店铺'){echo 'D1为店铺';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("E1")->getValue() == '销售量'){echo 'E1为销售量';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("F1")->getValue() == '销售额'){echo 'F1销售额';exit();}
    }
}