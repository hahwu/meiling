<?php
/**
 * 各店铺销售结构表(完成)
 */

class StructureModel extends Model
{
    private $DB_NAME = 'structure';

    function excelToMysql ($objPHPExcel)
    {
        $this->checkExcel($objPHPExcel);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        //M($this->DB_NAME)->where("date > '1970-01-01'")->delete();
        for($i=2;$i<=$highestRow;$i++)
        {
            $data['store'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            if(is_object($data['store']))  $data['store']= $data['store']->__toString();
            $data['date'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            if(is_object($data['date']))  $data['date']= $data['date']->__toString();
            $data['color'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
//            if(is_object($data['color']))  $data['color']= $data['color']->__toString();
//            $data['color'] = $data['color']==NULL?'-':$data['color'];
            $data['category'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            if($data['category'] == NULL){
                $data['category'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
                $data['color'] = $data['category'] == '总计'?'#0A95EC':'#82CED0';
            }else{
                $data['color'] = '#ffffff';
            }
            $data['category'] = $data['category']==NULL?'-':$data['category'];
            if(is_object($data['category']))  $data['category']= $data['category']->__toString();
            $data['order']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();
            $data['money']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();
            $data['rate']= $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();
            $data['price']= $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();
            $data['link']= $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();
            $data['stock']= $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();
            $data['value']= $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();
            echo mysql_error();
            if(M($this->DB_NAME)->where("store = '{$data['store']}' and date = '{$data['date']}' and category = '{$data['category']}'")->select()){
                M($this->DB_NAME)->where("store = '{$data['store']}' and date = '{$data['date']}' and category = '{$data['category']}'")->data($data)->save();
            }else{
                echo "add";
                M($this->DB_NAME)->data($data)->add();
            }
            echo ($i/$highestRow*100)."%<br>";
        }
    }
    function checkExcel($objPHPExcel)
    {
        if(!$objPHPExcel->getActiveSheet()->getCell("A1")->getValue() == '店铺'){echo 'A1为店铺';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("B1")->getValue() == '日期'){echo 'B1为日期';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("C1")->getValue() == '大类'){echo 'C1为大类';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("D1")->getValue() == '类目'){echo 'D1为类目';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("E1")->getValue() == '支付商品件数'){echo 'E1为支付商品件数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("F1")->getValue() == '支付金额'){echo 'F1为支付金额';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("G1")->getValue() == '支付金额占比%'){echo 'G1为支付金额占比%';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("H1")->getValue() == '销售平均物单价'){echo 'H1为销售平均物单价';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("I1")->getValue() == '在架链接数'){echo 'I1为在架链接数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("J1")->getValue() == '在架库存数'){echo 'J1为在架库存数';exit();}
        if(!$objPHPExcel->getActiveSheet()->getCell("K1")->getValue() == '在架销售货值'){echo 'K1为在架销售货值';exit();}
    }
}