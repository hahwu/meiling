<?php
// +----------------------------------------------------------------------
// | Description: Be yourself
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.bbw712.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: simon wsyone@foxmail.com
// +----------------------------------------------------------------------
// | Date:2014-5-17
class IndexAction extends Action {
	/**
	 *
	 * Enter 导出excel共同方法 ...
	 * @param unknown_type $expTitle
	 * @param unknown_type $expCellName
	 * @param unknown_type $expTableData
	 */
	function  index(){
		$this->display();
	}
	public function exportExcel($expTitle,$expCellName,$expTableData){
		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$fileName = $_SESSION['account'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);

		vendor("PHPExcel.PHPExcel");
			
		$objPHPExcel = new PHPExcel();
		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

		$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
		// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
		for($i=0;$i<$cellNum;$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
		}
		// Miscellaneous glyphs, UTF-8
		for($i=0;$i<$dataNum;$i++){
			for($j=0;$j<$cellNum;$j++){
				$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
			}
		}

		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}

	/**
	 *
	 * 导出Excel
	 */

	/**
	 *
	 * 显示导入页面 ...
	 */

	/**实现导入excel
	 **/
	public function impUser(){
        header("Content-type: text/html; charset=utf-8");
        $name = '';
        switch ($_FILES['import']['name'])
        {
            case '生意参谋.xlsx':$Model = new SycmModel();$name = '生意参谋.xlsx';break;
            case '各店铺月度销售达成明细.xlsx':$Model = new CompletionModel();$name = '各店铺月度销售达成明细.xlsx';break;
            case '店铺销售结构表.xlsx':$Model = new StructureModel();$name = '店铺销售结构表.xlsx';break;
            case '竞品各店铺销售一览表.xlsx':$Model = new CompetitionModel();$name = '竞品各店铺销售一览表.xlsx';break;
            case '直通车.xlsx':$Model = new ZtcModel();$name = '直通车.xlsx';break;
            case '钻展.xlsx':$Model = new ZzModel();$name = '钻展.xlsx';break;
            case '品销宝.xlsx':$Model = new PxbModel();$name = '品销宝.xlsx';break;
            case '子行业排行表.xlsx':$Model = new RankingModel();$name = '子行业排行表.xlsx';break;
            case '商品存销数据.xlsx':$Model = new StockModel();$name = '商品库存数据.xlsx';break;
            case '唯品会.xlsx':$Model = new WphModel();$name = '维品会.xlsx';break;
            case '淘系.xlsx':$Model = new TxModel();$name = '淘系.xlsx';break;
        }
        if($name == '')
        {
            echo '请选择上传的文件或请使用正确的文件名！';
        }
		if (!empty($_FILES)) {
			import("@.ORG.UploadFile");
			$config=array(
                'allowExts'=>array('xlsx','xls'),
                'savePath'=>'./Public/upload/',
                'saveRule'=>'time',
			);
			$upload = new UploadFile($config);
			if (!$upload->upload()) {
				$this->error($upload->getErrorMsg());
			} else {
				$info = $upload->getUploadFileInfo();

			}
			vendor("PHPExcel.PHPExcel");
			$file_name=$info[0]['savepath'].$info[0]['savename'];
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($file_name,$encode='utf-8');
			//$Model = new RankingModel();
			echo $Model->excelToMysql($objPHPExcel);
			unset($data);
		}
		else
		{
			$this->error("请选择上传的文件");
		}
        unlink($file_name);
		unset($data);
	}

}