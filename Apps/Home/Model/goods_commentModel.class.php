<?php

namespace Home\Model;

use Think\Model;
use Usercenter\Model\userModel;

/**
 * 商品评论及回复模型
 *
 * @author DongZ
 *        
 */
class goods_commentModel extends Model {
	
	/**
	 * 保存商品评论及回复
	 *
	 * @param array $postarr:
	 *        	post数组 :GoodsId,Content,AssesseeId,ReplyId
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 * @author NENER
	 */
	public function addComment($postarr, $uid = null) {
		if (empty ( $postarr )) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
			);
		}
		if (! $uid) {
			$uid = ( int ) cookie ( '_uid' );
		}
		// 需要提交的商品评论参数
		$data = array (
				'GoodsId' => ( int ) $postarr ['GoodsId'],
				'Content' => $postarr ['Content'],
				'CreateTime' => time (),
				'UserId' => $uid,
				'AssesseeId' => ( int ) $postarr ['AssesseeId'],
				'ReplyId' => ( int ) $postarr ['ReplyId'],
				'Status' => 10 
		);
		$m = new goodsModel ();
		$g = $m->findone ( $data ['GoodsId'] );
		if (! $g || ( int ) $g ['Status'] < 10) {
			return array (
					'status' => 0,
					'msg' => '商品已下架' 
			);
		}
		$dal = M ();
		$dal->startTrans ();
		// 保存商品评论
		$rst = $this->add ( $data );
		if ($rst) {
			$dal->commit ();
			$today= $this->where ( array (
					'UserId' => $uid,
					'Status' => 10,
					'CreateTime' => array (
							'egt',
							strtotime ( date ( 'Y-m-d' ) ) 
					) 
			) )->count ();
			$EXP=(int)C('COMMENT_EXP_FOR_DAY');
			if($today<=$EXP){
				handleEXP($uid);
			}
			$m = new goodsModel ();
			$m->VCChhandle ( $data ['GoodsId'], 3 );
			$data ['Id'] = $rst;
			$this->createnotice ( $data );
			return array (
					'status' => 1,
					'msg' => '操作成功' 
			);
		} else {
			$dal->rollback ();
			return array (
					'status' => 0,
					'msg' => '操作失败' 
			);
		}
	}
	/**
	 * 创建通知留言或回复
	 *
	 * @param array $arr        	
	 */
	public function createnotice($arr) {
		$tpl = C ( 'MSG_TYPE_CONTENT_PATH' );
		$rid = ( int ) $arr ['ReplyId'];
		$tarr = C ( 'MSG_TYPE_TITLE_GROUP' );
		$data ['SendId'] = $arr ['UserId']; /* 发件人 */
		$m = new view_goods_listModel ();
		$g = $m->getgoodsdetails ( ( int ) $arr ['GoodsId'], 2 );
		$m = new userModel ();
		$u = $m->finduser ( ( int ) $arr ['UserId'], 2 );
		/* 消息体 */
		$cdata ['Title'] = $g ['Title']; // Title
		$cdata ['GURL'] = U ( '/g/'. $arr ['GoodsId'] ); // GURL
		$cdata ['UURL'] = U ( '/user/'. $u ['Nick']); // UserURL
		$cdata ['Nick'] = $u ['Nick']; // Nick
		$cdata ['CId'] = $arr ['Id']; // CId
		$cdata ['Content'] = $arr ['Content']; // Content
		$cdata ['AId'] = $u ['Id'];
		$cdata ['GId'] = $arr ['GoodsId'];
		/* 消息体结束 */
		$m = new noticeModel ();
		if (! $rid || $rid <= 0) {
			/* 商品留言通知 */
			$data ['Title'] = $tarr ['MSG'];
			$data ['RecipientId'] = ( int ) $g ['UserId']; /* 收件人 */
			$data ['Content'] = CNC ( $cdata, $tpl ['MSG'] );
			return $m->addone ( $data, 3 ); // 创建留言通知
		} else {
			$data ['Title'] = $tarr ['REPLY'];
			$data ['RecipientId'] = ( int ) $arr ['AssesseeId']; /* 收件人 */
			$data ['Content'] = CNC ( $cdata, $tpl ['REPLY'] );
			return $m->addone ( $data, 4 ); // 创建留言通知
		}
	}
}