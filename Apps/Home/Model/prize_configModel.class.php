<?php

namespace Home\Model;

use Think\Model;

class prize_configModel extends Model {
	/**
	 * 抽奖
	 *
	 * @param string $uid        	
	 * @return
	 *
	 */
	public function prize($uid) {
		$prize_arr = $this->getprize ();
		return $this->getResult ( $prize_arr, $uid );
	}
	public function saveone($data) {
		$arr=array (
					'PraiseFeild'=>$data['PraiseFeild'],
					'PraiseName'=>$data['PraiseName'],
					'MinDeg'=>$data['MinDeg'],
					'MaxDeg'=>$data['MaxDeg'],
					'PraiseNumber'=>$data['PraiseNumber'],
					'PraiseContent'=>$data['PraiseContent'],
					'PraiseCount'=>$data['PraiseCount'],
					'Chance' =>$data['Chance'],
					'Status'=>10
			);
		if ($data ['modif'] == 'add') {
			return $this->add ($arr);
		}else{
			return $this->where(array('Id'=>$data['Id']))->save($arr);
		}
	}
	
	/**
	 * 获得抽奖配置
	 *
	 * @param array $wa
	 *        	获取条件
	 * @return multitype:multitype:
	 */
	public function getprize($wa = array('Status'=>10), $type = 1, $isgetlist = false) {
		$prize_arr = array ();
		$arr = $this->where ( $wa )->order ( 'PraiseFeild' )->select ();
		if ($isgetlist) {
			return $arr;
		}
		foreach ( $arr as $key => $val ) {
			$min = explode ( ",", $val ['MinDeg'] );
			$max = explode ( ",", $val ['MaxDeg'] );
			if (count ( $min ) > 1) {
				$val ['MinDeg'] = $min;
			}
			if (count ( $max ) > 1) {
				$val ['MaxDeg'] = $max;
			}
			$prize_arr [$key] = $val;
			if ($type == 2) {
				return $val;
			}
		}
		return $prize_arr;
	}
	
	/**
	 * 抽奖结果
	 *
	 * @param array $priearr
	 *        	抽奖配置
	 * @param unknown $uid        	
	 * @return multitype:number string |string
	 */
	private function getResult($priearr, $uid) {
		$arr = array ();
		$count = array ();
		if (! checkprize ( $uid )) {
			return array (
					'status' => 0,
					'msg' => '你已经抽过奖了' 
			);
		}
		foreach ( $priearr as $key => $val ) {
			$arr [$val ['Id']] = $val ['Chance'];
			$count [$val ['Id']] = $val ['PraiseCount'];
		}
		$rid = $this->getRand ( $arr, $count ); // 根据概率获取奖项id
		$res = $this->getprize ( array (
				'Id' => $rid 
		), 2 ); // 中奖项
		$m = new prize_recordModel ();
		$code = uniqid ( true );
		$r = $m->addone ( $uid, $res ['Id'], $code );
		if ($r ['status'] == 0) {
			return array (
					'status' => 0,
					'msg' => $r ['msg'] 
			);
		}
		$min = $res ['MinDeg'];
		$max = $res ['MaxDeg'];
		if (is_array ( $min )) { // 多等奖的时候
			$i = mt_rand ( 0, count ( $min ) - 1 );
			$result ['deg'] = mt_rand ( $min [$i], $max [$i] );
		} else {
			$result ['deg'] = mt_rand ( $min, $max ); // 随机生成一个角度
		}
		$result ['name'] = $res ['PraiseName'];
		$result ['content'] = $res ['PraiseContent'];
		$result ['type'] = $res ['PraiseFeild'];
		CSYSN ( $uid, '你中奖了', '你在抽奖活动中中了：' . $res ['PraiseName'] . '。奖品：' . $res ['PraiseContent'] . '。请及时兑奖。<br>兑奖地址：贵州财经大学创业园-指尖科技(笃行楼B栋101-F)' );
		return array (
				'status' => 1,
				'msg' => $r ['msg'],
				'res' => $result 
		);
	}
	/**
	 * 根据概率执行抽奖
	 *
	 * @param array $proArr
	 *        	概率
	 * @param array $proCount
	 *        	库存
	 * @return int Id
	 */
	private function getRand($proArr, $proCount) {
		$result = '';
		$proSum = 0;
		// 概率数组的总概率精度 获取库存不为0的
		foreach ( $proCount as $key => $val ) {
			if ($val <= 0) {
				continue;
			} else {
				$proSum = $proSum + $proArr [$key];
			}
		}
		// 概率数组循环 �
		foreach ( $proArr as $key => $proCur ) {
			if ($proCount [$key] <= 0) {
				continue;
			} else {
				$randNum = mt_rand ( 1, $proSum ); // 关键
				if ($randNum <= $proCur) {
					$result = $key;
					break;
				} else {
					$proSum -= $proCur;
				}
			}
		}
		unset ( $proArr );
		return $result;
	}
}