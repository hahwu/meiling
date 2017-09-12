<?php
/**
 * 微信接口文件
 */

class WxAction extends Action
{
    /*直通车*/
    public function ztc($date='')
    {
        $ZTC = M('ztc');
        if($date == '')
        {
            $date = $ZTC->max('date');
        }
        //echo $date;
        /*三天数据*/
        $time = strtotime($date);
        $date2 = date("Y-m-d",$time-86400*2);

        $three_data = $ZTC->field('store,sum(cost) as cost,sum(click) as click,sum(amount) as amount,sum(day_money) as day_money')->where("date <= '{$date}' and date >= '{$date2}'")->group('store')->order(array('department','team','cost'=>'desc'))->select();
        $three_order = $ZTC->where("date = '{$date2}'")->order(array('department','team','cost'=>'desc'))->select();
        $three_total =array();
        for($i=0;$i<count($three_data);$i++)
        {
            $three_result[$i]['three_order'] = $three_order[$i]['three_order'] == 0?'-':$three_order[$i]['three_order'];
            $three_result[$i]['store'] = $three_data[$i]['store'];
            $three_result[$i]['cost'] = $three_order[$i]['cost'] == 0?'-':round($three_order[$i]['cost']);
            if($three_data[$i]['click'] == 0) {
                $three_result[$i]['click_price'] = '-';
                $three_result[$i]['click_rate'] = '-';
            }else{
                $three_result[$i]['click_price'] = round($three_order[$i]['cost']/$three_order[$i]['click'],2);
                $three_result[$i]['click_rate'] = round($three_order[$i]['click']/$three_order[$i]['amount']*100,2);
            }
            $three_result[$i]['day_money'] = $three_order[$i]['three_money'] == 0?'-':round($three_order[$i]['three_money']);
            if($three_data[$i]['cost'] == 0){
                $three_result[$i]['roi'] = '-';
            }else{
                $three_result[$i]['roi'] = round($three_result[$i]['day_money']/$three_result[$i]['cost'],2);
            }
            $three_result[$i]['roi'] = $three_result[$i]['roi'] == 0?'-':$three_result[$i]['roi'];
            $three_total['cost'] += $three_order[$i]['cost'];
            $three_total['click'] += $three_order[$i]['click'];
            $three_total['amount'] += $three_order[$i]['amount'];
            $three_total['day_money'] += $three_order[$i]['three_money'];
            $three_total['three_order'] += $three_result[$i]['three_order'];
        }
        $three_total['cost'] = round($three_total['cost']);
        $three_total['day_money'] = round( $three_total['day_money']);
        $three_total['click_price'] = round($three_total['cost']/$three_total['click'],2);
        $three_total['click_rate'] = round($three_total['click']/$three_total['amount']*100,2);
        $three_total['roi'] = round($three_total['day_money']/$three_total['cost'],2);

        /*一天数据*/
        $data = $ZTC->where("date = '{$date}'")->order(array('department','team','cost'=>'desc'))->select();
        $total = array();
        for($i=0;$i<count($data);$i++)
        {
            $result[$i]['store'] = $data[$i]['store'];
            $result[$i]['cost'] = (int)$data[$i]['cost'] == 0?'-':round($data[$i]['cost']);
            if($data[$i]['click'] == 0) {
                $result[$i]['click_price'] = '-';
                $result[$i]['click_rate'] = '-';
            }else{
                $result[$i]['click_price'] = substr($data[$i]['cost']/$data[$i]['click'],0,4);
                $result[$i]['click_rate'] = substr($data[$i]['click']/$data[$i]['amount']*100,0,4)."%";
            }
            $result[$i]['day_money'] = $data[$i]['day_money'] == 0?'-':$data[$i]['day_money'];
            if($data[$i]['cost'] == 0){
                $result[$i]['roi'] = '-';
            }else{
                $result[$i]['roi'] = substr($data[$i]['day_money']/$data[$i]['cost'],0,4);
            }
            $result[$i]['roi'] = $result[$i]['roi'] == 0?'-':$result[$i]['roi'];
            $total['cost'] += (int)$data[$i]['cost'];
            $total['click'] += $data[$i]['click'];
            $total['amount'] += $data[$i]['amount'];
            $total['day_money'] += $data[$i]['day_money'];
        }
        $total['click_price'] = round($total['cost']/$total['click'],2);
        $total['click_rate'] = round($total['click']/$total['amount']*100,2);
        $total['roi'] = round($total['day_money']/$total['cost'],2);
        $data2['result'] = $result;
        $data2['date'] = $date;
        $data2['date2'] = $date2."-".$date;
        $data2['total'] = $total;
        $data2['three_result'] = $three_result;
        $data2['three_total'] = $three_total;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
        unset($data);
        unset($data2);
    }

