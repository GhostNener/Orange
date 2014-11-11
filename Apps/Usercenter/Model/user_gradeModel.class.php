<?php
namespace Usercenter\Model;
use Think\Model;

/**
 * 用户积分模型
 *
 */
class user_gradeModel extends Model{
	
	/**
	 * 通过用户积分获取相应等级信息
	 * @param int $grade：用户grade       	
	 * @return $rst ：符合的等级
	 */
	public function getgrade($EXP){
		$whereArr['MinEXP'] = array('elt',(int)$EXP);
		$whereArr['MaxEXP'] = array('egt',(int)$EXP);
		$whereArr['Status'] = 10; 
		$rst = $this->where($whereArr)->find();
		//显示需要升级的经验
		$rst['EXPDiff1'] = $rst['MaxEXP']-$rst['MinEXP'];
		//显示当前经验
		$rst['EXPDiff2'] = $EXP-$rst['MinEXP'];
		$rst['division'] = (int)(($rst['EXPDiff2'] / $rst['EXPDiff1'])*100);
		return $rst;
	}
}