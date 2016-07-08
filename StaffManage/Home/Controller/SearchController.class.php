<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\Staff\StaffModel;

class SearchController extends Controller{

	public function search(){
		 $list["Name"] = I("Name");
		 $list["PriSector"] = I("PriSector");
		 $list["ScdSector"] = I("ScdSector");
		 $userModel = new StaffModel();
		 $staff = $userModel->getInfo($list["Name"], $list["PriSector"], $list["ScdSector"], 0, 100);
		 $this->ajaxReturn($staff);
		 	
		
	}


}