    /*钻展*/
    public function zz($date='')
    {
        $total = array();
        $ZTC = M('zz');
        if($date == '') $date = $ZTC->max('date');
        /*三天数据*/
        $time = strtotime($date);
        $date2 = date("Y-m-d",$time-86400*2);
        $three_data = $ZTC->field('store,sum(consume) as consumes,sum(click) as clicks,sum(reveal) as shows,sum(order_money) as order_money')->where("date <= '{$date}' and date >= '{$date2}'")->group('store')->order(array('department','team','consumes'=>'desc'))->select();
        echo mysql_error();
        $three_order = $ZTC->field('sum(consume) as consume,sum(click) as clicks,sum(reveal) as shows,sum(order_money) as order_money')->where("date = '{$date2}'")->group('store')->order(array('department','team','consume'=>'desc'))->select();
        echo mysql_error();
        $three_total =array();
        for($i=0;$i<count($three_data);$i++)
        {
            $three_result[$i]['store'] = $three_data[$i]['store'];
            $three_result[$i]['consume'] = $three_order[$i]['consume'] == 0?'-':round($three_order[$i]['consume']);
            $three_result[$i]['click_price'] = $three_order[$i]['consume']/$three_order[$i]['clicks'] == 0?'-':round($three_order[$i]['consume']/$three_order[$i]['clicks'],2);
            $three_result[$i]['click_rate'] = $three_order[$i]['clicks']/$three_order[$i]['shows'] == 0?'-':round($three_order[$i]['clicks']/$three_order[$i]['shows']*100,2)."%";
            $three_result[$i]['order_money'] = $three_order[$i]['order_money'] == 0?'-':$three_order[$i]['order_money'];
            $three_result[$i]['roi'] = $three_order[$i]['order_money']/$three_order[$i]['consume'] == 0?'-':round($three_order[$i]['order_money']/$three_order[$i]['consume'],2);
            $three_total['consume'] += $three_order[$i]['consume'];

            $three_total['shows'] += $three_order[$i]['shows'];
            $three_total['clicks'] += $three_order[$i]['clicks'];
            $three_total['order_money'] += $three_order[$i]['order_money'];
            $three_total['three_order_money'] += $three_data['order_money'];
        }
//        echo "<pre>";
//        var_dump($three_data);
//        var_dump($three_result);
        $three_total['order_money'] = round($three_total['order_money']);
        $three_total['consume'] = round($three_total['consume']);
        $three_total['click_price'] = round($three_total['consume']/$three_total['clicks'],2);
        $three_total['click_rate'] = round($three_total['clicks']/$three_total['shows']*100,2)."%";
        $three_total['roi'] = round($three_total['order_money']/$three_total['consume'],2);
        /*一天数据*/
        $data = $ZTC->field('store,sum(consume) as consumes,sum(click) as clicks,sum(reveal) as shows, sum(order_money) as order_moneys')->where("date = '{$date}'")->group('store')->order(array('department','team','consumes'=>'desc'))->select();
        for($i=0;$i<count($data);$i++)
        {
            $result[$i]['store'] = $data[$i]['store'];
            $result[$i]['consume'] = $data[$i]['consumes'] == 0?'-':round($data[$i]['consumes']);
            $result[$i]['click_price'] = $result[$i]['consume']/$data[$i]['clicks'] == 0?'-':round($result[$i]['consume']/$data[$i]['clicks'],2);
            $result[$i]['click_rate'] = $data[$i]['clicks']/$data[$i]['shows'] == 0?'-':round($data[$i]['clicks']/$data[$i]['shows']*100,2)."%";
            $result[$i]['order_money'] = $data[$i]['order_moneys'] == 0?'-':$data[$i]['order_moneys'];
            $result[$i]['roi'] = $data[$i]['order_moneys']/$data[$i]['consumes'] == 0?'-':round($data[$i]['order_moneys']/$data[$i]['consumes'],2);
            $total['consume'] += $data[$i]['consumes'];
            $total['shows'] += (int)$data[$i]['shows'];
            $total['clicks'] += (int)$data[$i]['clicks'];
            $total['order_moneys'] += $data[$i]['order_moneys'];
        }
        $total['order_moneys'] = round($total['order_moneys']);
        $total['consume'] = round($total['consume']);
        $total['click_price'] = round($total['consume']/$total['clicks'],2);
        $total['click_rate'] = round($total['clicks']/$total['shows']*100,2)."%";
        $total['roi'] = round($total['order_moneys']/$total['consume'],2);
        $data2['result'] = $result;
        $data2['date'] = $date;
        $data2['total'] = $total;
        $data2['three_result'] = $three_result;
        $data2['three_total'] = $three_total;
        $data2['date2'] = $date2."-".$date;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
        unset($data);
        unset($data2);
    }

    /*品销宝*/
    public function pxb($date='')
    {
        header("Content-type: text/html; charset=utf-8");
        $ZTC = M('pxb');
        if($date == '') $date = $ZTC->max('date');
        /*三天数据*/
        $time = strtotime($date);
        $date2 = date("Y-m-d",$time-86400*2);
        $three_data = $ZTC->field('store,project,sum(consume) as consume,sum(click) as click,sum(reveal) as reveal,sum(storecollect) as storecollect')->where("date <= '{$date}' and date >= '{$date2}'")->group('store,project')->order(array('team','store'=>'desc','project'=>'desc'))->select();
        $three_order = $ZTC->field('store,project,sum(consume) as consume,sum(click) as click,sum(reveal) as reveal,sum(storecollect) as storecollect')->where("date = '{$date2}'")->group('store,project')->order(array('team','store'=>'desc','project'=>'desc'))->select();
        $three_total = array();
        for($i=0;$i<count($three_order);$i++)
        {
            $three_result[$i]['store'] = $three_order[$i]['store'] == $three_order[$i-1]['store']?"":$three_order[$i]['store'];
            $three_result[$i]['project'] = $three_order[$i]['project'];
            $three_result[$i]['consume'] = $three_order[$i]['consume'] == 0?'-':round($three_order[$i]['consume']);
            $three_result[$i]['click_price'] = $three_order[$i]['consume']/$three_order[$i]['click'] == 0?'-':round($three_order[$i]['consume']/$three_order[$i]['click'],2);
            $three_result[$i]['click_rate'] = $three_order[$i]['reveal']/$three_order[$i]['click'] == 0?'-':round($three_order[$i]['reveal']/$three_order[$i]['click'],2)."%";
            $three_result[$i]['store_collect'] = $three_order[$i]['storecollect'] == 0?'-':$three_order[$i]['storecollect'];
            $three_total['consume'] += $three_order[$i]['consume'];
            $three_total['click'] += $three_order[$i]['click'];
            $three_total['reveal'] += $three_order[$i]['reveal'];
            $three_total['store_collect'] += $three_order[$i]['storecollect'];
        }
        $three_total['consume'] = round($three_total['consume']);
        $three_total['click_price'] = round($three_total['consume']/$three_total['click'],2);
        $three_total['click_rate'] = round($three_total['reveal']/$three_total['click'],2);
        /*一天数据*/
        $data = $ZTC->field('store,project,sum(consume) as consume,sum(click) as click,sum(reveal) as reveal,sum(storecollect) as storecollect')->where("date = '{$date}'")->group('store,project')->order(array('team','store'=>'desc','project'=>'desc'))->select();
        $total = array();
        for($i=0;$i<count($data);$i++)
        {
            $result[$i]['store'] = $data[$i]['store'] == $data[$i-1]['store']?"":$data[$i]['store'];
            $result[$i]['project'] = $data[$i]['project'];
            $result[$i]['consume'] = $data[$i]['consume'] == 0?'-':round($data[$i]['consume']);
            $result[$i]['click_price'] = $result[$i]['consume']/$data[$i]['click'] == 0?'-':round($result[$i]['consume']/$data[$i]['click'],2);
            $result[$i]['click_rate'] = $data[$i]['click']/$data[$i]['reveal'] == 0?'-':round($data[$i]['click']/$data[$i]['reveal']*100,2)."%";
            $result[$i]['store_collect'] = $data[$i]['storecollect'] == 0?'-':$data[$i]['storecollect'];
            $total['consume'] += $data[$i]['consume'];
            $total['click'] += $data[$i]['click'];
            $total['reveal'] += $data[$i]['reveal'];
            $total['store_collect'] += $data[$i]['storecollect'];
        }
        $total['click_price'] = round($total['consume']/$total['click'],2);
        $total['click_rate'] = round($total['reveal']/$total['click'],2);
        $data2['result'] = $result;
        $data2['date'] = $date;
        $data2['total'] = $total;
        $data2['three_result'] = $three_result;
        $data2['three_total'] = $three_total;
        $data2['date2'] = $date2."-".$date;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
        unset($data);
        unset($data2);
    }

