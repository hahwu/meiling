<?php
/**
 * 各店铺销售一览表(完成)
 */

class CompletionModel extends Model
{
    private $DB_NAME = 'completion';

    function excelToMysql ($objPHPExcel)
    {
        $this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['department'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if(is_object($data['department']))  $data['department']= $data['department']->__toString();
            $data['team'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['team']))  $data['team']= $data['team']->__toString();
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue()));
            $data['money']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            $data['scalp']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $data['target']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['starget']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            $data['actsale']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['actssale']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
            echo mysql_error();
            M($this->DB_NAME)->data($data)->add();
            echo $i/$highestRow;
        }
    }

    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '部门'){echo 'A1为部门';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '分组'){echo 'B1为分组';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '店铺'){echo 'D1为店铺';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("E1")->getValue() == '年/月'){echo 'E1为年/月';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("F1")->getValue() == '支付金额'){echo 'F1为支付金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("G1")->getValue() == '刷单金额'){echo 'G1为刷单金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("I1")->getValue() == '销售流水目标'){echo 'I1为销售流水目标';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("K1")->getValue() == '销售净额目标'){echo 'K1为销售净额目标';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '实际销售流水'){echo 'H1为实际销售流水';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("L1")->getValue() == '实际销售净额'){echo 'L1为实际销售净额';exit();}
    }
}