<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\Staff\StaffModel;

class LoginController extends Controller{

	public function login(){
		 $account["ID"] = I("ID");
		 $account["password"] = I("password");
		 $userModel = new StaffModel();
		 $pwd = $userModel->GetPassword($account["ID"]);
		 if($pwd){
		 	if($pwd == $account["password"]){
		 		$this->ajaxReturn(1);
		 	}
		 	else{
		 		$this->ajaxReturn(0);
		 	}
		 }
		 else{
		 		$this->ajaxReturn(0);
		 	}
		
	}


}