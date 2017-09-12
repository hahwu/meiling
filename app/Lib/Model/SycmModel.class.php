<?php
/**
 * 生意参谋模型(已完成)
 */

class SycmModel extends Model
{
    private $DB_NAME = 'sycm';

    function excelToMysql ($objPHPExcel)
    {
        $this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        //M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['department'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if(is_object($data['department']))  $data['department']= $data['department']->__toString();
            $data['team'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['team']))  $data['team']= $data['team']->__toString();
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue()));
            $data['visitor']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['scalp']= $objPHPExcel->getActiveSheet()->getCell("AJ".$i)->getValue();
            $data['scalp_order']= $objPHPExcel->getActiveSheet()->getCell("AI".$i)->getValue();
            $data['money']= $objPHPExcel->getActiveSheet()->getCell("S".$i)->getValue();
            $data['money_order']= $objPHPExcel->getActiveSheet()->getCell("V".$i)->getValue();
            $data['price']= $objPHPExcel->getActiveSheet()->getCell("Y".$i)->getValue();
            $data['rate']= $objPHPExcel->getActiveSheet()->getCell("AK".$i)->getValue();
            $data['refund']= $objPHPExcel->getActiveSheet()->getCell("AL".$i)->getValue();
            $data['ztccost']= $objPHPExcel->getActiveSheet()->getCell("AN".$i)->getValue();
            $data['zzcost']= $objPHPExcel->getActiveSheet()->getCell("AQ".$i)->getValue();
            $data['tbkcost']= $objPHPExcel->getActiveSheet()->getCell("AX".$i)->getValue();
            $data['pxbcost']= $objPHPExcel->getActiveSheet()->getCell("AU".$i)->getValue();
            $data['jhscost']= $objPHPExcel->getActiveSheet()->getCell("AY".$i)->getValue();
            echo mysql_error();
            if(M($this->DB_NAME)->where("department = '{$data['department']}' and date = '{$data['date']}' and store = '{$data['store']}'")->select()){
                echo "update";
                M($this->DB_NAME)->data($data)->save();
            }else{
                echo "add";
                M($this->DB_NAME)->data($data)->add();
            }
            if(round($i/$highestRow*100)%5 == 0)
            {
                echo round($i/$highestRow*100,2)."%".$i.'/'.$highestRow."行<br>";
            }
        }
    }
    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '部门'){echo 'A1为部门';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '分组'){echo 'B1为分组';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '店铺'){echo 'C1为店铺';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '日期'){echo 'D1为日期';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '访客数'){echo 'H1为访客数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("S1")->getValue() == '支付金额'){echo 'S1为支付金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("V1")->getValue() == '支付买家数'){echo 'V1为支付买家数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("Y1")->getValue() == '客单价'){echo 'Y1为客单价';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AI1")->getValue() == '刷单数量'){echo 'AI1为刷单数量';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AJ1")->getValue() == '刷单金额'){echo 'AJ1为刷单金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AK1")->getValue() == '支付转化率'){echo 'AK1为支付转化率';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AL1")->getValue() == '退款金额'){echo 'AL1为退款金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AN1")->getValue() == '直通车费用'){echo 'AN1为直通车费用';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AQ1")->getValue() == '钻展费用'){echo 'AQ1为钻展费用';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AX1")->getValue() == '淘宝客费用'){echo 'AX1为淘宝客费用';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AU1")->getValue() == '品销宝费用'){echo 'AU1为品销宝费用';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("AY1")->getValue() == '聚划算'){echo 'AY1为聚划算';exit();}
    }
}