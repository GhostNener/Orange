<?php

namespace Home\Controller;

use Think\Controller;
use Usercenter\Model\userModel;
use Usercenter\Model\view_user_info_avatarModel;
use Home\Model\goods_categoryModel;
use Home\Model\activityModel;
use Home\Model\noticeModel;
use Usercenter\Model\user_gradeModel;

/**
 * 基础控制器
 *
 * @author NENER
 *        
 */
class BaseController extends Controller {
	/**
	 * 基础渲染
	 */
	public function _initialize() {
		$user = new userModel ();
		$usermodel = null;
		if ($user->islogin ( null, false, false )) {
			$m = new view_user_info_avatarModel ();
			$usermodel = $m->getinfo ();
			if ($usermodel ['status'] == 1) {
				$usermodel = $usermodel ['msg'];
			} else {
				$usermodel = null;
			}
		}
		$isclockin = checkclockin ();
		if ($isclockin) {
			$isclockin = 1;
		} else {
			$isclockin = 0;
		}
		$nm = new noticeModel ();
		$un = $nm->getunread ( null, 2 );
		$this->assign ( 'urnotice', $un );
		/* 分类复制 */
		$model = new goods_categoryModel ();
		$clist = $model->getall ();
		$this->assign ( 'clist', $clist );
		/* 获取活动图片 */
		$model = new activityModel ();
		$hotactivitylist = $model->getlist ( array (
				'Status' => 10,
				'IsHot' => 1 
		), 3 );
		/* 热门活动 */
		$this->assign ( 'hotactivity', $hotactivitylist );
		$this->assign ( 'emptyact', '<hr><h5 class="text-center text-import">暂无活动</h5>' );
		/* 签到 */
		$this->assign ( 'isclockin', $isclockin );
		/* 用户 */
		$this->assign ( 'usermodel', $usermodel );
		/* 商品图路径 */
		$this->assign ( 'gmpath', C ( 'GOODS_IMG_PATH' ) );
		/* 用户头像路径 */
		$this->assign ( 'uapath', C ( 'USER_AVATAR_PATH' ) );
		
		/* 排行榜 */
		$model = M ( 'view_user_info_avatar' );
		$lct = $signlist = $model->where ( array (
				'Status' => 10,
				'LastClockinTime' => array (
						'egt',
						strtotime ( date ( 'Y-m-d', strtotime ( '-1 day' ) ) ) 
				) 
		) )->order ( array (
				'ClockinCount' => 'DESC',
				'LastClockinTime' => 'ASC' 
		) )->limit ( 5 )->field ( 'Id,Nick,URL,ClockinCount' )->select ();
		$gradelist = $model->where ( 'Status = 10' )->order ( 'EXP desc' )->limit ( 5 )->field ( 'Id,Nick,URL,EXP' )->select ();
		
		$gradeModel = new user_gradeModel ();
		foreach ( $gradelist as $key => $value ) {
			$gradelist [$key] ['EXP'] = $gradeModel->getgrade ( $value ['EXP'] );
		}
		$this->assign ( 'signlist', $signlist );
		$this->assign ( 'gradelist', $gradelist );
	}
}
?>