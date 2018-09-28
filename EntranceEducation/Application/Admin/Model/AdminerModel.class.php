<?php 

namespace Admin\Model;
use Think\Model;
class AdminerModel extends Model {


	// 返回管理员的学院 校级管理员则返回 ''
	public function getCollege () {

		if (session('type') == 1) {
			return ;
		}

		if ( session('type') == 2 && !is_null(session('nickname'))) {
			return session('nickname');
		}
		return ;
	}

	
}
 ?>