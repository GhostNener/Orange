<?php
namespace Home\Model;
use Think\Model;

/**
 * 商品评论及回复模型
 * 
 * @author DongZ
 *
 */
class goods_commentModel extends Model{
	
	
	/**
	 * 保存商品评论及回复
	 *
	 * @param array $postarr:
	 *        	post数组
	 * @return array 保存信息： 包含 status 状态 ；
	 *         msg 消息
	 */
	public function addComment($postarr) {
		if (empty ( $postarr )) {
			return array (
					'status' => 0,
					'msg' => '没有数据' 
			);
		}
		// 需要提交的商品评论参数
		$data = array (
				'GoodsId' => $postarr ['GoodsId'],
				'Content' => $postarr ['Content'],
				'CreateTime' => date ( "Y-m-d H:i:s", time () ),
				'UserId' => cookie('_uid'),
				'AssesseeId' => $postarr ['AssesseeId'],
				'Status' => 10 
		);
		$dal = M ();
		$dal->startTrans ();
		$goods_comment = M ( 'goods_comment' );
		// 保存商品评论
		$rst = $goods_comment->add ( $data );
		if ($rst) {
			$dal->commit ();
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
}