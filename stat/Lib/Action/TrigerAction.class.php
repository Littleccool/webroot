<?php
// 老虎机分析文件

class TrigerAction extends BaseAction {

	protected $By_tpl = 'Triger'; 
	
	//老虎机局数
	public function tongji1(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		//$table1 = "log_tiger_record_log";
		//$row1 = M($table1, '', DB_CONFIG2);
		$table5 = "user_info";
		$row5 = M($table5, '', DB_CONFIG2);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$channel = I("channel");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		if (!empty($beginTime) && !empty($endTime)){
			$date11 = date("Y-m-d", strtotime($beginTime));
			$date12 = date("Y-m-d", strtotime($endTime));
			$day_jian = (strtotime($date12) - strtotime($date11)) / (60 * 60 * 24) + 1;
			
			//$datenow = date("d", strtotime($endTime));
			//$dateend = date("d", strtotime($beginTime));
		}else{
			$date12 = date("Y-m-d",strtotime("-1 day"));
			$day_jian = PAGE_SHOW;
			$date11 = date("Y-m-d", (strtotime($date12) - 60 * 60 * 24 * ($day_jian - 1)));
		}
		//echo $day_jian;
		//$date11 .= " 00:00:00"; 
		//$date12 .= " 23:59:59"; 
		
		$this->assign('date11',$date11);
		$this->assign('date12',$date12);
		$this->assign('channel',$channel);
		//计算时间区段
		$seldate = array();
		$seldate[0] = array('date1' => date("Y-m-d"),
							'date2' => date("Y-m-d"));
		$seldate[1] = array('date1' => date("Y-m-d",strtotime("-1 day")),
							'date2' => date("Y-m-d",strtotime("-1 day")));
		$seldate[2] = array('date1' => date("Y-m-d",strtotime("-6 day")),
							'date2' => date("Y-m-d"));
		$seldate[3] = array('date1' => date("Y-m-d",strtotime("-29 day")),
							'date2' => date("Y-m-d"));
		$this->assign('seldate',$seldate);
		
		$sql1 = "!((user_id>=".ROBERT1_BEGIN." and user_id<".ROBERT1_END.") or (user_id>=".ROBERT2_BEGIN." and user_id<=".ROBERT2_END."))";
		$sql0 = "";
		if (!empty($channel)){
			$sql3 = " and channel=$channel";
			$sql0 .= " and channel=$channel";
			
			//渠道用户
			$res0 = $row5->field('user_id')->where("channel=$channel")->select();
			$sql4 = "";
			foreach ($res0 as $key => $val){
				$sql4 .= (empty($sql4)) ? $val['user_id'] : ",".$val['user_id'];
			}
			if (!empty($sql4)) $sql4 = " and user_id in ($sql4)";
		}else{
			$sql0 .= " and channel='all'";
			$sql3 = "";
			$sql4 = "";
		}
		
		import('ORG.Util.Page');
		$Page       = new Page($day_jian,PAGE_SHOW);
		$show       = $Page->show();
		//echo $Page->firstRow."**".$Page->listRows."<br>";
		//房间号
		$room = array('1','2','3');
	
		if ($day_jian >= 0){
			$tongji_show = array();
			$tongji1 = array();
			$tongji2 = array();
			$tongji3 = array();
			$tongji4 = array();
			$data0 = '';
			$data1 = array();
			$data2 = array();
			$data3 = array();
			$data4 = array();
			$alltotal1 = 0;
			$alltotal2 = 0;
			$maxi = $day_jian - $Page->firstRow;
			$mini = $day_jian - $Page->firstRow - $Page->listRows;
			if ($mini < 1) $mini = 1;
			for ($i=$maxi; $i>$mini; $i--){
				
				$j = $i - 1;	
				$time1 = strtotime($date11) + 60 * 60 * 24 * ($i - 1);
				$time2 = $time1 + 60 * 60 * 24;
				$date1 = date("Y-m-d", $time1);
				$date2 = date("Y-m-d", $time2);
				//echo $date1."***".$date2."<br>";
				
				if ($date1 < date("Y-m-d")){
					if ($date1 < "2016-01-01"){
						$table1 = "log_tiger_2015";
					}else{
						$table1 = "log_tiger_".date("Ym", $time1);
					}
					$row1 = M($table1, '', DB_CONFIG3);
				}else{
					$table1 = "log_tiger_record_log";
					$row1 = M($table1, '', DB_CONFIG2);
				}
				
				//当日玩老虎机记录
				$flag = "4";
				//echo "**";
				$total = $row->where("data='$date1' and flag=$flag $sql0")->count();
				//dump($row->_sql());
				if ($total == 0){
					$tongji = array();
					for($k=0; $k<10; $k++){
						$tongji[0]['all'][$k]['count'] = 0;
					}
					
					//参与人数
					$count5 = 0;
					//总局数
					$count4 = 0;
					//总用户游戏人数
					$count4 = $row1->where("selecttime>='$time1' and selecttime<'$time2' and type=1")->count('id');
					$count5 = $row1->where("selecttime>='$time1' and selecttime<'$time2' and type=1")->count('distinct user_id');
					
					$sql11 = "SELECT COUNT(user_id) AS nums FROM $table1 WHERE selecttime>=$time1 AND selecttime<$time2 GROUP BY user_id ";
					$row11 = $row1->query($sql11);
					foreach($row11 as $key11 => $val11){
						$count1 = $val11['nums'];
						if ($count1==0){
							$tongji[0]['all'][0]['count']++;
						}else if ($count1<6){
							$tongji[0]['all'][1]['count']++;
						}else if ($count1<11){
							$tongji[0]['all'][2]['count']++;
						}else if ($count1<16){
							$tongji[0]['all'][3]['count']++;
						}else if ($count1<21){
							$tongji[0]['all'][4]['count']++;
						}else if ($count1<31){
							$tongji[0]['all'][5]['count']++;
						}else if ($count1<41){
							$tongji[0]['all'][6]['count']++;
						}else if ($count1<51){
							$tongji[0]['all'][7]['count']++;
						}else if ($count1<101){
							$tongji[0]['all'][8]['count']++;
						}else{
							$tongji[0]['all'][9]['count']++;
						}
					}
					
					//总投注
					$count2 = $row1->where("$sql1 and selecttime>='$time1' and selecttime<'$time2' and (type=1 or type=2)")->sum('goldnum');
					$count2 = abs($count2);
					//总输赢
					$count3 = $row1->where("$sql1 and selecttime>='$time1' and selecttime<'$time2'")->sum('goldnum');
					if (empty($count3)) $count3 = 0;  else $count3 = -$count3;
					
					//总局数
					//$count4 = $row1->where("$sql1 and selecttime>='$time1' and selecttime<'$time2' and type=1")->count('id');
					//if ($date1=="2015-11-13") dump($row1->_sql());
					//净耗率
					$count6 = (!empty($count2)) ? round($count3/$count2,2) : 0;
					
					$tongji0 = array('data' => $date1,
									 'tongji' => $tongji,
									 'count2' => $count2,
									 'count3' => $count3,
									 'count4' => $count4,
									 'count5' => $count5,
									 'count6' => $count6);
									
					if ($date1<date("Y-m-d")){
						$data9 = array('data' => $date1,
									   'channel' => empty($channel) ? "all" : $channel,
									   'flag' => $flag,
									   'tongji' => json_encode($tongji0),
									   'addtime' => time());
						$result = $row->add($data9);			   
					}
					$tongji1[$j] = $tongji0;	
					//print_r($tongji0);
					//exit;
				}else{
					$info = $row->where("data='$date1' and flag=$flag $sql0")->find();
					$tongji1[$j] = json_decode($info['tongji'], true);
				}
				
			}
		} 
		//print_r($tongji1); 
		//print_r($tongji3);
		$this->assign('tongji1',$tongji1);


		$this->assign('pageshow',$show);
		$this->assign('left_css',"62");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/tongji1";
		$this->display($lib_display);
	}
	
