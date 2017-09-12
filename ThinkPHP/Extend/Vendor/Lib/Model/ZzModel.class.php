<?php

/**
 * 钻展模型(已完成)
 */

class ZzModel extends Model
{
    private $DB_NAME = "zz";

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
				$data['team'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
                if(is_object($data['team']))  $data['team']= $data['team']->__toString();
				$data['store'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
                if(is_object($data['store']))  $data['store']= $data['store']->__toString();
                $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue()));
                $data['reveal']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
				$data['click']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
				$data['consume']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
				$data['click_rate']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
				$data['click_price']= $objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue();
				$data['visitor']= $objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();
				$data['baby_collect']= $objPHPExcel->getActiveSheet()->getCell("S".$i)->getValue();
				$data['store_collect']= $objPHPExcel->getActiveSheet()->getCell("T".$i)->getValue();
				$data['cart']= $objPHPExcel->getActiveSheet()->getCell("U".$i)->getValue();
				$data['order']= $objPHPExcel->getActiveSheet()->getCell("X".$i)->getValue();
				$data['order_money']=$objPHPExcel->getActiveSheet()->getCell("Y".$i)->getValue();
				$data['clickrate'] =$objPHPExcel->getActiveSheet()->getCell("Z".$i)->getValue();
				$data['report']=$objPHPExcel->getActiveSheet()->getCell("AA".$i)->getValue();
				$data['three_report']=$objPHPExcel->getActiveSheet()->getCell("AB".$i)->getValue();
                echo mysql_error();
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
        if(!$objPHPExcel->getActiveSheet()->getCell("I1")->getValue() == '展现'){echo 'I1为展现';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("J1")->getValue() == '点击'){echo 'J1为点击';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("K1")->getValue() == '消耗'){echo 'K1为消耗';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("L1")->getValue() == '点击率(%)'){echo 'L1为点击率(%)';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("M1")->getValue() == '点击单价(元)'){echo 'M1为点击单价(元)';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("O1")->getValue() == '访客'){echo 'O1为访客';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("S1")->getValue() == '宝贝收藏数'){echo 'S1为宝贝收藏数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("T1")->getValue() == '店辅收藏数'){echo 'T1为店辅收藏数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("U1")->getValue() == '添加购物车量'){echo 'U1为添加购物车量';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("X1")->getValue() == '成交订单量'){echo 'X1为成交订单量';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("Y1")->getValue() == '成交订单金额'){echo 'Y1为成交订单金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("Z1")->getValue() == '点击转化率(%)'){echo 'Z1为点击转化率(%)';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AA1")->getValue() == '投资回报率'){echo 'AA1为投资回报率';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AB1")->getValue() == '3天回报率'){echo 'AB1为3天回报率';exit();}
    }


}