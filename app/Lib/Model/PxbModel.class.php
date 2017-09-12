<?php
/**
 * 品銷寶(已完成)
 */

class PxbModel extends Model
{
    private $DB_NAME = 'pxb';

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
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue()));
            $data['project']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            if(is_object($data['project']))  $data['project']= $data['project']->__toString();
            $data['reveal']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['click']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['clickrate']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            $data['consume']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
            $data['babycollect']= $objPHPExcel->getActiveSheet()->getCell("S".$i)->getValue();
            $data['storecollect']= $objPHPExcel->getActiveSheet()->getCell("T".$i)->getValue();
            //$data['store_rate']= $objPHPExcel->getActiveSheet()->getCell("Z".$i)->getValue();
            $data['three_report']= $objPHPExcel->getActiveSheet()->getCell("AP".$i)->getValue();
            echo mysql_error();

                echo "add";
                M($this->DB_NAME)->data($data)->add();

            echo $i/$highestRow;
        }
    }

    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '部门'){echo 'A1为部门';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '组别'){echo 'B1为组别';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '店铺'){echo 'C1为店铺';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '日期1'){echo 'D1为日期1';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '计划基本信息'){echo 'H1计划基本信息';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("I1")->getValue() == '展现'){echo 'I1为展现';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("J1")->getValue() == '点击'){echo 'J1为点击';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("L1")->getValue() == '消耗'){echo 'L1为消耗';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("K1")->getValue() == '点击率(%)'){echo 'K1为点击率(%)';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("S1")->getValue() == '宝贝收藏数'){echo 'S1为宝贝收藏数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("T1")->getValue() == '店辅收藏数'){echo 'T1为店辅收藏数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AP1")->getValue() == '3天回报金额'){echo 'AP1为3天回报金额';exit();}
    }
}