	//老虎机牌型
	public function tongji2(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		//$table1 = "log_tiger_record_log";
		//$row1 = M($table1, '', DB_CONFIG2);
		$table5 = "user_info";
		$row5 = M($table5, '', DB_CONFIG2);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$channel = I("channel");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		if (!empty($beginTime) && !empty($endTime)){
			$date11 = date("Y-m-d", strtotime($beginTime));
			$date12 = date("Y-m-d", strtotime($endTime));
			$day_jian = (strtotime($date12) - strtotime($date11)) / (60 * 60 * 24) + 1;
			
			//$datenow = date("d", strtotime($endTime));
			//$dateend = date("d", strtotime($beginTime));
		}else{
			$date12 = date("Y-m-d");
			$day_jian = PAGE_SHOW;
			$date11 = date("Y-m-d", (strtotime($date12) - 60 * 60 * 24 * ($day_jian - 1)));
		}
		//echo $day_jian;
		//$date11 .= " 00:00:00"; 
		//$date12 .= " 23:59:59"; 
		
		$this->assign('date11',$date11);
		$this->assign('date12',$date12);
		$this->assign('channel',$channel);
		//计算时间区段
		$seldate = array();
		$seldate[0] = array('date1' => date("Y-m-d"),
							'date2' => date("Y-m-d"));
		$seldate[1] = array('date1' => date("Y-m-d",strtotime("-1 day")),
							'date2' => date("Y-m-d",strtotime("-1 day")));
		$seldate[2] = array('date1' => date("Y-m-d",strtotime("-6 day")),
							'date2' => date("Y-m-d"));
		$seldate[3] = array('date1' => date("Y-m-d",strtotime("-29 day")),
							'date2' => date("Y-m-d"));
		$this->assign('seldate',$seldate);
		
		$sql1 = "!((user_id>=".ROBERT1_BEGIN." and user_id<".ROBERT1_END.") or (user_id>=".ROBERT2_BEGIN." and user_id<=".ROBERT2_END."))";
		$sql0 = "";
		if (!empty($channel)){
			$sql3 = " and channel=$channel";
			$sql0 .= " and channel=$channel";
			
			//渠道用户
			$res0 = $row5->field('user_id')->where("channel=$channel")->select();
			$sql4 = "";
			foreach ($res0 as $key => $val){
				$sql4 .= (empty($sql4)) ? $val['user_id'] : ",".$val['user_id'];
			}
			if (!empty($sql4)) $sql4 = " and user_id in ($sql4)";
		}else{
			$sql0 .= " and channel='all'";
			$sql3 = "";
			$sql4 = "";
		}
		
		import('ORG.Util.Page');
		$Page       = new Page($day_jian,PAGE_SHOW);
		$show       = $Page->show();
		//echo $Page->firstRow."**".$Page->listRows."<br>";
		//房间号
		$room = array('1','2','3');
	
		if ($day_jian >= 0){
			$tongji_show = array();
			$tongji1 = array();
			$tongji2 = array();
			$tongji3 = array();
			$tongji4 = array();
			$data0 = '';
			$data1 = array();
			$data2 = array();
			$data3 = array();
			$data4 = array();
			$alltotal1 = 0;
			$alltotal2 = 0;
			$maxi = $day_jian - $Page->firstRow;
			$mini = $day_jian - $Page->firstRow - $Page->listRows;
			if ($mini < 1) $mini = 1;
			for ($i=$maxi; $i>$mini; $i--){
				
				$j = $i - 1;	
				$time1 = strtotime($date11) + 60 * 60 * 24 * ($i - 1);
				$time2 = $time1 + 60 * 60 * 24;
				$date1 = date("Y-m-d", $time1);
				$date2 = date("Y-m-d", $time2);
				//echo $date1."***".$date2."<br>";
				
				//当日玩老虎机记录
				$flag = "5";
				//echo "**";
				$total = $row->where("data='$date1' and flag=$flag $sql0")->count();
				//dump($row->_sql());
				if ($total == 0){
					$tongji = array();
					/*$tongji[0] = array('type' => '1',
									   'name' => '单牌');*/
					$tongji[0] = array('type' => '2',
									   'name' => '对子');
					$tongji[1] = array('type' => '3',
									   'name' => '顺子');
					$tongji[2] = array('type' => '4',
									   'name' => '金花');
					$tongji[3] = array('type' => '5',
									   'name' => '顺金');				   
					$tongji[4] = array('type' => '6',
									   'name' => '豹子');
					$tongji[5] = array('type' => '10',
									   'name' => '地龙');
					$tongji[6] = array('type' => '11',
									   'name' => '一花');
					$tongji[7] = array('type' => '12',
									   'name' => '二花');
					$tongji[8] = array('type' => '13',
									   'name' => '王牌AAA');
					$tongji[9] = array('type' => '14',
									    'name' => '无花');						
									   
					//dump($row5->_sql());
					if ($date1 < date("Y-m-d")){
						if ($date1 < "2016-01-01"){
							$table1 = "log_tiger_2015";
						}else{
							$table1 = "log_tiger_".date("Ym", $time1);
						}
						$row1 = M($table1, '', DB_CONFIG3);
					}else{
						$table1 = "log_tiger_record_log";
						$row1 = M($table1, '', DB_CONFIG2);
					}
					
					foreach ($tongji as $key => $val){
						$count1 = $row1->where("selecttime>='$time1' and selecttime<'$time2' and cardtype='".$val['type']."' and type in (1,2)")->count('id');
						//dump($row1->_sql());
						//if ($date1=="2015-11-13") dump($row1->_sql());
						$tongji[$key]['count'] = $count1;	
							
							
					}
										
					$tongji0 = array('data' => $date1,
									 'tongji' => $tongji);
									
					if ($date1<date("Y-m-d")){
						$data9 = array('data' => $date1,
									   'channel' => empty($channel) ? "all" : $channel,
									   'flag' => $flag,
									   'tongji' => json_encode($tongji0),
									   'addtime' => time());
						$result = $row->add($data9);			   
					}
					$tongji1[$j] = $tongji0;	
					//print_r($tongji0);
					//exit;
				}else{
					$info = $row->where("data='$date1' and flag=$flag $sql0")->find();
					$tongji1[$j] = json_decode($info['tongji'], true);
				}
				
			}
		} 
		//print_r($tongji1); 
		//print_r($tongji3);
		$this->assign('tongji1',$tongji1);


		$this->assign('pageshow',$show);
		$this->assign('left_css',"62");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/tongji2";
		$this->display($lib_display);
	}
	
	//老虎机详情
	public function tongji3(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		
		$table5 = "user_info";
		$row5 = M($table5, '', DB_CONFIG2);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$user_id = I("user_id");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		if (!empty($beginTime) && !empty($endTime)){
			$date11 = date("Y-m-d", strtotime($beginTime));
			$date12 = date("Y-m-d", strtotime($endTime));
		}else{
			$date12 = date("Y-m-d");
			$date11 = $date12;
		}
		
		$this->assign('date11',$date11);
		$this->assign('date12',$date12);
		$this->assign('user_id',$user_id);
		
		//计算时间区段
		$seldate = array();
		$seldate[0] = array('date1' => date("Y-m-d"),
							'date2' => date("Y-m-d"));
		$seldate[1] = array('date1' => date("Y-m-d",strtotime("-1 day")),
							'date2' => date("Y-m-d",strtotime("-1 day")));
		$seldate[2] = array('date1' => date("Y-m-d",strtotime("-6 day")),
							'date2' => date("Y-m-d"));
		$seldate[3] = array('date1' => date("Y-m-d",strtotime("-29 day")),
							'date2' => date("Y-m-d"));
		$this->assign('seldate',$seldate);
		
		if (!empty($user_id)) $sql0 = " and user_id=$user_id"; else $sql0 = "";
		
		$time11 = strtotime($date11);
		$time12 = strtotime($date12) + 60 * 60 * 24;
		
		if ($date11 < date("Y-m-d")){
			if ($date11 < "2016-01-01"){
				$table1 = "log_tiger_2015";
			}else{
				$table1 = "log_tiger_".date("Ym", $time11);
			}
			$row1 = M($table1, '', DB_CONFIG3);
		}else{
			$table1 = "log_tiger_record_log";
			$row1 = M($table1, '', DB_CONFIG2);
		}
		
		
		$count = $row1->where("selecttime>=$time11 and selecttime<$time12 and type in (1,2) $sql0")->count('id');
		import('ORG.Util.Page');
		$Page       = new Page($count,PAGE_SHOW);	
		$show       = $Page->show();
		$info = $row1->where("selecttime>=$time11 and selecttime<$time12 and type in (1,2) $sql0")->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($row1->_sql());
		foreach ($info as $key => $val){
				if ($val['iswin']==0) $win_count++; else $lost_count++;
				$num1 = substr($val['cards'],0,2);
				$pai1 = '<img src="'.DB_HOST.'/Public/images/'.$num1.'.png" width="30">';
				$num2 = substr($val['cards'],2,2);
				$pai2 = '<img src="'.DB_HOST.'/Public/images/'.$num2.'.png" width="30">';
				$num3 = substr($val['cards'],4,2);
				$pai3 = '<img src="'.DB_HOST.'/Public/images/'.$num3.'.png" width="30">';
				$showcards = $pai1.$pai2.$pai3;
				$info[$key]['showcards'] = $showcards;
				
				switch ($val['cardtype']){
					case 1:  $cardtype = "单牌"; break;
					case 2:  $cardtype = "对子"; break;
					case 3:  $cardtype = "顺子"; break;
					case 4:  $cardtype = "金花"; break;
					case 5:  $cardtype = "顺金"; break;
					case 6:  $cardtype = "豹子"; break;
					case 10: $cardtype = "地龙"; break;
					case 11: $cardtype = "一花"; break;
					case 12: $cardtype = "二花"; break;
					case 13: $cardtype = "王牌AAA"; break;
					case 14: $cardtype = "无花"; break;
					default: $cardtype = "未知"; break;
				}
				$info[$key]['cardtype'] = $cardtype;
				$info[$key]['type'] = ($val['type']==1) ? "买" : "换";
				
				//获取系统结算
				$nextid = $val['id'] + 1;
				$res = $row1->where("id=".$nextid)->find();
				if ($res['type'] == 3){
					$info[$key]['xtjs'] = $res['goldnum'];
				}else{
					$info[$key]['xtjs'] = 0;
				}
		}
		
		$sum = array(0,0);
		$sum[0] = $row1->where("selecttime>$time11 and selecttime<$time12 and type in (1,2) $sql0")->sum('goldnum');
		$sum[1] = $row1->where("selecttime>$time11 and selecttime<$time12 and type=3 $sql0")->sum('goldnum');
		$this->assign('sum',$sum);
		$this->assign('info',$info);
		$this->assign('pageshow',$show);
		
		$this->assign('left_css',"62");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/tongji3";
		$this->display($lib_display);
	}
	
	//时时彩详情
	public function tongji4(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		$table1 = "kingflower.lottery_log";
		$row1 = M($table1);
		$table2 = "kingflower.log_lottery_bet_log";
		$row2 = M($table2);
		$table5 = "user_info";
		$row5 = M($table5);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$user_id = I("user_id");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		if (!empty($beginTime) && !empty($endTime)){
			$date11 = date("Y-m-d", strtotime($beginTime));
			$date12 = date("Y-m-d", strtotime($endTime));
		}else{
			$date12 = date("Y-m-d");
			$date11 = $date12;
		}
		
		$this->assign('date11',$date11);
		$this->assign('date12',$date12);
		$this->assign('user_id',$user_id);
		
		//计算时间区段
		$seldate = array();
		$seldate[0] = array('date1' => date("Y-m-d"),
							'date2' => date("Y-m-d"));
		$seldate[1] = array('date1' => date("Y-m-d",strtotime("-1 day")),
							'date2' => date("Y-m-d",strtotime("-1 day")));
		$seldate[2] = array('date1' => date("Y-m-d",strtotime("-6 day")),
							'date2' => date("Y-m-d"));
		$seldate[3] = array('date1' => date("Y-m-d",strtotime("-29 day")),
							'date2' => date("Y-m-d"));
		$this->assign('seldate',$seldate);
		
		if (!empty($user_id)) $sql0 = " "; else $sql0 = "";
		
		$time11 = strtotime($date11);
		$time12 = strtotime($date12) + 60 * 60 * 24;
		$count = $row1->where("intime>'$date11' and intime<'$date12 23:59:59' $sql0")->count('id');
		//dump($row1->_sql());
		import('ORG.Util.Page');
		$Page       = new Page($count,PAGE_SHOW);	
		$show       = $Page->show();
		$info = $row1->where("intime>'$date11' and intime<'$date12 23:59:59' $sql0")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($row1->_sql());
		
		foreach ($info as $key => $val){
			$max_win = 0;
			$max_lost = 0;
			//最大赢家盈利额lotteryid
			$res = $row2->field("user_id")->group("user_id")->where("lotteryid=".$val['lotteryid'])->select();
			foreach($res as $key1 => $val1){
				$win = $row2->where("lotteryid=".$val['lotteryid']." and user_id=".$val1['user_id']." and type=2")->sum('gold');
				$lost = $row2->where("lotteryid=".$val['lotteryid']." and user_id=".$val1['user_id']." and type=1")->sum('gold');
				if ($win > $max_win) $max_win = $win;
				if ($lost < $max_lost) $max_lost = $lost;
			}
			
			$info[$key]['max_win'] = $max_win;
			$info[$key]['max_lost'] = $max_lost;
		}
		//print_r($info);
		
		$this->assign('info',$info);
		$this->assign('pageshow',$show);
		
		$this->assign('left_css',"62");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/tongji4";
		$this->display($lib_display);
	}
	