    /*月推广表*/
    public function expand($date='')
    {
        if($date == '') {
            $time = time();
            $date = date('Y-m', $time);
        }
        $dates = split('-',$date);
        $year = ($dates[1] == '12'?(int)$dates[0]+1:$dates[0]);
        $month = ($dates[1] == '12'?1:(int)$dates[1]+1);
        $newdate = $month < 10?$year.'-0'.$month:$year.'-'.$month;
        $total = array();
        $ztcstore = M('ztc')->field('store')->group('store')->order(array('department','team','cost'=>'desc'))->where("date >= '{$date}' and date <= '{$newdate}'")->select();
        for($i=0;$i<count($ztcstore);$i++)
        {
            $result[$i]['store'] =  $store = $ztcstore[$i]['store'];
            $ztccost = M('ztc')->field('sum(cost) as cost,sum(sale) as sale')->group('store')->where("date >= '{$date}' and date < '{$newdate}' and store = '{$store}'")->select();
            $zzcost = M('zz')->field('sum(consume) as cost')->group('store')->where("date >= '{$date}' and date < '{$newdate}' and store = '{$store}'")->select();
            $pxbcost = M('pxb')->field('sum(consume) as cost')->group('store')->where("date >= '{$date}' and date < '{$newdate}' and store = '{$store}'")->select();
            $tbkcost = M('sycm')->field('sum(tbkcost) as cost')->group('store')->where("date >= '{$date}' and date < '{$newdate}' and store = '{$store}'")->select();
            echo mysql_error();
            $result[$i]['money'] = ($ztccost[0]['sale'] == 'NULL'?'-':$ztccost[0]['sale']) == 0?'-':round($ztccost[0]['sale']);
            $result[$i]['ztccost'] = ($ztccost[0]['cost'] == 'NULL'?'-':$ztccost[0]['cost']) == 0?'-':round($ztccost[0]['cost']);
            $result[$i]['zzcost'] = ($zzcost[0]['cost'] == 'NULL'?'-':$zzcost[0]['cost']) == 0?'-':round($zzcost[0]['cost']);
            $result[$i]['pxbcost'] = ($pxbcost[0]['cost'] == 'NULL'?'-':$pxbcost[0]['cost']) == 0?'-':round($pxbcost[0]['cost']);
            $result[$i]['tbkcost'] = ($tbkcost[0]['cost'] == 'NULL'?'-':$tbkcost[0]['cost']) == 0?'-':round($tbkcost[0]['cost']);
            $result[$i]['total'] = round($result[$i]['ztccost'] + $result[$i]['zzcost'] + $result[$i]['pxbcost']);
            $result[$i]['rate'] = round($result[$i]['total']/$result[$i]['money']*100,2).'%';
            $total['money'] += ($ztccost[0]['sale'] == 'NULL'?0:$ztccost[0]['sale']);
            $total['ztccost'] += ($ztccost[0]['cost'] == 'NULL'?0:$ztccost[0]['cost']);
            $total['zzcost'] += ($zzcost[0]['cost'] == 'NULL'?0:$zzcost[0]['cost']);
            $total['tbkcost'] += ($tbkcost[0]['cost'] == 'NULL'?0:$tbkcost[0]['cost']);
            $total['pxbcost'] += ($pxbcost[0]['cost'] == 'NULL'?'-':$pxbcost[0]['cost']);
            $total['total'] += (($ztccost[0]['cost'] == 'NULL'?0:$ztccost[0]['cost'])+($zzcost[0]['cost'] == 'NULL'?0:$zzcost[0]['cost'])+($pxbcost[0]['cost'] == 'NULL'?'-':$pxbcost[0]['cost']));
        }
        $total['store'] = '总计';
        $total['color'] = '#0A95EC';
        $total['money'] = round($total['money']);
        $total['ztccost'] = round($total['ztccost']);
        $total['zzcost'] = round($total['zzcost']);
        $total['pxbcost'] = round($total['pxbcost']);
        $total['tbkcost'] = round($total['tbkcost']);
        $total['total'] = round($total['total']);
        $total['rate'] = round($total['total']/$total['money']*100,2).'%';
        array_push($result,$total);
        $data['result'] = $result;
        $data['month'] = $date;
        $data['total'] = $total;
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);

