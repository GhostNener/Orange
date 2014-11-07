<?php
namespace Usercenter\Model;
use Think\Model;

/**
 * 用户积分模型
 * Enter description here ...
 * @author DongZ
 *
 */
class user_gradeModel extends Model{
	
	/**
	 * 通过用户积分获取相应等级信息
	 * @param int $grade：用户grade       	
	 * @return $rst ：符合的等级
	 */
	public function getgrade($grade){
		$whereArr['MinEXP'] = array('ELT',$grade);
		$whereArr['MaxEXP'] = array('EGT',$grade);
		$whereArr['Status'] = 10; 
		$rst = $this->where($whereArr)->find();
		return $rst;
	}
}