	//时时彩详情
	public function more(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		$table1 = "kingflower.lottery_log";
		$row1 = M($table1);
		$table2 = "kingflower.log_lottery_bet_log";
		$row2 = M($table2);
		$table5 = "user_info";
		$row5 = M($table5);
		
		$lotteryid = I("lotteryid");
		$endTime = I("endTime");
		$user_id = I("user_id");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (empty($lotteryid)){
			$this->error('输入有误');
			exit;
		}
		

		$count = $row2->where("lotteryid=".$lotteryid)->count('id');
		//dump($row1->_sql());
		import('ORG.Util.Page');
		$Page       = new Page($count,PAGE_SHOW);	
		$show       = $Page->show();
		$info = $row2->where("lotteryid=".$lotteryid)->order("id ")->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($row2->_sql());
		
		foreach ($info as $key => $val){

			if ($val['type']==1){
				$info[$key]['type'] = "购买";
			}else if ($val['type']==1){
				$info[$key]['type'] = "领奖";
			}else{
				$info[$key]['type'] = "";
			}
			$info[$key]['selecttime'] = date("Y-m-d H:i:s", $val['selecttime']);
		}
		//print_r($info);
		
		$this->assign('info',$info);
		$this->assign('pageshow',$show);
		
		$this->assign('left_css',"62");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/more";
		$this->display($lib_display);
	}
	
	//EXCEL导出
	public function exceldo(){
		$table = "fx_paiju_tongji";
		$row = M($table);
		$table1 = "log_tiger_record_log";
		$row1 = M($table1, '', DB_CONFIG2);
		$table5 = "user_info";
		$row5 = M($table5, '', DB_CONFIG2);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$user_id = I("user_id");
		
		//查询不能大于当天
		$timenow = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		if (!empty($beginTime) && !empty($endTime)){
			$date11 = date("Y-m-d", strtotime($beginTime));
			$date12 = date("Y-m-d", strtotime($endTime));
		}else{
			$date12 = date("Y-m-d");
			$date11 = $date12;
		}
		
		
		if (!empty($user_id)) $sql0 = " and user_id=$user_id"; else $sql0 = "";
		
		$time11 = strtotime($date11);
		$time12 = strtotime($date12) + 60 * 60 * 24;
		//$count = $row1->where("selecttime>$time11 and selecttime<$time12 and type in (1,2) $sql0")->count('id');
		//dump($row1->_sql());
		//import('ORG.Util.Page');
		//$Page       = new Page($count,PAGE_SHOW);	
		//$show       = $Page->show();
		$info = $row1->where("selecttime>$time11 and selecttime<$time12 and type in (1,2) $sql0")->order("id")->select();
		//dump($row1->_sql());
		foreach ($info as $key => $val){
				if ($val['iswin']==0) $win_count++; else $lost_count++;
				$num1 = substr($val['cards'],0,2);
				$pai1 = '<img src="'.DB_HOST.'/Public/images/'.$num1.'.png" width="30">';
				$num2 = substr($val['cards'],2,2);
				$pai2 = '<img src="'.DB_HOST.'/Public/images/'.$num2.'.png" width="30">';
				$num3 = substr($val['cards'],4,2);
				$pai3 = '<img src="'.DB_HOST.'/Public/images/'.$num3.'.png" width="30">';
				$showcards = $pai1.$pai2.$pai3;
				$info[$key]['showcards'] = $showcards;
				
				switch ($val['cardtype']){
					case 1:  $cardtype = "单牌"; break;
					case 2:  $cardtype = "对子"; break;
					case 3:  $cardtype = "顺子"; break;
					case 4:  $cardtype = "金花"; break;
					case 5:  $cardtype = "顺金"; break;
					case 6:  $cardtype = "豹子"; break;
					case 10: $cardtype = "地龙"; break;
					case 11: $cardtype = "一花"; break;
					case 12: $cardtype = "二花"; break;
					case 13: $cardtype = "王牌AAA"; break;
					case 14: $cardtype = "无花"; break;
					default: $cardtype = "未知"; break;
				}
				$info[$key]['cardtype'] = $cardtype;
				$info[$key]['type'] = ($val['type']==1) ? "买" : "换";
				
				//获取系统结算
				$nextid = $val['id'] + 1;
				$res = $row1->where("id=".$nextid)->find();
				if ($res['type'] == 3){
					$info[$key]['xtjs'] = $res['goldnum'];
				}else{
					$info[$key]['xtjs'] = 0;
				}
		}
		
		$sum = array(0,0);
		$sum[0] = $row1->where("selecttime>$time11 and selecttime<$time12 and type in (1,2) $sql0")->sum('goldnum');
		$sum[1] = $row1->where("selecttime>$time11 and selecttime<$time12 and type=3 $sql0")->sum('goldnum');
		$this->assign('sum',$sum);
		$this->assign('info',$info);
		$this->assign('pageshow',$show);
		
		$xlsName  = "老虎机详情";
		$xlsCell  = array(
			array('user_id','用户UID'),
			array('type','类别'),
			array('cardtype','牌型'),
			array('goldnum','投注金币数'),
			array('xtjs','系统结算'),
			array('curgold','当前金币数'),
			array('disdate','添加时间')   
		);
		$xlsData = array();
		foreach ($info as $k => $v)
		{
				$xlsData[$k]['user_id'] = $v['user_id'];
				$xlsData[$k]['type'] = $v['type'];
				$xlsData[$k]['cardtype'] = $v['cardtype'];
				$xlsData[$k]['goldnum'] = $v['goldnum'];
				$xlsData[$k]['xtjs'] = $v['xtjs'];
				$xlsData[$k]['curgold'] = $v['curgold'];
				$xlsData[$k]['disdate'] = $v['disdate'];
		}
		exportExcel($xlsName,$xlsCell,$xlsData);
		exit;

	}
	
	//大转盘抽奖记录
	public function wheel(){

		$row = M("log_lotterydraw_record_log", '', DB_CONFIG2);
		$user = M("user_info", '', DB_CONFIG2);
		
		import('ORG.Util.Page');
		
		$user_id = I("user_id");
		$awardtype = I("awardtype");
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		
		if (!empty($beginTime) && !empty($endTime)){
			$time1 = strtotime($beginTime); 
			$time2 = strtotime($endTime) + 86400; 
			$sql11 .= " and (operator>=$time1 and operator<$time2)";
			//统计
			$sum = array(array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0));
			for($i=1; $i<=3; $i++){
				$sum[$i-1][0] = $row->where("awardtype=1 and drawtype=".$i.$sql11)->count('id');
				$sum[$i-1][1] = $row->where("awardtype=2 and drawtype=".$i.$sql11)->count('id');
				$sum[$i-1][2] = $row->where("awardtype=3 and drawtype=".$i.$sql11)->count('id');
				$sum[$i-1][3] = $row->where("awardtype=4 and drawtype=".$i.$sql11)->count('id');
				$sum[$i-1][4] = $row->where("awardtype=5 and drawtype=".$i.$sql11)->count('id');
			}
			//print_r($sum);
			$this->assign('sum',$sum);
			$this->assign('showsum',1);

		}else{
			$endTime = date("Y-m-d");
			$beginTime = date("Y-m-d");
			$this->assign('showsum',0);
		}
		
