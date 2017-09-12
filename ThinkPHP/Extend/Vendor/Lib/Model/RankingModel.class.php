<?php
/**
 * 各类目行业排行一览表(未完成)
 */

class RankingModel extends Model
{
    private $DB_NAME = 'ranking';

    function excelToMysql ($objPHPExcel)
    {
        $this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['date'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if(is_object($data['date']))  $data['date']= $data['date']->__toString();
            $data['category'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['category']))  $data['category']= $data['category']->__toString();
            $data['class'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            if(is_object($data['class']))  $data['class']= $data['class']->__toString();
            $data['brand'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if(is_object($data['brand']))  $data['brand']= $data['brand']->__toString();

            $data['rank']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['sales']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $data['buyers']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['price']= $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();
            $data['visitor']= $objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue();
            $data['rate']= $objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();
            $data['last']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            echo mysql_error();
            if(M($this->DB_NAME)->where("brand = '{$data['brand']}' and date = '{$data['date']}' and class = '{$data['class']}'")->select()){
                M($this->DB_NAME)->data($data)->save();
            }else{
                echo "add";
                M($this->DB_NAME)->data($data)->add();
            }
            echo ($i/$highestRow*100)."%<br>";
        }
    }
    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == "时间"){echo 'A1为时间';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '大类'){echo 'B1为大类';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '类目'){echo 'C1为类目';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '品牌'){echo 'D1为品牌';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("E1")->getValue() == '热销排名'){echo 'E1热销排名';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("G1")->getValue() == '旗舰店销售额'){echo 'G1为旗舰店销售额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '支付买家数'){echo 'H1为支付买家数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("L1")->getValue() == '客单价'){echo 'L1为客单价';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("M1")->getValue() == '访客数'){echo 'M1为访客数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("N1")->getValue() == '支付转化率'){echo 'N1支付转化率';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("J1")->getValue() == '上周全网销售'){echo 'J1上周全网销售';exit();}
    }
}