        unset($data);
    }

    /*各店铺销售情况一览表-日*/
    public function sycm_day($date='',$sycm='')
    {
        $sycm = trim($sycm);
        $sycm_array = explode(',',$sycm);
        $sycm_s = '';
        if(count($sycm_array) > 0){
            for($i = 0;$i < count($sycm_array);$i++){
                $sycm_s = $sycm_s." and "."store != '".trim($sycm_array[$i])."'";
            }
        }
        $ZTC = M('sycm');
        if($date == '') $date = $ZTC->max('date');
        $data = $ZTC->field('money_order,scalp_order,scalp,refund,department,store,visitor,rate,price,money,ztccost,zzcost,pxbcost,jhscost')->group('store')->order(array('department','team','store'=>'desc'))->where("date = '{$date}'" .$sycm_s)->select();
//        $department = $ZTC->field('sum(scalp_order) as scalp_order,sum(money_order) as money_order,department,sum(refund) as refund,sum(visitor) as visitor,sum(rate) as rate,sum(price) as price,sum(money) as money,sum(scalp) as scalp,sum(ztccost) as ztccost,sum(zzcost) as zzcost,sum(pxbcost) as pxbcost,sum(jhscost) as jhscost')->group('department')->order(array('department','team','store'=>'desc'))->where("date = '{$date}'")->select();
//        $index = array();
//        $v = 0;
//        $total = array();
//        for($i=0;$i<count($data);$i++)
//        {
//            if($data[$i]['department'] != $department[$v]['department']) {
//                $index[$v] = $i;
//                $v++;
//            }
//        }
        for($i=0;$i<count($data);$i++)
        {
            $data[$i]['price'] = ($data[$i]['money']-$data[$i]['scalp'])/($data[$i]['money_order']-$data[$i]['scalp_order']) == 0?'-':round(($data[$i]['money']-$data[$i]['scalp'])/($data[$i]['money_order']-$data[$i]['scalp_order']));
            $data[$i]['refund'] = ($data[$i]['refund'] == 0?'-':round($data[$i]['refund']));
            $data[$i]['money'] = (($data[$i]['money'] - $data[$i]['scalp']) == 0?'-':round(($data[$i]['money'] - $data[$i]['scalp'])));
            $data[$i]['rate'] = ($data[$i]['rate'] == 0?'-':round($data[$i]['rate']*100,2).'%');
            $data[$i]['total'] = $data[$i]['ztccost'] + $data[$i]['zzcost'] + $data[$i]['pxbcost'];
            $data[$i]['total'] = ($data[$i]['total'] == 0?'-':round($data[$i]['total']));
            $data[$i]['ztccost'] = $data[$i]['ztccost'] == 0?'-':round($data[$i]['ztccost']);
            $data[$i]['zzcost'] = $data[$i]['zzcost'] == 0?'-':round($data[$i]['zzcost']);
            $data[$i]['pxbcost'] = $data[$i]['pxbcost'] == 0?'-':round($data[$i]['pxbcost']);
        }
//        for($i=0;$i<count($department);$i++)
//        {
//            $store = $ZTC->where("date = '{$date}' and department = '{$department[$i]['department']}' and rate != 0")->count();
//            $department[$i]['store'] = $department[$i]['department'].'汇总';
//            $department[$i]['money'] = round($department[$i]['money'] - $department[$i]['scalp']);
//
//            $department[$i]['rate'] = round($department[$i]['rate']/$store*100,2).'%';
//            $department[$i]['price'] = ($department[$i]['money']-$department[$i]['scalp'])/($department[$i]['money_order']-$department[$i]['scalp_order']) == 0?'-':round(($department[$i]['money']-$department[$i]['scalp'])/($department[$i]['money_order']-$department[$i]['scalp_order']));
////            $department[$i]['total'] = $department[$i]['ztccost'] + $department[$i]['zzcost'] + $department[$i]['pxbcost']+ $department[$i]['jhscost'];
////            $department[$i]['total'] = ($department[$i]['total'] == 0?'-':round($department[$i]['total']));
//
//            $department[$i]['color'] = '#A5CCF7';
//            $department[$i]['refund'] = round($department[$i]['refund']);
//            $total['visitor'] += $department[$i]['visitor'];
//            $total['rate'] += $department[$i]['rate'];
//            $total['price'] += $department[$i]['price'];
//            $total['zzcost'] += $department[$i]['zzcost'];
//            $total['ztccost'] += $department[$i]['ztccost'];
//            $total['pxbcost'] += $department[$i]['pxbcost'];
//            $total['money'] += $department[$i]['money'];
//            $total['refund'] += $department[$i]['refund'];
//            $department[$i]['zzcost'] = $department[$i]['zzcost'] == 0?'-':round($department[$i]['zzcost']);
//            $department[$i]['ztccost'] = $department[$i]['ztccost'] == 0?'-':round($department[$i]['ztccost']);
//            $department[$i]['pxbcost'] = $department[$i]['pxbcost'] == 0?'-':round($department[$i]['pxbcost']);
//        }
//        $total['rate'] = round($total['rate']/count($department),2)."%";
//        $total['price'] = round($total['price']/count($department));
//        $total['ztccost'] = $total['ztccost'] == 0?'-':round($total['ztccost']);
//        $total['zzcost'] = $total['zzcost'] == 0?'-':round($total['zzcost']);
//        $total['pxbcost'] = $total['pxbcost'] == 0?'-':round($total['pxbcost']);
//        $total['color'] = '#0A95EC';
//        $total['store'] = '总计';
//        $d = 0;
//        for($i=0;$i<count($index);$i++)
//        {
//            array_splice($data,$index[$i] + $d,0,array($department[$i]));
//            $d++;
//        }
//        array_push($data,$department[count($department)-1]);
//        array_push($data, $total);
        $data2['date'] = $date;
        $data2['result'] = $data;
        $data2['sycm'] = $sycm_s;
        //$data2['total'] = $total;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*各店铺销售情况一览表-月*/
    public function sycm_month($date='',$sycm='')
    {
        header("Content-type: text/html; charset=utf-8");
        $sycm_array = explode(',',$sycm);
        $sycm_s = '';
        if(count($sycm_array) > 0){
            for($i = 0;$i < count($sycm_array);$i++){
                $sycm_s = $sycm_s." and "."sycm.store != '".trim($sycm_array[$i])."'";
            }
        }
        $ZTC = M('sycm');
        if($date == '') $date = $ZTC->max('date');
        $date = substr($date,0,7);
        $comdate = $date."-01";
        $dates = split('-',$date);
        $year = ($dates[1] == '12'?(int)$dates[0]+1:$dates[0]);
        $month = ($dates[1] == '12'?1:(int)$dates[1]+1);
        $newdate = $month < 10?$year.'-0'.$month:$year.'-'.$month;
        $data = $ZTC->join('completion ON  completion.store = sycm.store')->field('sum(sycm.scalp) as scalp,sum(scalp_order) as scalp_order,sum(money_order) as money_order,starget,target,sycm.department,sum(refund) as refund,completion.store,sum(visitor) as visitor,sum(sycm.rate) as rate,sum(price) as price,sum(sycm.money) as money,sum(ztccost) as ztccost,sum(zzcost) as zzcost,sum(pxbcost) as pxbcost,sum(jhscost) as jhscost,sum(tbkcost) as tbkcost')->group('sycm.store')->order(array('sycm.department','sycm.team','sycm.money'=>'desc'))->where("sycm.date >= '{$date}' and sycm.date < '{$newdate}' and completion.date = '{$comdate}'".$sycm_s)->select();
        echo mysql_error();
        $department = $ZTC->field('sum(scalp_order) as scalp_order,sum(money_order) as money_order,department,sum(refund) as refund,sum(visitor) as visitor,sum(rate) as rate,sum(price) as price,sum(money) as money,sum(scalp) as scalp,sum(ztccost) as ztccost,sum(zzcost) as zzcost,sum(pxbcost) as pxbcost,sum(jhscost) as jhscost,sum(tbkcost) as tbkcost')->group('department')->order(array('department','team','money'=>'desc'))->where("date >= '{$date}' and date <= '{$newdate}'")->select();
        $index = array();
        $v = 0;
        $total = array();
        for($i=0;$i<count($data);$i++)
        {
            if($data[$i]['department'] != $department[$v]['department']) {
                $index[$v] = $i;
                $v++;
            }
        }
        for($i=0;$i<count($data);$i++)
        {
            $count = $ZTC->where("date >= '{$date}' and date <= '{$newdate}' and store = '{$data[$i]['store']}'")->count();
            $data[$i]['price'] = ($data[$i]['money']-$data[$i]['scalp'])/($data[$i]['money_order']-$data[$i]['scalp_order']) == 0?'-':round(($data[$i]['money']-$data[$i]['scalp'])/($data[$i]['money_order']-$data[$i]['scalp_order']));
            $data[$i]['refund'] = ($data[$i]['refund'] == 0?'-':round($data[$i]['refund']));
            $data[$i]['money'] = (($data[$i]['money'] - $data[$i]['scalp']) == 0?'-':round(($data[$i]['money'] - $data[$i]['scalp'])));
            $data[$i]['rate'] = ($data[$i]['rate'] == 0?'-':round($data[$i]['rate']*100/$count,2).'%');
            $data[$i]['total'] = $data[$i]['ztccost'] + $data[$i]['zzcost'] + $data[$i]['pxbcost']+ $data[$i]['jhscost'] + $data[$i]['tbkcost'];
            $data[$i]['total'] = ($data[$i]['total'] == 0?'-':round($data[$i]['total']));
        }
        for($i=0;$i<count($department);$i++)
        {
            for($j=0;$j<count($data);$j++)
            {
                if($department[$i]['department'] == $data[$j]['department']){
                    $department[$i]['target'] += $data[$j]['target'];
                    $department[$i]['starget'] += $data[$j]['starget'];
                }
            }
            $store = $ZTC->where("date >= '{$date}' and date <= '{$newdate}' and department = '{$department[$i]['department']}' and rate != 0")->count();
            $department[$i]['store'] = $department[$i]['department'].'汇总';
            $department[$i]['money'] = round($department[$i]['money'] - $department[$i]['scalp']);
            $department[$i]['rate'] = round($department[$i]['rate']/$store*100,2).'%';
            $department[$i]['price'] = ($department[$i]['money']-$department[$i]['scalp'])/($department[$i]['money_order']-$department[$i]['scalp_order']) == 0?'-':round(($department[$i]['money']-$department[$i]['scalp'])/($department[$i]['money_order']-$department[$i]['scalp_order']));
            $department[$i]['total'] = $department[$i]['ztccost'] + $department[$i]['zzcost'] + $department[$i]['pxbcost']+ $department[$i]['jhscost'] + $data[$i]['tbkcost'];
            $department[$i]['total'] = ($department[$i]['total'] == 0?'-':round($department[$i]['total']));
            $department[$i]['color'] = '#A5CCF7';
            $department[$i]['refund'] = round($department[$i]['refund']);
            $total['visitor'] += $department[$i]['visitor'];
            $total['rate'] += $department[$i]['rate'];
            $total['price'] += $department[$i]['price'];
            $total['total'] += $department[$i]['total'];
            $total['money'] += $department[$i]['money'];
            $total['refund'] += $department[$i]['refund'];
            $total['target'] += $department[$i]['target'];
            $total['starget'] += $department[$i]['starget'];
        }
        $total['rate'] = round($total['rate']/count($department),2)."%";
        $total['price'] = round($total['price']/count($department));
        $total['color'] = '#0A95EC';
        $total['store'] = '总计';
        $d = 0;
//        for($i=0;$i<count($index);$i++)
//        {
//            array_splice($data,$index[$i] + $d,0,array($department[$i]));
//            $d++;
//        }
//        array_push($data,$department[count($department)-1]);
//        array_push($data, $total);
        for($j=0;$j<count($data);$j++)
        {
            $data[$j]['target'] = $data[$j]['target'] == 0?'-':$data[$j]['target'];
            $data[$j]['starget'] = $data[$j]['starget'] == 0?'-':$data[$j]['starget'];
            $data[$j]['sale_rate'] = $data[$j]['money']/$data[$j]['target'] == 0?'-':round($data[$j]['money']/$data[$j]['target']*100,2).'%';
            $data[$j]['total_rate'] = $data[$j]['total']/$data[$j]['money'] == 0?'-':round($data[$j]['total']/$data[$j]['money']*100,2).'%';
        }
        $data2['date'] = $date;
        $data2['result'] = $data;
        $data2['month_sycm'] = $sycm_s;
        //$data2['total'] = $total;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*各店铺销售情况一览表-年*/
    public function sycm_year($sycm='')
    {
        $sycm_array = explode(',',$sycm);
        $sycm_s = '';
        if(count($sycm_array) > 0){
            for($i = 0;$i < count($sycm_array);$i++){
                $sycm_s = $sycm_s." and "."store != '".trim($sycm_array[$i])."'";
            }
        }

        header("Content-type: text/html; charset=utf-8");
        $ZTC = M('completion');
        $date = $ZTC->max('date');

        $date2 = substr(M('sycm')->max('date'),0,7);
        $date = substr($date,0,4);
        $data = $ZTC->field('department,store,sum(actsale) as actsale,sum(target) as target,sum(starget) as starget,sum(actssale) as actssale')->group('store')->order(array('department','team','store'=>'desc'))->where("date >= '{$date}' and actsale != 0".$sycm_s)->select();
        $department = $ZTC->field('department,sum(actsale) as actsale,sum(target) as target,sum(starget) as starget,sum(actssale) as actssale')->group('department')->order(array('department','team','store'=>'desc'))->where("date >= '{$date}' and actsale != 0")->select();
        echo mysql_error();
        $year_data = $ZTC->field('store,sum(target) as target,sum(starget) as starget')->group('store')->order(array('department','team','store'=>'desc'))->where("date >= '{$date}'")->select();
        $year_department = $ZTC->field('department,sum(target) as target,sum(starget) as starget')->group('department')->order(array('department','team','store'=>'desc'))->where("date >= '{$date}'")->select();
        echo mysql_error();

        $index = array();
        $v = 0;
        $total = array();
        for($i=0;$i<count($data);$i++)
        {
            if($data[$i]['department'] != $department[$v]['department']) {
                $index[$v] = $i;
                $v++;
            }
        }
        for($i=0;$i<count($data);$i++){
            for($j=0;$j<count($year_data);$j++){
                if($year_data[$j]['store'] == $data[$i]['store']){
                    $data[$i]['year_target'] = $year_data[$j]['target'] == 0?'-':$year_data[$j]['target'];
                    $data[$i]['year_starget'] = $year_data[$j]['starget'] == 0?'-':$year_data[$j]['starget'];
                }
            }
            $data[$i]['gap'] = round($data[$i]['actssale'] - $data[$i]['starget']);
            $data[$i]['actsale'] = round($data[$i]['actsale']);
            $data[$i]['actssale'] = $data[$i]['actssale']==0?'-':round($data[$i]['actssale']);
            $data[$i]['target_rate'] = $data[$i]['target'] == 0?'-':round($data[$i]['actsale']/$data[$i]['target']*100,2).'%';
            $data[$i]['target'] = $data[$i]['target'] == 0?'-':round($data[$i]['target']);
            $data[$i]['starget'] = $data[$i]['starget'] == 0?'-':round($data[$i]['starget']);
            $data[$i]['starget_rate'] =  $data[$i]['starget'] == 0?'-':round($data[$i]['actssale']/$data[$i]['starget']*100,2).'%';
            $data[$i]['year_rate'] = $data[$i]['year_target'] == '-'?'-':round($data[$i]['actsale']/$data[$i]['year_target']*100,2);
            $data[$i]['year_rate'] = $data[$i]['year_rate'] == 0?'-':$data[$i]['year_rate'].'%';
            $data[$i]['year_rate2'] = $data[$i]['year_starget'] == '-'?'-':round($data[$i]['actssale']/$data[$i]['year_starget']*100,2);
            $data[$i]['year_rate2'] = $data[$i]['year_rate2'] == 0?'-':$data[$i]['year_rate2'].'%';
            $data[$i]['year_starget'] = $data[$i]['year_starget'] == 0?'-':round($data[$i]['year_starget']);
            $data[$i]['year_target'] = $data[$i]['year_target'] == 0?'-':round($data[$i]['year_target']);
        }
//        for($i=0;$i<count($department);$i++)
//        {
//            for($j=0;$j<count($year_department);$j++){
//                if($year_department[$j]['department'] == $department[$i]['department']){
//                    $department[$i]['year_target'] = $year_department[$j]['target'] == 0?'-':$year_department[$j]['target'];
//                    $department[$i]['year_starget'] = $year_department[$j]['starget'] == 0?'-':$year_department[$j]['starget'];
//                }
//            }
//            $department[$i]['gap'] = round($department[$i]['actsale'] - $department[$i]['starget']);
//            $department[$i]['starget_rate'] = $department[$i]['starget'] == 0?'-':round($department[$i]['actssale']/$department[$i]['starget']*100,2).'%';
//            $department[$i]['store'] = $department[$i]['department'].'汇总';
//            $department[$i]['color'] = '#A5CCF7';
//            $total['actssale'] += $department[$i]['actssale'];
//            $total['starget'] += $department[$i]['starget'];
//            $department[$i]['actssale'] = round($department[$i]['actssale']);
//            $department[$i]['starget'] = round($department[$i]['starget']);
//            $department[$i]['target_rate'] = $department[$i]['target'] == 0?'-':round($department[$i]['actsale']/$department[$i]['target']*100,2).'%';
//            $department[$i]['year_rate'] = $department[$i]['actsale']/$department[$i]['year_target'] == 0?'-':round($department[$i]['actsale']/$department[$i]['year_target']*100,2).'%';
//            $department[$i]['year_rate2'] = $department[$i]['actssale']/$department[$i]['year_starget'] == 0?'-':round($department[$i]['actssale']/$department[$i]['year_starget']*100,2).'%';
//            $total['actsale'] += $department[$i]['actsale'];
//            $total['target'] += $department[$i]['target'];
//            $total['year_target'] += $department[$i]['year_target'];
//            $total['year_starget'] += $department[$i]['year_starget'];
//            $department[$i]['actsale'] = round($department[$i]['actsale']);
//            $department[$i]['year_target'] = round($department[$i]['year_target']);
//            $department[$i]['target'] = round($department[$i]['target']);
//            $department[$i]['year_starget'] = round($department[$i]['year_starget']);
//        }
//        $total['store'] = '总计';
//        $total['color'] = '#0A95EC';
//        $total['gap'] = round($total['actssale'] - $total['starget']);
//        $total['starget_rate'] = $total['starget'] == 0?'-':round($total['actssale']/$total['starget']*100,2).'%';
//        $total['actsale'] = round($total['actsale']);
//        $total['actssale'] = round($total['actssale']);
//        $total['starget'] = round($total['starget']);
//        $total['target_rate'] = $total['target'] == 0?'-':round($total['actsale']/$total['target']*100,2).'%';
//        $total['year_rate'] = round($total['actsale']/$total['year_target']*100,2).'%';
//        $total['year_rate2'] = round($total['actssale']/$total['year_starget']*100,2).'%';
//        $total['year_starget'] = round($total['year_starget']);
//        $total['target'] = round($total['target']);
        $d = 0;
//        for($i=0;$i<count($index);$i++)
//        {
//            array_splice($data,$index[$i] + $d,0,array($department[$i]));
//            $d++;
//        }
//        array_push($data,$department[count($department)-1]);
//        array_push($data, $total);
        $data2['date'] = $date2;
        $data2['result'] = $data;
        $data2['sycm_s'] = $sycm_s;
        //$data2['total'] = $total;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*各竞品店铺销售一览表*/
    public function competition($date="",$industry='时尚男装')
    {
        header("Content-type: text/html; charset=utf-8");
        $ZTC = M('competition');
        if($date == '') $date = $ZTC->where("industry = '{$industry}'")->max('date');

        $months = substr($date,0,7);
        $dates = split('-',$months);
        $year = ($dates[1] == '12'?(int)$dates[0]+1:$dates[0]);
        $month = ($dates[1] == '12'?1:(int)$dates[1]+1);
        $newmonth = $month < 10?$year.'-0'.$month:$year.'-'.$month;
        $condition['date'] = $date;
        $condition['industry'] = $industry;
        $data = $ZTC->field('store,money,sales')->where($condition)->select();
        $industry_data = $ZTC->field('industry')->group('industry')->select();
        echo mysql_error();
        $total = array();
        for($i=0;$i<count($data);$i++)
        {
            $data[$i]['price'] = round($data[$i]['money']/$data[$i]['sales']);
            $total['money'] += $data[$i]['money'];
            $total['sales'] += $data[$i]['sales'];
        }
        for($i=0;$i<count($industry_data);$i++)
        {
            $industry_array[$i] = $industry_data[$i]['industry'];
        }
        /*日期数据*/
        $store = $ZTC->field('store,date')->group('store')->where("date > '{$months}' and date < '{$newmonth}' and industry='{$industry}'")->select();

        $month_data =array();
        $condition2['date'] = array('gt',$months,'AND','lt',$newmonth,'AND');
        for($i=0;$i<count($store);$i++)
        {
            $month_total = array();
            $condition2['store'] = array('eq',$store[$i]['store']);
            $store_data = $ZTC->field('date,money')->where($condition2)->where("date > '{$months}' and date < '{$newmonth}' and industry='{$industry}'")->order('date')->select();
            echo mysql_error();
            for($j=0;$j<count($store_data);$j++){
                $month_total['money'] += $store_data[$j]['money'];
            }
            $month_total['color'] = '#0A95EC';
            $month_data[$i]['data'] = $store_data;
            array_push($month_data[$i]['data'],$month_total);
            $month_data[$i]['store'] = $store[$i]['store'];
        }
        $total['price'] = round($total['money']/$total['sales']);
        $total['store'] = '总计';
        $total['color'] = '#0A95EC';
        array_push($data,$total);
        $month_date = $ZTC->where("date > '{$months}' and date < '{$newmonth}' and industry='{$industry}'")->group('date')->field('date')->order('date')->select();
        array_push($month_date,['date'=>'总计','color'=>'#0A95EC']);
        $data2['date'] = $date;
        $data2['month_data'] = $month_data;
        $data2['month_date'] = $month_date;
        $data2['result'] = $data;
        $data2['industry'] = $industry_array;
        //$data2['total'] = $total;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*店铺销售结构表*/
    public function structure($store_name='',$date='')
    {
        header("Content-type: text/html; charset=utf-8");
        $ZTC = M('structure');
        if($date == ''){
            $date = $ZTC->max('date');
        }
        $data = $ZTC->where("date = '{$date}' and store = '{$store_name}'")->select();
        $store = $ZTC->group('store')->field('store')->where("date = '{$date}'")->select();
        $date_data = $ZTC->group('date')->field('date')->order('date')->select();
        for($i=0;$i<count($data);$i++)
        {
            $data[$i]['color'] = $data[$i]['category'] == '总计'?'#0A95EC':'';
            $data[$i]['order'] = $data[$i]['order']==0?'-':$data[$i]['order'];
            $data[$i]['money'] = $data[$i]['money']==0?'-':round($data[$i]['money']);
            $data[$i]['rate'] = $data[$i]['rate']==0?'-':round($data[$i]['rate']*100,2).'%';
            $data[$i]['price'] = $data[$i]['price']==0?'-':round($data[$i]['price']);
            $data[$i]['link'] = $data[$i]['link']==0?'-':$data[$i]['link'];
            $data[$i]['stock'] = $data[$i]['stock']==0?'-':$data[$i]['stock'];
            $data[$i]['value'] = $data[$i]['value']==0?'-':$data[$i]['value'];
        }
        for($i=0;$i<count($store);$i++)
        {
            $store_array[$i] = $store[$i]['store'];
        }
        for($i=0;$i<count($date_data);$i++)
        {
            $date_array[$i] = $date_data[$i]['date'];
        }
        $data2['date'] = $date;
        $data2['date_array'] = $date_array;
        $data2['store'] = $store_array;
        $data2['result'] = $data;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*子行业*/
    public function ranking($date='',$category='')
    {
        $ZTC = M('ranking');
        if($date == '' and $category == ''){ $date = $ZTC->max('date');}
        if($date == ''){ $date = $ZTC->where("category = '{$category}'")->max('date');}
        $data = $ZTC->where("date = '{$date}' and category = '{$category}'")->order(array('class','rank'))->select();
        $dates = $ZTC->field('date')->group('date')->order('date')->select();
        $store = $ZTC->field('category')->group('category')->select();
        for ($i=0;$i<count($data);$i++){
            $data[$i]['money'] = $data[$i]['price']*$data[$i]['visitor']*$data[$i]['rate'];
            $data[$i]['last_rate'] = round((($data[$i]['money']-$data[$i]['last'])/$data[$i]['last']*100),2);
            if($data[$i]['last_rate'] < 0){
                $data[$i]['last_rate'] = (-$data[$i]['last_rate'])."%";
                $data[$i]['color'] = 'red';
            }else{
                $data[$i]['last_rate'] = $data[$i]['last_rate'] == 0?'-':$data[$i]['last_rate'].'%';
            }
            $data[$i]['money'] = $data[$i]['money'] == 0?'-':round($data[$i]['money']);
            $data[$i]['sales'] = $data[$i]['sales'] == 0?'-':round($data[$i]['sales']);
        }
        for($i=0;$i<count($dates);$i++){
            $date_array[$i] = $dates[$i]['date'];
        }
        for($i=0;$i<count($store);$i++){
            $store_array[$i] = $store[$i]['category'];
        }
        $data2['result'] = $data;
        $data2['date'] = $date;
        $data2['store'] = $store_array;
        $data2['date_array'] = $date_array;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    public function tx($date=''){
        $ZTC = M('tx');
        if($date == '') $date = $ZTC->max('date');
        $data = $ZTC->where("date = '{$date}'")->select();
        for($i=0;$i<count($data);$i++){
            $data[$i]['zfzhl'] = round($data[$i]['zfzhl']*100,1)."%";
            $data[$i]['xszk'] = round($data[$i]['xszk']*100,1)."%";
        }
        $data2['data'] = $data;
        $data2['date'] = $date;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }
    public function stock($date=''){
        $ZTC = M('stock');
        $data = $ZTC->select();
        for($i=0;$i<count($data);$i++){
            $data[$i]['stockr'] = round($data[$i]['stockr']*100,1)."%";
            $data[$i]['saler'] = round($data[$i]['saler']*100,1)."%";
            $data[$i]['total'] = round($data[$i]['total']*100,1)."%";
        }
        $data2['data'] = $data;
        $data2['date'] = $date;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }
    public function wph($date=''){
        $ZTC = M('wph');
        if($date == '') $date = $ZTC->max('date');
        $data = $ZTC->where("date = '{$date}'")->select();
        for($i=0;$i<count($data);$i++){
            $data[$i]['dcl'] = round($data[$i]['dcl'],1)."%";
        }
        $data2['data'] = $data;
        $data2['date'] = $date;
        $json = json_encode($data2,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*登陆*/
    public function login($name,$pwd)
    {
        $password = MD5($pwd);
        $user = M('user')->where("name = '{$name}'")->select();
        if($user){
            if(trim($user[0]['password']) == $password){
                $result['status'] =  1;
            }else{
                $result['status'] = 2;
            }
        }else{
            $result['error'] = mysql_error();
            $result['status'] = 2;
        }
        $result['user'] = $user[0];
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }
    /*数据库操作*/

    public function user()
    {
        $ZTC = M('user');
        $data = $ZTC->where("id > 2")->select();
        $result['data'] = $data;
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    /*添加账户初始化*/
    public function add()
    {
        $data1 = M('ranking')->group('category')->field('category')->select();
        $data2 = M('competition')->group('industry')->field('industry')->select();
        $data3 = M('structure')->group('store')->field('store')->select();
        $data4 = M('sycm')->group('store')->field('store')->where('date > 2017-01-01')->select();
        echo mysql_error();
        for($i=0;$i<count($data1);$i++){
            $category[$i] = $data1[$i]['category'];
        }
        for($i=0;$i<count($data2);$i++){
            $industry[$i] = $data2[$i]['industry'];
        }
        for($i=0;$i<count($data3);$i++){
            $store[$i] = $data3[$i]['store'];
        }
        for($i=0;$i<count($data4);$i++){
            $sycm[$i] = $data4[$i]['store'];
        }
        $result['category'] = $category;
        $result['industry'] = $industry;
        $result['store'] = $store;
        $result['sycm'] = $sycm;
        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    public function adduser($name,$password,$nickname,$category,$industry,$store,$unshow,$sycm)
    {
        $data['name'] = $name;
        $data['password'] = $password;
        $data['nickname'] = $nickname;
        $category = json_decode($category);
        $industry = json_decode($industry);
        $store = json_decode($store);
        $unshow = json_decode($unshow);
        $sycm = json_decode($sycm);
        $sycm_s = $sycm[0];
        $unshow_s = $unshow[0];
        $category_s = $category[0];
        $industry_s = $industry[0];
        $store_s = $store[0];
        for($i=1;$i<count($category);$i++){
            $category_s = $category_s.','.$category[$i];
        }
        for($i=1;$i<count($industry);$i++){
            $industry_s = $industry_s.','.$industry[$i];
        }
        for($i=1;$i<count($store);$i++){
            $store_s = $store_s.','.$store[$i];
        }
        for($i=1;$i<count($unshow);$i++){
            $unshow_s = $unshow_s.','.$unshow[$i];
        }
        for($i=1;$i<count($sycm);$i++){
            $sycm_s = $sycm_s.','.$sycm[$i];
        }
        $data['category'] = $category_s;
        $data['industry'] = $industry_s;
        $data['store'] = $store_s;
        $data['unshow'] = $unshow_s;
        $data['sycm'] = $sycm_s;
        $data['right'] = 3;
        $data['password'] = MD5($password);
        $result['data'] = $data;
        $result['category'] = $category;
        if(M('user')->where("name = '{$name}'")->select())
        {
            if(M('user')->data($data)->save())
            {
                $result['status'] = 1;
            }else{
                $result['status'] = 2;
            }
        }else{
            if(M('user')->data($data)->add())
            {
                $result['status'] = 1;
            }else{
                $result['status'] = 2;
            }
        }

        $json = json_encode($result,JSON_UNESCAPED_UNICODE);
        $cha=mb_detect_encoding($json);
        echo $s = iconv($cha,"utf-8",$json);
    }

    public function change()
    {
        header("Content-type: text/html; charset=utf-8");

        M('ranking')->where("date >= '2017/07/01-07/31'")->delete();
        echo mysql_error();
    }


}