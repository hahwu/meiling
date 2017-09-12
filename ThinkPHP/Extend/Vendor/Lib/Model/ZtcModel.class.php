<?php
/**
 * 直通車模型(已完成)
 */

class ZtcModel extends Model
{
    private $DB_NAME = "ztc";

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
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue()));
            $data['sale']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['cost']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['amount']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['click']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            $data['three_money']= $objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();
            $data['three_order']= $objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();
            $data['collect']= $objPHPExcel->getActiveSheet()->getCell("V".$i)->getValue();
            $data['cart']= $objPHPExcel->getActiveSheet()->getCell("W".$i)->getValue();
            $data['store_collect']= $objPHPExcel->getActiveSheet()->getCell("Z".$i)->getValue();
            $data['day_money']= $objPHPExcel->getActiveSheet()->getCell("AB".$i)->getValue();
            echo mysql_error();
            M($this->DB_NAME)->data($data)->add();
            echo round($i/$highestRow*100,2)."%"."<br>";
        }
    }

    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '部门'){echo "A1为部门";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '组别'){echo "B1为组别";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '日期'){echo "C1为日期";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("E1")->getValue() == '店铺'){echo "E1为店铺";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '实际总销售额'){echo "H1为实际总销售额";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("I1")->getValue() == '花费'){echo "I1为花费";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("J1")->getValue() == '展现量'){echo "J1为展现量";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("K1")->getValue() == '点击量'){echo "K1为点击量";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("N1")->getValue() == '3天成交金额'){echo "N1为3天成交金额";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("O1")->getValue() == '3天成交笔数'){echo "O1为3天成交笔数";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("V1")->getValue() == '宝贝收藏数'){echo "V1为宝贝收藏数";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("W1")->getValue() == '总购物车数'){echo "W1为总购物车数";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("Z1")->getValue() == '店铺收藏数'){echo "Z1为店铺收藏数";exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AB1")->getValue() == '当天成交金额'){echo "AB1为当天成交金额";exit();}
    }
}