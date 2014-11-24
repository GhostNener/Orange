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
	 *
	 * @param string $uid        	
	 * @param number $type
	 *        	1：获得 列表，2：获得数量
	 * @return array or number
	 */
	public function getunread($uid = null, $type = 1, $limit = 20,$baseurl=ACTION_NAME,$defaultpar = true,$param=null) {
		if (! $uid) {
			$uid = cookie ( '_uid' );
		}
		$warr = array (
				'RecipientId' => ( int ) $uid,
				'Status' => 10 
		);
		if ($type == 2) {
			return ($this->where ( $warr )->count ());
		} else {
			$allCount = $this->where ( $warr )->count ();
		$Page = new \Think\Page ( $allCount, $limit, $param, $defaultpar );
		$showPage = $Page->show ( $baseurl );
			$list = $this->where ( $warr )->limit ( $Page->firstRow . ',' . $Page->listRows )->order ( 'CreateTime DESC ' )->select ();
			return array (
					'page' => $showPage,
					'list' => $list 
			);
		}
	}
	
	/**
	 * 创建系统通知
	 *
	 * @param array $arr
	 *        	收件人
	 * @param string $title
	 *        	标题
	 * @param string $content
	 *        	内容
	 */
	public function CSYSN($arr, $title, $content) {
		$data ['Title'] = $title;
		$data ['Content'] = $content;
		$data ['SendId'] = 0;
		if (! is_array ( $arr )) {
			$data ['RecipientId'] = ( int ) $arr;
			$this->addone ( $data, 1 );
			return;
		}
		foreach ( $arr as $k => $v ) {
			$data ['RecipientId'] = ( int ) $v;
			$this->addone ( $data, 1 );
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
	
	/**
	 * 删除通知
	 * 
	 * @param unknown $Id        	
	 * @return Ambigous <boolean, unknown>
	 */
	public function delone($Id) {
		
		return $this->delete($Id);
/* 		return $this->where ( array (
				'Id' => $Id 
		) )->save ( array (
				'Status' => - 1 
		) ); */
	}
}