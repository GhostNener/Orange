<?php

namespace Home\Model;

use Think\Model;

/**
 * 消息模块
 *
 * @author NENER
 *        
 */
class noticeModel extends Model {
	
	/**
	 * 自动完成
	 *
	 * @var unknown
	 * @author NENER
	 */
	protected $_auto = array (
			array (
					'CreateTime',
					NOW_TIME,
					self::MODEL_INSERT 
			),
			array (
					'Theme',
					'gettheme',
					self::MODEL_INSERT,
					'callback' 
			),
			array (
					'Status',
					10,
					self::MODEL_INSERT 
			) 
	);
	
	/**
	 * 获得消息主题
	 *
	 * @param number $type
	 *        	1：系统，2：订单，3：留言，4：回复
	 * @return string NULL
	 */
	protected function gettheme($type) {
		/**
		 * 消息类型组
		 * MSG,SYS,ORDER,REPLY
		 */
		$msggrop = C ( 'MSG_TYPE_GROUP' );
		switch ($type) {
			case 1 :
				return htmlspecialchars ( $msggrop ['SYS'] );
			case 2 :
				return htmlspecialchars ( $msggrop ['ORDER'] );
			case 3 :
				return htmlspecialchars ( $msggrop ['MSG'] );
			case 4 :
				return htmlspecialchars ( $msggrop ['REPLY'] );
			default :
				return htmlspecialchars ( $msggrop ['SYS'] );
		}
	}
	/**
	 * 获得未读消息
	 * @param string $uid
	 * @param number $type 1：获得 列表，2：获得数量
	 * @return array or number  */
	public function getunread($uid=null,$type=1){
		if(!$uid){
			$uid=cookie('_uid');
		}
		$warr=array('RecipientId'=>(int)$uid,'Status'=>10);
		if($type==2){
			return ($this->where($warr)->count());
		}else{
			return ($this->where($warr)->select());
		}
	}
	
	/**
	 * 添加一个消息
	 *
	 * @param array $data
	 *        	：Title，Content，SendId，RecipientId
	 * @param number $type
	 *        	1：系统，2：订单，3：留言，4：回复
	 */
	public function addone($data, $type = 1) {
		$data ['Theme'] = $type;
		$data ['Type'] = $type;
		
		$msg = $this->create ( $data );
		if (! $msg) {
			return json_encode ( $data );
		}
		return $this->add ( $msg );
	}
}