		$this->assign('awardtype',$awardtype);
		$this->assign('user_id',$user_id);
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		
		$sql11 = "";
		if (!empty($awardtype)) $sql11 .= " and awardtype=$awardtype"; 
		if (!empty($user_id)) $sql11 .= " and user_id=$user_id";  
		if (!empty($beginTime) && !empty($endTime)) {$time1 = strtotime($beginTime); $time2 = strtotime($endTime) + 86400; $sql11 .= " and (operator>=$time1 and operator<$time2)"; }		 			
		$count = $row->where("1".$sql11)->count('id');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$show       = $Page->show();// 分页显示输出
		$list = $row->where("1".$sql11)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($row->_sql());
		//exit;
		foreach($list as $key=>$val){
			$list[$key]['operator'] =  date("Y-m-d H:i:s", $val['operator']);
			$userinfo = $user->field('nick_name,nickname')->where("user_id=".$val['user_id'])->find();
			$list[$key]['nickname'] = (!empty($userinfo['nickname'])) ? $userinfo['nickname'] : $userinfo['nick_name'];
			
			if ($val['drawtype']==1) {
				$list[$key]['type'] = '免费抽'; 
			}elseif ($val['drawtype']==2){
				$list[$key]['type'] = '金币抽'; 
			}elseif ($val['drawtype']==3){
				$list[$key]['type'] = '钻石抽'; 
			}
			
			switch($val['awardtype']){
				case 1: $list[$key]['showtype'] = "金币"; break;
				case 2: $list[$key]['showtype'] = "话费"; break;
				case 3: $list[$key]['showtype'] = "车"; break;
				case 4: $list[$key]['showtype'] = "飞机"; break;
				case 5: $list[$key]['showtype'] = "奖券"; break;
				default: $list[$key]['showtype'] = ""; break;
			}
		}
		//print_r($list);
		$this->assign('list',$list);
		$this->assign('pageshow',$show);
		
		
		
		
		$this->assign('left_css',"20");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/wheel";
		$this->display($lib_display);		
	}
	
	//奖券兑换记录
	public function lottery(){

		$row = M("log_mall_lottery_log", '', DB_CONFIG2);
		$user = M("user_info", '', DB_CONFIG2);
		
		import('ORG.Util.Page');
		
		$id = I("id");
		$act = I("act");
		$user_id = I("user_id");
		$type = I("type");
		$status = I("status");
		$this->assign('type',$type);
		$this->assign('status',$status);
		$this->assign('user_id',$user_id);
		
		if ($act == "edit" && !empty($id)){
			if (!empty($_POST)){
				$data = array();
				$data['status'] = $_POST['status'];
				$data['meno'] = $_POST['meno'];
				$result = $row->where("id=".$id)->save($data);
				if($result){
					//发邮件
					$table5 = M("user_email", '', DB_CON_GAME);
					$arr = array();
					$arr['user_id'] = $user_id;
					$arr['email_type'] = 7;
					$arr['is_read'] = 0;
					$arr['content'] = $_POST['meno'];
					$arr['opera_date'] = date("Y-m-d H:i;s");
					$email_id = $table5->add($arr);
					
					$this->success('修改成功',U($this->By_tpl.'/lottery'));
					exit;
				}else{

					$this->error('修改失败');
					exit;
				}
			}else{
				$info = $row->where("id=".$id)->find();
				$this->assign('info',$info);
				$this->assign('left_css',"20");
				$this->assign('By_tpl',$this->By_tpl);
				$lib_display = $this->By_tpl."/lottery_more";
				$this->display($lib_display);
				exit;
			}
		}
		
		$sql11 = "";
		if (!empty($type)) $sql11 .= " and type=$type"; 
		if (!empty($user_id)) $sql11 .= " and user_id=$user_id";  
		if ($status == "1" || $status == "3") $sql11 .= " and status=$status"; 
		if ($status == "2") $sql11 .= " and status=0"; 
	
		$count = $row->where("1".$sql11)->count('id');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$show       = $Page->show();// 分页显示输出
		$list = $row->where("1".$sql11)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
		//dump($row->_sql());
		//exit;
		foreach($list as $key=>$val){
			$list[$key]['addtime'] =  date("Y-m-d H:i:s", $val['addtime']);
			$userinfo = $user->field('nick_name,nickname')->where("user_id=".$val['user_id'])->find();
			$list[$key]['nickname'] = (!empty($userinfo['nickname'])) ? $userinfo['nickname'] : $userinfo['nick_name'];
			
			if ($val['type']==1) {
				$list[$key]['showtype'] = '兑换金币'; 
			}elseif ($val['type']==3){
				$list[$key]['showtype'] = '兑换话费'; 
			}elseif ($val['type']==4){
				$list[$key]['showtype'] = '兑换SVIP卡'; 
			}
			
			if ($val['status']==1) {
				$list[$key]['status'] = '成功'; 
			}elseif ($val['status']==3){
				$list[$key]['status'] = '已兑换'; 
			}else{
				$list[$key]['status'] = '失败'; 
			}
		}
		//print_r($list);
		$this->assign('list',$list);
		$this->assign('pageshow',$show);
		
		$this->assign('left_css',"20");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/lottery";
		$this->display($lib_display);		
	}
	
	//百人场庄家统计
	public function brc_bank(){
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$bankall = I("bankall");
		$win = I("win");
		$bankid = I("bankid");
		$act = I("act");
		//echo $act."***********************"; 
		//查询不能大于当天
		if (empty($bankall)) $bankall = "ALL";
		$todaytime = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		
		
		import('ORG.Util.Page');
		if ((!empty($beginTime) && !empty($endTime)) && $endTime != date("Y-m-d")){
			
			$sql0 = "";
			if (!empty($bankall) && $bankall!="ALL") $sql0 .= " and bankid='$bankall'";
			if (!empty($win)) $sql0 .= ($win == 1) ? " and vargold >= 0" : " and vargold < 0";
			if (!empty($bankid)) $sql0 .= " and bankid='$bankid'";
			
			$time1 = strtotime($beginTime);
			$time2 = strtotime($endTime);
			
			$bank = array();
			$banklist = array();
			
			$sum = array(0,0,0,0,0,0,0,0,0,0);
			
			for($i=$time2; $i>=$time1; $i-=86400){
				$table = "log_brc_bank_".date("Ymd", $i);
				//echo $table."***************<br>";
				$bank_model = M($table, '', DB_CONFIG3);
				//获取所有庄家
				$bank1 = $bank_model->field("banknickname,bankid")->group("banknickname,bankid")->select();
				
				foreach($bank1 as $key1 => $val1){
					$flag = 0;
					foreach($bank as $key => $val){
						if ($val['banknickname'] == $val1['banknickname']){
							$flag = 1; break;
						}
					}
					if ($flag == 0){
						$bank[] = array('banknickname' => $val1['banknickname'], 'bankid' => $val1['bankid']);
					}
 				}
				
				$banklist1 = $bank_model->where($sql0)->order("id desc")->select();
				foreach($banklist1 as $key => $val){
					
					$sum[0]++;
					if ($val['bankid'] == 0){
						$sum[1]++;
						$sum[2] += $val['vargold'];
						$sum[3] += $val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['nouthxzcount'];
						if ($val['jiangjin'] > 0) $sum[4]++;
					}
					$sum[5] += $val['rate'];
					$sum[9] += $val['jiangjin'];
					if ($val['bankid']!= 0){
						$sum[6]++;
						$sum[7] += $val['vargold'];
						$sum[8] += $val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['nouthxzcount'];
					}

					
					$banklist[] = array(
										'beforgamegold' => number_format($val['beforgamegold']),
										'vargold' => number_format($val['vargold']),
										'aftergamegold' => number_format($val['aftergamegold']),
										'eastxzcount' => number_format($val['eastxzcount']),
										'southxzcount' => number_format($val['southxzcount']),
										'westxzcount' => number_format($val['westxzcount']),
										'nouthxzcount' => number_format($val['nouthxzcount']),
										'rate' => number_format($val['rate']),
										'id' => $val['id'],
										'banknickname' => $val['banknickname'],
										'cardtyperate' => $val['cardtyperate'],
										'xzplayercount' => $val['xzplayercount'],
										'jiangjin' => $val['jiangjin'],
										'fazhi' => $val['fazhi'],
										'gameid' => $val['gameid'],
										'operatedate' => $val['operatedate'],
										'card' => $val['card']
										);
				}

			}
			
			$sum[2] = number_format($sum[2]);
			$sum[3] = number_format($sum[3]);
			$sum[5] = number_format($sum[5]);
			$sum[7] = number_format($sum[7]);
			$sum[8] = number_format($sum[8]);
			$this->assign('sum',$sum);
			
			$this->assign('bank',$bank);
			
			$count = count($banklist);
			$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
			$pageshow   = $Page->show();// 分页显示输出
			$banklist0 = array();
			for($i=$Page->firstRow; $i<$Page->firstRow+$Page->listRows; $i++){
				$banklist0[$i] = $banklist[$i];
			}
			
			$this->assign('pageshow',$pageshow);
			$this->assign('list',$banklist0);
						
		}else{
			
			$endTime = date("Y-m-d");
			$day_jian = 1;
			$beginTime = date("Y-m-d");
			
			$sql0 = "(operatedate>='$beginTime 00:00:00' and operatedate<='$endTime 23:59:59')";
			if (!empty($bankall) && $bankall!="ALL") $sql0 .= " and bankid='$bankall'";
			if (!empty($win)) $sql0 .= ($win == 1) ? " and vargold >= 0" : " and vargold < 0";
			if (!empty($bankid)) $sql0 .= " and bankid='$bankid'";
			
			$table = "log_brc_bank_statistics_log_".date("Ym");
			$bank_model = M($table, '', DB_CONFIG2);
			
			//获取所有庄家
			$bank = $bank_model->field("banknickname,bankid")->group("banknickname,bankid")->select();
			$this->assign('bank',$bank);
			
			$count = $bank_model->where($sql0)->count('id');
			$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
			$pageshow   = $Page->show();// 分页显示输出
			$banklist = $bank_model->where($sql0)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach($banklist as $key => $val){
				$banklist[$key]['beforgamegold'] = number_format($val['beforgamegold']);
				$banklist[$key]['vargold'] = number_format($val['vargold']);
				$banklist[$key]['aftergamegold'] = number_format($val['aftergamegold']);
				$banklist[$key]['eastxzcount'] = number_format($val['eastxzcount']);
				$banklist[$key]['southxzcount'] = number_format($val['southxzcount']);
				$banklist[$key]['westxzcount'] = number_format($val['westxzcount']);
				$banklist[$key]['nouthxzcount'] = number_format($val['nouthxzcount']);
				$banklist[$key]['rate'] = number_format($val['rate']);
			}

			$this->assign('pageshow',$pageshow);
			$this->assign('list',$banklist);
			
			$sum = array(0,0,0,0,0,0,0,0,0,0);
			$sum[0] = $count;
			$sum[1] = $bank_model->where($sql0." and bankid=0")->count('id');
			$sum[2] = number_format($bank_model->where($sql0." and bankid=0")->sum('vargold'));
			$sum[3] = number_format($bank_model->where($sql0." and bankid=0")->sum('eastxzcount+southxzcount+westxzcount+nouthxzcount'));
			$sum[4] = $bank_model->where($sql0." and bankid=0 and jiangjin>0")->count('id');
			$sum[5] = number_format($bank_model->where($sql0)->sum('rate'));
			$sum[6] = $bank_model->where($sql0." and bankid!=0")->count('id');
			$sum[7] = number_format($bank_model->where($sql0." and bankid!=0")->sum('vargold'));
			$sum[8] = number_format($bank_model->where($sql0." and bankid!=0")->sum('eastxzcount+southxzcount+westxzcount+nouthxzcount'));
			$sum[9] = $bank_model->where($sql0)->sum('jiangjin');
			$this->assign('sum',$sum);
		}
		//print_r($bank);
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		$this->assign('bankall',$bankall);
		$this->assign('win',$win);
		$this->assign('bankid',$bankid);
		
		//print_r($banklist);
		//echo "*******************************************************";
		//exit;
		if ($act == "exceldo1"){
			$xlsName  = "统计百人场庄家数据";
			
			$xlsCell  = array(
				array('data','编号'),
				array('user_add','开局时间'),
				array('user_pay_num','庄家昵称'),
				array('user_pay_money','庄家ID'),
				array('user_pay_lv','开局金额'),
				array('user_num','庄家输赢'),
				array('dau','结算后金额'),
				array('dau_old','庄'),
				array('dau_old1','东'),
				array('dau_old2','南'),
				array('dau_old3','西'),
				array('dau_old4','北'),
				array('user_add_ok','押注人数'),
				array('user_ok_lv','东押注'),
				array('liucun1','南押注'),
				array('liucun2','西押注'),
				array('liucun3','北押注'),
				array('online1','税收'),
				array('online2','奖金奖池'),
				array('paiju','系统奖池')
			);
			//print_r($xlsCell);
			$xlsData = array();
			
			if ($endTime == date("Y-m-d")){
				$banklist = $bank_model->where($sql0)->order("id desc")->select();
			}
			foreach($banklist as $key => $val){
				$banklist[$key]['beforgamegold'] = number_format($val['beforgamegold']);
				$banklist[$key]['vargold'] = number_format($val['vargold']);
				$banklist[$key]['aftergamegold'] = number_format($val['aftergamegold']);
				$banklist[$key]['eastxzcount'] = number_format($val['eastxzcount']);
				$banklist[$key]['southxzcount'] = number_format($val['southxzcount']);
				$banklist[$key]['westxzcount'] = number_format($val['westxzcount']);
				$banklist[$key]['nouthxzcount'] = number_format($val['nouthxzcount']);
				$banklist[$key]['rate'] = number_format($val['rate']);
				
				$cardtyperate = explode(",", $val['cardtyperate']);
				
				$xlsData[$key]['data'] = " ".$val['id']." ";
				$xlsData[$key]['user_add'] = $val['operatedate'];
				$xlsData[$key]['user_pay_num'] = $val['banknickname'];
				$xlsData[$key]['user_pay_money'] = $val['bankid'];
				$xlsData[$key]['user_pay_lv'] = $val['beforgamegold'];
				$xlsData[$key]['user_num'] = $val['vargold'];
				$xlsData[$key]['dau'] = $val['aftergamegold'];
				$xlsData[$key]['dau_old'] = $cardtyperate[0];
				$xlsData[$key]['dau_old1'] = $cardtyperate[1];
				$xlsData[$key]['dau_old2'] = $cardtyperate[2];
				$xlsData[$key]['dau_old3'] = $cardtyperate[3];
				$xlsData[$key]['dau_old4'] = $cardtyperate[4];
				$xlsData[$key]['user_add_ok'] = $val['xzplayercount'];
				$xlsData[$key]['user_ok_lv'] = $val['eastxzcount'];
				$xlsData[$key]['liucun1'] = $val['southxzcount'];
				$xlsData[$key]['liucun2'] = $val['westxzcount'];
				$xlsData[$key]['liucun3'] = $val['nouthxzcount'];
				$xlsData[$key]['online1'] = $val['rate'];
				$xlsData[$key]['online2'] = $val['jiangjin'];
				$xlsData[$key]['paiju'] = $val['fazhi'];
			}

			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/brc_bank";
		$this->display($lib_display);
	}
	
	//百人场散家统计
	public function brc_sanjia(){
		
		$table = "log_brc_sanjia_statistics_log_".date("Ym");
		$sanjia_model = M($table, '', DB_CONFIG2);
		$table = "log_game_record_log_".date("Ym");
		$game_model = M($table, '', DB_CONFIG2);
		
		//获取所有庄家
		$sanjia = $sanjia_model->field("nickname,user_id")->group("nickname,user_id")->select();
		$this->assign('sanjia',$sanjia);
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$sanjiaall = I("sanjiaall");
		$user_id = I("user_id");
		$act = I("act");
		
		//查询不能大于当天
		$todaytime = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		import('ORG.Util.Page');
		
		$sum = array(0,0,0,0,0,0,0,0,0,0);

		
		if ((!empty($beginTime) && !empty($endTime)) && $endTime != date("Y-m-d")){
		
			$sql0 = "";
			if (!empty($sanjiaall)) $sql0 .= " ";
			if (!empty($user_id)) $sql0 .= " and user_id='$user_id'";
			
			$time1 = strtotime($beginTime);
			$time2 = strtotime($endTime);
			
			$sanjialist = array();
			
			for($i=$time2; $i>=$time1; $i-=86400){
				
				$table = "log_brc_sanjia_".date("Ymd", $i);
				$sanjia_model1 = M($table, '', DB_CONFIG3);
				$table = "log_game_".date("Ymd", $i);
				$game_model1 = M($table, '', DB_CONFIG3);
		
				$sanjialist1 = $sanjia_model1->where($sql0)->order("id desc")->select();
				foreach($sanjialist1 as $key => $val){
					$sanjialist[$key]['allxzcount'] = number_format($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
					
					$sanjialist[$key]['eastxzcount'] = number_format($val['eastxzcount']);
					$sanjialist[$key]['southxzcount'] = number_format($val['southxzcount']);
					$sanjialist[$key]['westxzcount'] = number_format($val['westxzcount']);
					$sanjialist[$key]['northxzcount'] = number_format($val['northxzcount']);

					//获取玩家输赢
					$sanjiagold = $game_model1->where("roomid=6 AND gameid=".$val['gameid']." AND user_id=".$val['user_id'])->find();
					$sanjialist[$key]['beforegold'] = number_format($sanjiagold['beforegold']);
					$sanjialist[$key]['aftergold'] = number_format($sanjiagold['aftergold']);
					$sanjialist[$key]['changegold'] = number_format($sanjiagold['changegold']);
					$sanjialist[$key]['taxgold'] = number_format($sanjiagold['taxgold']);
					
					$sanjialist[] = array(
										  'allxzcount' => number_format($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']),
										  'eastxzcount' => number_format($val['eastxzcount']),
										  'southxzcount' => number_format($val['southxzcount']),
										  'westxzcount' => number_format($val['westxzcount']),
										  'northxzcount' => number_format($val['northxzcount']),
										  'beforegold' => number_format($sanjiagold['beforegold']),
										  'aftergold' => number_format($sanjiagold['aftergold']),
										  'changegold' => number_format($sanjiagold['changegold']),
										  'taxgold' => number_format($sanjiagold['taxgold']),
										  'id' => $val['id'],
										  'operatedate' => $val['operatedate'],
										  'user_id' => $val['user_id'],
										  'nickname' => $val['nickname'],
										  'gameid' => $val['gameid'],
										  'eastrate' => $val['eastrate'],
										  'southrate' => $val['southrate'],
										  'westrate' => $val['westrate'],
										  'northrate' => $val['northrate']
										  );
					
					$sum[1] += $sanjiagold['changegold'];
					$sum[2] += ($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
					$sum[3] += $sanjiagold['taxgold'];
				}
				
			}
			
			$sum[1] = number_format($sum[1]);
			$sum[2] = number_format($sum[2]);
			$sum[3] = number_format($sum[3]);
			
			$count = count($sanjialist);
			$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
			$pageshow   = $Page->show();// 分页显示输出
			
			$sanjialist0 = array();
			for($i=$Page->firstRow; $i<$Page->firstRow+$Page->listRows; $i++){
				$sanjialist0[$i] = $sanjialist[$i];
			}

			$this->assign('pageshow',$pageshow);
			$this->assign('list',$sanjialist0);
			
		}else{
			$endTime = date("Y-m-d");
			$day_jian = 1;
			$beginTime = date("Y-m-d");
			
			$sql0 = "(operatedate>='$beginTime 00:00:00' and operatedate<='$endTime 23:59:59')";
			if (!empty($sanjiaall)) $sql0 .= " ";
			if (!empty($user_id)) $sql0 .= " and user_id='$user_id'";
			
			$sum[0] = $sanjia_model->where($sql0." ")->count('distinct gameid');
			
			$count = $sanjia_model->where($sql0)->count('id');
			$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
			$pageshow   = $Page->show();// 分页显示输出
			$sanjialist = $sanjia_model->where($sql0)->order("id desc")->select();
			foreach($sanjialist as $key => $val){
				$sanjialist[$key]['allxzcount'] = number_format($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
				
				$sanjialist[$key]['eastxzcount'] = number_format($val['eastxzcount']);
				$sanjialist[$key]['southxzcount'] = number_format($val['southxzcount']);
				$sanjialist[$key]['westxzcount'] = number_format($val['westxzcount']);
				$sanjialist[$key]['northxzcount'] = number_format($val['northxzcount']);

				//获取玩家输赢
				$sanjiagold = $game_model->where("roomid=6 AND gameid=".$val['gameid']." AND user_id=".$val['user_id'])->find();
				$sanjialist[$key]['beforegold'] = number_format($sanjiagold['beforegold']);
				$sanjialist[$key]['aftergold'] = number_format($sanjiagold['aftergold']);
				$sanjialist[$key]['changegold'] = number_format($sanjiagold['changegold']);
				$sanjialist[$key]['taxgold'] = number_format($sanjiagold['taxgold']);
				
				$sum[1] += $sanjiagold['changegold'];
				$sum[2] += ($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
				$sum[3] += $sanjiagold['taxgold'];
			}
			
			$sum[1] = number_format($sum[1]);
			$sum[2] = number_format($sum[2]);
			$sum[3] = number_format($sum[3]);
			
			$sanjialist0 = array();
			for($i=$Page->firstRow; $i<$Page->firstRow+$Page->listRows; $i++){
				$sanjialist0[$i] = $sanjialist[$i];
			}

			$this->assign('pageshow',$pageshow);
			$this->assign('list',$sanjialist0);
		}
		
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		$this->assign('sanjiaall',$sanjiaall);
		$this->assign('user_id',$user_id);
	
		
		
		
		if ($act == "exceldo1"){
			$xlsName  = "统计百人场散家数据";
			
			$xlsCell  = array(
				array('data','编号'),
				array('user_add','操作时间'),
				array('user_pay_num','昵称'),
				array('user_pay_money','ID'),
				array('user_pay_lv','押注前金币'),
				array('user_num','输赢金币'),
				array('dau','结算后金币'),
				array('dau_old','税收'),
				array('user_add_ok','总押注'),
				array('user_ok_lv','东押注'),
				array('liucun1','东倍率'),
				array('liucun2','南押注'),
				array('liucun3','南倍率'),
				array('online1','西押注'),
				array('online2','西倍率'),
				array('paiju','北押注'),
				array('paiju2','北倍率')
			);
			//print_r($xlsCell);
			$xlsData = array();
			//$sanjialist = $sanjia_model->where($sql0)->order("id desc")->select();
			foreach($sanjialist as $key => $val){
				$sanjialist[$key]['allxzcount'] = number_format($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
				
				$sanjialist[$key]['eastxzcount'] = number_format($val['eastxzcount']);
				$sanjialist[$key]['southxzcount'] = number_format($val['southxzcount']);
				$sanjialist[$key]['westxzcount'] = number_format($val['westxzcount']);
				$sanjialist[$key]['northxzcount'] = number_format($val['northxzcount']);

				//获取玩家输赢
				$sanjiagold = $game_model->where("roomid=6 AND gameid=".$val['gameid']." AND user_id=".$val['user_id'])->find();
				$sanjialist[$key]['beforegold'] = number_format($sanjiagold['beforegold']);
				$sanjialist[$key]['aftergold'] = number_format($sanjiagold['aftergold']);
				$sanjialist[$key]['changegold'] = number_format($sanjiagold['changegold']);
				$sanjialist[$key]['taxgold'] = number_format($sanjiagold['taxgold']);
				
				$sum[1] += $sanjiagold['changegold'];
				$sum[2] += ($val['eastxzcount'] + $val['southxzcount'] + $val['westxzcount'] + $val['northxzcount']);
				$sum[3] += $sanjiagold['taxgold'];
				
				$xlsData[$key]['data'] = " ".$sanjialist[$key]['id']." ";
				$xlsData[$key]['user_add'] = $sanjialist[$key]['operatedate'];
				$xlsData[$key]['user_pay_num'] = $sanjialist[$key]['nickname'];
				$xlsData[$key]['user_pay_money'] = $sanjialist[$key]['user_id'];
				$xlsData[$key]['user_pay_lv'] = $sanjialist[$key]['beforegold'];
				$xlsData[$key]['user_num'] = $sanjialist[$key]['changegold'];
				$xlsData[$key]['dau'] = $sanjialist[$key]['aftergold'];
				$xlsData[$key]['dau_old'] = $sanjialist[$key]['taxgold'];
				$xlsData[$key]['user_add_ok'] = $sanjialist[$key]['allxzcount'];
				$xlsData[$key]['user_ok_lv'] = $sanjialist[$key]['eastxzcount'];
				$xlsData[$key]['liucun1'] = $sanjialist[$key]['eastrate'];
				$xlsData[$key]['liucun2'] = $sanjialist[$key]['southxzcount'];
				$xlsData[$key]['liucun3'] = $sanjialist[$key]['southrate'];
				$xlsData[$key]['online1'] = $sanjialist[$key]['westxzcount'];
				$xlsData[$key]['online2'] = $sanjialist[$key]['westrate'];
				$xlsData[$key]['paiju'] = $sanjialist[$key]['northxzcount'];
				$xlsData[$key]['paiju2'] = $sanjialist[$key]['northrate'];
			}


			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
	
		
		$this->assign('sum',$sum);
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/brc_sanjia";
		$this->display($lib_display);
	}
	
	//七夕牌型统计
	public function fenxi77(){
		
		$fx77_model = M("fx77", '', DB_CONFIG1);
		import('ORG.Util.Page');
		
		$act = I("act");
		$user_id = I("user_id");
		
		if (!empty($_POST) && empty($user_id)){
			
			$date1 = I("date1");
			$cate = I("cate");
			if (empty($date1)){
				$time1 = strtotime("2016-08-09");
			}else{
				$time1 = strtotime($date1);
			}
			$table = "log_game_".date("Ymd", $time1);
			//echo $table;
			$game_model = M($table, '', DB_CONFIG3);
			
			//获取777
			$user_id = "";
			$game777 = $game_model->where("roomid IN (1,2,3,7,8) AND iswin=0 AND cards LIKE '%7%7%7'")->select();
			foreach($game777 as $key => $val){
				//判断是否有记录
				$count = $fx77_model->where("user_id=".$val['user_id'])->count();
				if ($count == 0){
					$data = array();
					$data['user_id'] = $val['user_id'];
					$data['gameid'] = $val['gameid'];
					$data['cards'] = $val['cards'];
					$data['curtime'] = $val['curtime'];
					$data['date'] = $val['date'];
					$data['gold'] = 777777;
					$data['addtime'] = time();
					$result = $fx77_model->add($data);
					if ($result) $user_id .= empty($user_id) ? $val['user_id'] : ",".$val['user_id'];
				}
			}
			
			//获取77
			if (!empty($user_id)) $user_id = " AND user_id NOT IN ($user_id)";
			$game77 = $game_model->where("roomid IN (1,2,3,7,8) AND iswin=0 AND cards LIKE '%7%7%'".$user_id)->select();
			foreach($game77 as $key => $val){
				//判断是否有记录
				$count = $fx77_model->where("user_id=".$val['user_id'])->count();
				if ($count == 0){
					$data = array();
					$data['user_id'] = $val['user_id'];
					$data['gameid'] = $val['gameid'];
					$data['cards'] = $val['cards'];
					$data['curtime'] = $val['curtime'];
					$data['date'] = $val['date'];
					$data['gold'] = 77777;
					$data['addtime'] = time();
					$result = $fx77_model->add($data);
					if ($result) $user_id .= empty($user_id) ? $val['user_id'] : ",".$val['user_id'];
				}
			}
			
			//exit;
			$this->success('统计完成', U($this->By_tpl.'/fenxi77'));
			exit;
			
		}else{
			
			$sql0 = "";
			if (!empty($user_id)) $sql0 .= " and user_id=".$user_id;
			
			$user_model = M("user_info", '', DB_CONFIG2);
			$sum = array(0,0,0,0,0,0);
			$list = $fx77_model->where("status=1".$sql0)->select();
			foreach($list as $key => $val){
				
				$user = $user_model->where("user_id=".$val['user_id'])->find();
				$list[$key]['nickname'] = !empty($user['nickname']) ? $user['nickname'] : $user['nick_name'];
				
				$num1 = substr($val['cards'],0,2);
				$pai1 = '<img src="'.DB_HOST.'/Public/images/'.$num1.'.png" width="30">';
				$num2 = substr($val['cards'],2,2);
				$pai2 = '<img src="'.DB_HOST.'/Public/images/'.$num2.'.png" width="30">';
				$num3 = substr($val['cards'],4,2);
				$pai3 = '<img src="'.DB_HOST.'/Public/images/'.$num3.'.png" width="30">';
				$showcards = $pai1.$pai2.$pai3;
				$list[$key]['showcards'] = $showcards;
				
				$list[$key]['addtime'] = date("Y-m-d H:i:s", $val['addtime']);
				$list[$key]['notice_status'] = ($val['notice_status'] == "1") ? "已发放" : "未发放";
				$list[$key]['notice_time'] = !empty($val['notice_time']) ? date("Y-m-d H:i:s", $val['notice_time']) : "";
				
				$sum[0]++;
				$sum[1]+=$val['gold'];
				if ($val['gold'] == 777777){
					$sum[2]++;
					$sum[3]+=$val['gold'];
				}
				if ($val['gold'] == 77777){
					$sum[4]++;
					$sum[5]+=$val['gold'];
				}
			}
			$sum[1] = number_format($sum[1]);
			$sum[3] = number_format($sum[3]);
			$sum[5] = number_format($sum[5]);
			
			$count = count($list);
			$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
			$pageshow   = $Page->show();// 分页显示输出
			
			$list0 = array();
			for($i=$Page->firstRow; $i<$Page->firstRow+$Page->listRows; $i++){
				if (!empty($list[$i]['id']))	$list0[$i] = $list[$i];
			}

			$this->assign('pageshow',$pageshow);
			$this->assign('list',$list0);
			$this->assign('sum',$sum);
			
		}
		
		if ($act == "exceldo1"){
			$xlsName  = "统计七夕数据";
			
			$xlsCell  = array(
				array('data','编号'),
				array('user_add','用户id'),
				array('nickname','昵称'),
				array('user_pay_num','局数id'),
				array('user_pay_money','牌型'),
				array('user_pay_lv','牌局时间'),
				array('user_num','获奖金币'),
				array('dau','添加时间'),
				array('dau_old','通知状态'),
				array('user_add_ok','通知时间'),

			);
			//print_r($xlsCell);
			$xlsData = array();
			//$sanjialist = $sanjia_model->where($sql0)->order("id desc")->select();
			foreach($list as $key => $val){
				
				$xlsData[$key]['data'] = " ".$val['id']." ";
				$xlsData[$key]['user_add'] = $val['user_id'];
				$xlsData[$key]['nickname'] = $val['nickname'];
				$xlsData[$key]['user_pay_num'] = $val['gameid'];
				$xlsData[$key]['user_pay_money'] = $val['showcards'];
				$xlsData[$key]['user_pay_lv'] = $val['date'];
				$xlsData[$key]['user_num'] = $val['gold'];
				$xlsData[$key]['dau'] = $val['addtime'];
				$xlsData[$key]['dau_old'] = $val['notice_status'];
				$xlsData[$key]['user_add_ok'] = $val['notice_time'];
			}


			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
		
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/fx77";
		$this->display($lib_display);
		
	}
	
	public function fenxi77_del(){
		if(empty($_GET)){ 
			//增加操作记录
			$logs = C('MANAGE_MSG_DEL_FALSE');
			adminlog($logs);
			
			$this->error('非法操作');
			exit;
		}else{
			$id = I("id");

			$fx77_model = M("fx77", '', DB_CONFIG1);
			$jinbi = array();
			$jinbi['status'] = 0;
			$result = $fx77_model->where("id=".$id)->save($jinbi);

			if($result){
				//增加操作记录
				$logs = C('MANAGE_MSG_DEL_SUCCESS');
				$remark = "(删除用户:".$userid.")";
				adminlog($logs,$remark);
				
				$this->success('删除成功');
			}else{
				//增加操作记录
				$logs = C('MANAGE_MSG_DEL_FALSE');
				adminlog($logs);
				
				$this->error('删除失败');
				exit;
			}
		}
	}
	
	public function send77(){
		
		$fx77_model = M("fx77", '', DB_CONFIG1);
		$list = $fx77_model->where("notice_status=0")->limit(1)->select();
		
		foreach($list as $key => $val){
			//调用金币接口
			$id = $key + 1;
			if (intval($val['user_id']) > 0 && intval($val['gold']) > 0){
				$url = DB_HOST."/Pay/send77.php?user_id=".$val['user_id']."&gold=".$val['gold'];
				//echo "ID:".$id."**通知UID:".$val['user_id']."**金币:".$val['gold']."**通知地址：".$url.""; 
				$jinbi_result = curlGET($url);
				//echo $jinbi_result; //exit; 
				$len = strlen($jinbi_result)-3;
				$notify_status = substr($jinbi_result,$len,1);
				//echo $notify_status; exit; 
				//修改通知状态  notify_status=1,notify_times=notify_times+1,notify_date=".time()."
				if ($notify_status == "1"){
					$jinbi = array();
					$jinbi['notice_status'] = 1;
					$jinbi['notice_time'] = time();
					$result1 = $fx77_model->where("id=".$val['id'])->save($jinbi);
					//dump($fx77_model->_sql());
					echo "1";
					exit;
				}
			}
		}
		echo "-1";
		exit;
	}
	
	//七夕牌型统计
	public function fxcard(){
		
		$date1 = I("date1");
		$page1 = I("page1");
		$date1 = (!empty($date1)) ? $date1 : date("Y-m-d");
		$page1 = (!empty($page1)) ? $page1 : 1;
		$time1 = strtotime($date1);
		
		$types = array();
		$types[0]['name'] = '豹子';
		$types[0]['num'] = 0;
		$types[1]['name'] = '顺金';
		$types[1]['num'] = 0;
		$types[2]['name'] = '金花';
		$types[2]['num'] = 0;
		$types[3]['name'] = '顺子';
		$types[3]['num'] = 0;
		$types[4]['name'] = '对子';
		$types[4]['num'] = 0;
		$types[5]['name'] = '单牌';
		$types[5]['num'] = 0;
		//print_r($types);

		$table = "log_game_".date("Ymd", $time1);
		$game_model = M($table, '', DB_CONFIG3);
		$num1 = ($page1 - 1) * 50000;
		$row = $game_model->limit($num1, 50000)->select();
		dump($game_model->_sql());
		//print_r($row);
		foreach($row as $key => $val){
			//echo $val['cards']."||||";
				$num11 = substr($val['cards'],1,1);
				if ($num11=="a") $num11 = "10"; elseif ($num11=="b") $num11 = "11"; elseif ($num11=="c") $num11 = "12";  elseif ($num11=="d") $num11 = "13";  
				$num12 = substr($val['cards'],3,1);
				if ($num12=="a") $num12 = "10"; elseif ($num12=="b") $num12 = "11"; elseif ($num12=="c") $num12 = "12";  elseif ($num12=="d") $num12 = "13";  
				$num13 = substr($val['cards'],5,1);
				if ($num13=="a") $num13 = "10"; elseif ($num13=="b") $num13 = "11"; elseif ($num13=="c") $num13 = "12";  elseif ($num13=="d") $num13 = "13";  
				$suits = array(substr($val['cards'],0,1), substr($val['cards'],2,1),substr($val['cards'],4,1));
				$nums = array($num11, $num12, $num13);
				sort($nums);
				if ($nums[0]==$nums[1] && $nums[0]==$nums[2]){

					$types[0]['num']++;

				}else if ((($nums[0]+1==$nums[1] && $nums[1]+1==$nums[2]) || ($nums[0]=="1" && $nums[1]=="12" && $nums[2]=="13")) && ($suits[0]==$suits[1] && $suits[0]==$suits[2])){

					$types[1]['num']++;

				}else if ($suits[0]==$suits[1] && $suits[0]==$suits[2]){

					$types[2]['num']++;

				}else if (($nums[0]+1==$nums[1] && $nums[1]+1==$nums[2]) || ($nums[0]=="1" && $nums[1]=="12" && $nums[2]=="13")){

					$types[3]['num']++;

				}else if ($nums[0]==$nums[1] || $nums[1]==$nums[2]){

					$types[4]['num']++;
	
				}else{

					$types[5]['num']++;

				}  
		}
		echo "<br>******!!!!!!!!!!!!!!!!!!!!!!!!!!**********<br>";
		print_r($types);
	}
	
	//高频彩统计
	public function ssc_hua(){
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$qishu = I("qishu");
		$fapaimode = I("fapaimode");
		if ($fapaimode=="") $fapaimode = "-1";

		$act = I("act");
		//echo $act."***********************"; 
		//查询不能大于当天
		$todaytime = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		import('ORG.Util.Page');
		
		if (empty($beginTime) || empty($endTime)){
			$endTime = date("Y-m-d");
			$beginTime = date("Y-m-d");
		}	
			
		$sql0 = "(operatedate>='$beginTime 00:00:00' and operatedate<='$endTime 23:59:59')";
		if ($fapaimode != -1) $sql0 .=  " and fapaimode=$fapaimode";
		if (!empty($qishu)) $sql0 .= " and qishu like '%$qishu%'";
			
		$table = "shishicai_huase_statis_log";
		$huase_model = M($table, '', DB_CONFIG2);
			
		$count = $huase_model->where($sql0)->count('qishu');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$pageshow   = $Page->show();// 分页显示输出
		$huaselist = $huase_model->where($sql0)->order("qishu desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($huaselist as $key => $val){
			$huaselist[$key]['card'] = '<img src="'.DB_HOST.'/Public/images/'.$val['opencard'].'.png" width="30">';
			$win_huase = substr($val['opencard'], 0, 1);
			switch ($win_huase){
				case "0": $win_jinbi = $val['fangkuaibet']; $huaselist[$key]['fangkuaibet'] = '<font color="#ff0000">'.$val['fangkuaibet'].'</font>'; break;
				case "1": $win_jinbi = $val['meihuabet']; $huaselist[$key]['meihuabet'] = '<font color="#ff0000">'.$val['meihuabet'].'</font>'; break;
				case "2": $win_jinbi = $val['hongtaobet']; $huaselist[$key]['hongtaobet'] = '<font color="#ff0000">'.$val['hongtaobet'].'</font>'; break;
				case "3": $win_jinbi = $val['heitaobet']; $huaselist[$key]['heitaobet'] = '<font color="#ff0000">'.$val['heitaobet'].'</font>'; break;
				default : $win_jinbi = 0; break;
			}
			$huaselist[$key]['xishu'] = ($win_jinbi == 0) ? 0 : (round($val['givegold']/$win_jinbi, 2) + 1);
		}

		$this->assign('pageshow',$pageshow);
		$this->assign('list',$huaselist);
			
		$sum = array(0,0,0,0,0,0,0,0,0,0);
		$sum[0] = $count;
		$sum[1] = number_format($huase_model->where($sql0)->sum('totalbet'));
		$sum[2] = $huase_model->where($sql0)->sum('betnum');
		$sum[3] = number_format($huase_model->where($sql0)->sum('rate'));
		$sum[4] = number_format($huase_model->where($sql0)->sum('heitaobet'));
		$sum[5] = number_format($huase_model->where($sql0)->sum('hongtaobet'));
		$sum[6] = number_format($huase_model->where($sql0)->sum('meihuabet'));
		$sum[7] = number_format($huase_model->where($sql0)->sum('fangkuaibet'));
		$this->assign('sum',$sum);
		
		//print_r($bank);
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		$this->assign('qishu',$qishu);
		$this->assign('fapaimode',$fapaimode);
		
		//print_r($banklist);
		//echo "*******************************************************";
		//exit;
		if ($act == "exceldo1"){
			$xlsName  = "高频彩数据统计";
			
			$xlsCell  = array(
				array('show01','期数'),
				array('show02','押注人数'),
				array('show03','总押注'),
				array('show04','黑桃'),
				array('show05','红桃'),
				array('show06','梅花'),
				array('show07','方块'),
				array('show08','奖池金额'),
				array('show09','开奖牌型'),
				array('show10','发放金额'),
				array('show11','盈利系数'),
				array('show12','税收')
			);
			//print_r($xlsCell);
			$xlsData = array();
			
			$huaselist = $huase_model->where($sql0)->order("qishu desc")->select();
			foreach($huaselist as $key => $val){
				$win_huase = substr($val['opencard'], 0, 1);
				switch ($win_huase){
					case "0": $win_jinbi = $val['fangkuaibet']; break;
					case "1": $win_jinbi = $val['meihuabet'];  break;
					case "2": $win_jinbi = $val['hongtaobet']; break;
					case "3": $win_jinbi = $val['heitaobet']; break;
					default : $win_jinbi = 0; break;
				}
				$huaselist[$key]['xishu'] = ($win_jinbi == 0) ? 0 : (round($val['givegold']/$win_jinbi, 2) + 1);
				
				
				$xlsData[$key]['show01'] = " ".$val['qishu']." ";
				$xlsData[$key]['show02'] = $val['betnum'];
				$xlsData[$key]['show03'] = $val['totalbet'];
				$xlsData[$key]['show04'] = $val['heitaobet'];
				$xlsData[$key]['show05'] = $val['hongtaobet'];
				$xlsData[$key]['show06'] = $val['meihuabet'];
				$xlsData[$key]['show07'] = $val['fangkuaibet'];
				$xlsData[$key]['show08'] = $val['jiangchi'];
				$xlsData[$key]['show09'] = " ".$val['opencard']." ";
				$xlsData[$key]['show10'] = $val['givegold'];
				$xlsData[$key]['show11'] = $val['xishu'];
				$xlsData[$key]['show12'] = $val['rate'];
			}

			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/ssc_hua";
		$this->display($lib_display);
	}
	
	public function ssc_huamore(){
		
		$qishu = I("qishu");
		$uid = I("uid");
		$act = I("act");
		import('ORG.Util.Page');
		
		$sql0 = "1";
		if (!empty($qishu)) $sql0 .= " and qishu = '$qishu'";
		if (!empty($uid)) $sql0 .= " and uid = '$uid'";
			
		$table = "shishicai_user_detail_log_".date("Ym", $qishu);
		$huase_model = M($table, '', DB_CONFIG2);
			
		$count = $huase_model->where($sql0)->count('id');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$pageshow   = $Page->show();// 分页显示输出
		$huaselist = $huase_model->where($sql0)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($huaselist as $key => $val){
			$huaselist[$key]['card'] = '<img src="'.DB_HOST.'/Public/images/'.$val['opencard'].'.png" width="30">';
			$win_huase = substr($val['opencard'], 0, 1);
			switch ($win_huase){
				case "0": $huaselist[$key]['fangkuai_bet'] = '<font color="#FF0000">'.$val['fangkuai_bet'].'</font>'; break;
				case "1": $huaselist[$key]['meihua_bet'] = '<font color="#FF0000">'.$val['meihua_bet'].'</font>'; break;
				case "2": $huaselist[$key]['hongtao_bet'] = '<font color="#FF0000">'.$val['hongtao_bet'].'</font>'; break;
				case "3": $huaselist[$key]['heitao_bet'] = '<font color="#FF0000">'.$val['heitao_bet'].'</font>'; break;
				default : break;
			}
			$huaselist[$key]['totalbet'] = $val['fangkuai_bet'] + $val['meihua_bet'] + $val['hongtao_bet'] + $val['heitao_bet'];
		}

		$this->assign('pageshow',$pageshow);
		$this->assign('list',$huaselist);
		
		if ($act == "exceldo1"){
			$xlsName  = "高频彩数据统计";
			
			$xlsCell  = array(
				array('show01','用户ID'),
				array('show02','黑桃'),
				array('show03','红桃'),
				array('show04','梅花'),
				array('show05','方块'),
				array('show06','牌型'),
				array('show07','押注总额'),
				array('show08','赢钱'),
				array('show09','税收')
			);
			//print_r($xlsCell);
			$xlsData = array();
			
			$huaselist = $huase_model->where($sql0)->order("id desc")->select();
			foreach($huaselist as $key => $val){
				$totalbet = $val['fangkuai_bet'] + $val['meihua_bet'] + $val['hongtao_bet'] + $val['heitao_bet'];
				
				
				$xlsData[$key]['show01'] = " ".$val['uid']." ";
				$xlsData[$key]['show02'] = $val['heitao_bet'];
				$xlsData[$key]['show03'] = $val['hongtao_bet'];
				$xlsData[$key]['show04'] = $val['meihua_bet'];
				$xlsData[$key]['show05'] = $val['fangkuai_bet'];
				$xlsData[$key]['show06'] = " ".$val['opencard']." ";
				$xlsData[$key]['show07'] = $totalbet;
				$xlsData[$key]['show08'] = $val['huase_reward'];
				$xlsData[$key]['show09'] = $val['huase_rate'];
			}

			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
			
		//print_r($bank);
		$this->assign('qishu',$qishu);
		$this->assign('uid',$uid);
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/ssc_huamore";
		$this->display($lib_display);
	}
	
	public function ssc_da(){
		
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		$qishu = I("qishu");
		$fapaimode = I("fapaimode");
		if ($fapaimode=="") $fapaimode = "-1";

		$act = I("act");
		//echo $act."***********************"; 
		//查询不能大于当天
		$todaytime = strtotime(date("Y-m-d"));
		if (strtotime($beginTime)>strtotime($endTime)){
			$this->error('开始日期不能大于结束日期');
			exit;
		}
		
		import('ORG.Util.Page');
		
		if (empty($beginTime) || empty($endTime)){
			$endTime = date("Y-m-d");
			$beginTime = date("Y-m-d");
		}	
			
		$sql0 = "(operatedate>='$beginTime 00:00:00' and operatedate<='$endTime 23:59:59')";
		if ($fapaimode != -1) $sql0 .=  " and fapaimode=$fapaimode";
		if (!empty($qishu)) $sql0 .= " and qishu like '%$qishu%'";
			
		$table = "shishicai_daxiao_statis_log";
		$huase_model = M($table, '', DB_CONFIG2);
			
		$count = $huase_model->where($sql0)->count('qishu');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$pageshow   = $Page->show();// 分页显示输出
		$huaselist = $huase_model->where($sql0)->order("qishu desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($huaselist as $key => $val){
			$huaselist[$key]['card'] = '<img src="'.DB_HOST.'/Public/images/'.$val['opencard'].'.png" width="30">';
			$win_num = substr($val['opencard'], 1, 1);
			if ($win_num > 7 || ($win_num == "a" || $win_num == "b" || $win_num == "c" || $win_num == "d")){
				$huaselist[$key]['dabet'] = '<font color="#ff0000">'.$val['dabet'].'</font>';
			}else if ($win_num == 7){
				$huaselist[$key]['qibet'] = '<font color="#ff0000">'.$val['qibet'].'</font>';
			}else{
				$huaselist[$key]['xiaobet'] = '<font color="#ff0000">'.$val['xiaobet'].'</font>';
			}
		}

		$this->assign('pageshow',$pageshow);
		$this->assign('list',$huaselist);
			
		$sum = array(0,0,0,0,0,0,0,0,0,0);
		$sum[0] = $count;
		$sum[1] = number_format($huase_model->where($sql0)->sum('totalbet'));
		$sum[2] = $huase_model->where($sql0)->sum('betnum');
		$sum[3] = number_format($huase_model->where($sql0)->sum('rate'));
		$sum[4] = number_format($huase_model->where($sql0)->sum('xiaobet'));
		$sum[5] = number_format($huase_model->where($sql0)->sum('qibet'));
		$sum[6] = number_format($huase_model->where($sql0)->sum('dabet'));
		$this->assign('sum',$sum);
		
		//print_r($bank);
		$this->assign('beginTime',$beginTime);
		$this->assign('endTime',$endTime);
		$this->assign('qishu',$qishu);
		$this->assign('fapaimode',$fapaimode);
		
		//print_r($banklist);
		//echo "*******************************************************";
		//exit;
		if ($act == "exceldo1"){
			$xlsName  = "高频彩数据统计";
			
			$xlsCell  = array(
				array('show01','期数'),
				array('show02','押注人数'),
				array('show03','总押注'),
				array('show04','小'),
				array('show05','7'),
				array('show06','大'),
				array('show07','奖池金额'),
				array('show08','开奖牌型'),
				array('show09','发放金额'),
				array('show10','税收')
			);
			//print_r($xlsCell);
			$xlsData = array();
			
			$huaselist = $huase_model->where($sql0)->order("qishu desc")->select();
			foreach($huaselist as $key => $val){
				
				$xlsData[$key]['show01'] = " ".$val['qishu']." ";
				$xlsData[$key]['show02'] = $val['betnum'];
				$xlsData[$key]['show03'] = $val['totalbet'];
				$xlsData[$key]['show04'] = $val['xiaobet'];
				$xlsData[$key]['show05'] = $val['qibet'];
				$xlsData[$key]['show06'] = $val['dabet'];
				$xlsData[$key]['show07'] = $val['jiangchi'];
				$xlsData[$key]['show08'] = " ".$val['opencard']." ";
				$xlsData[$key]['show09'] = $val['givegold'];
				$xlsData[$key]['show10'] = $val['rate'];
			}

			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/ssc_da";
		$this->display($lib_display);
	}
	
	public function ssc_damore(){
		
		$qishu = I("qishu");
		$uid = I("uid");
		$act = I("act");
		import('ORG.Util.Page');
		
		$sql0 = "1";
		if (!empty($qishu)) $sql0 .= " and qishu = '$qishu'";
		if (!empty($uid)) $sql0 .= " and uid = '$uid'";
			
		$table = "shishicai_user_detail_log_".date("Ym", $qishu);
		$huase_model = M($table, '', DB_CONFIG2);
			
		$count = $huase_model->where($sql0)->count('id');
		$Page       = new Page($count,20);//实例化分页类传入总记录数和每页显示的记录数		
		$pageshow   = $Page->show();// 分页显示输出
		$huaselist = $huase_model->where($sql0)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($huaselist as $key => $val){
			$huaselist[$key]['card'] = '<img src="'.DB_HOST.'/Public/images/'.$val['opencard'].'.png" width="30">';
			$win_num = substr($val['opencard'], 1, 1);
			if ($win_num > 7 || ($win_num == "a" || $win_num == "b" || $win_num == "c" || $win_num == "d")){
				$huaselist[$key]['da_bet'] = '<font color="#ff0000">'.$val['da_bet'].'</font>';
			}else if ($win_num == 7){
				$huaselist[$key]['qi_bet'] = '<font color="#ff0000">'.$val['qi_bet'].'</font>';
			}else{
				$huaselist[$key]['xiao_bet'] = '<font color="#ff0000">'.$val['xiao_bet'].'</font>';
			}
			$huaselist[$key]['totalbet'] = $val['da_bet'] + $val['qi_bet'] + $val['xiao_bet'];
		}

		$this->assign('pageshow',$pageshow);
		$this->assign('list',$huaselist);
		
		if ($act == "exceldo1"){
			$xlsName  = "高频彩数据统计";
			
			$xlsCell  = array(
				array('show01','用户ID'),
				array('show02','小'),
				array('show03','7'),
				array('show04','大'),
				array('show05','牌型'),
				array('show06','押注总额'),
				array('show07','赢钱'),
				array('show08','税收')
			);
			//print_r($xlsCell);
			$xlsData = array();
			
			$huaselist = $huase_model->where($sql0)->order("id desc")->select();
			foreach($huaselist as $key => $val){
				$totalbet = $val['xiao_bet'] + $val['qi_bet'] + $val['da_bet'];
				
				
				$xlsData[$key]['show01'] = " ".$val['uid']." ";
				$xlsData[$key]['show02'] = $val['xiao_bet'];
				$xlsData[$key]['show03'] = $val['qi_bet'];
				$xlsData[$key]['show04'] = $val['da_bet'];
				$xlsData[$key]['show05'] = " ".$val['opencard']." ";
				$xlsData[$key]['show06'] = $totalbet;
				$xlsData[$key]['show07'] = $val['daxiao_reward'];
				$xlsData[$key]['show08'] = $val['daxiao_rate'];
			}

			//print_r($xlsData);
			//exit;
			exportExcel($xlsName,$xlsCell,$xlsData);
			exit;
		}
			
		//print_r($bank);
		$this->assign('qishu',$qishu);
		$this->assign('uid',$uid);
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/ssc_damore";
		$this->display($lib_display);
	}
	
	public function kuang(){
		
		$title = I("title");
		$contents = I("contents");
		$beginTime = I("beginTime");
		$endTime = I("endTime");
		import('ORG.Util.Page');
		
		if (!empty($_POST)){
			$kuang = array();
			$kuang['title'] = $title;
			$kuang['contents'] = $contents;
			$kuang['beginTime'] = $beginTime;
			$kuang['endTime'] = $endTime;
			S('kuang',$kuang);
			$this->success('配置成功',U('Triger/kuang'));
			exit;
		}else{
			$kuang = S("kuang");
		}
			
		//print_r($bank);
		$this->assign('kuang',$kuang);
		
		$this->assign('left_css',"66");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/kuang";
		$this->display($lib_display);
	}
	
	public function test(){
		
		$this->assign('left_css',"38");
		$this->assign('By_tpl',$this->By_tpl);
		$lib_display = $this->By_tpl."/test";
		$this->display($lib_display);
	}
	
	//判断目录是否为空
	public function is_empty_dir($fp)    
    {    
        $H = @opendir($fp); 
        $i=0;    
        while($_file=readdir($H)){    
            $i++;    
        }    
        closedir($H);    
        if($i>2){ 
            return 1; 
        }else{ 
            return 2;  //true
        } 
    } 
	
	//空方法
	 public function _empty() {  
        $this->display("Index/index");
    }
}