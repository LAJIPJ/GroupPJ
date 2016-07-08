<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\Staff\StaffModel;

class StaffController extends Controller{
    public function index() {
        $this->edit();
    }

    public function addAction() {
        $staffModel = new StaffModel();
        $staffModel->addStaff($_REQUEST);
        $this->ajaxReturn($staffModel ->addStaff($_REQUEST));
    }

    public function editAction() {
        $staffModel = new StaffModel();
        $staffModel->update($_REQUEST["staffId"], $_REQUEST);
        $this->ajaxReturn($staffModel ->addStaff($_REQUEST));
    }

    public function overview() {
        $this->display('Search');
    }

    private function displayView($isEditing) {
//        $staffModel = new StaffModel();

        if ($isEditing == 'true') {
            $this->userInfo = array("Name" => 'Nick', "Tel" => 13270808162, "EmergCont" => "WTF",
            "PriSector" => "APP部", "ScdSector" => "iOS组", "Post" => "搬瓦工", "Level" => "level1",
            "Address" => "NJU", "ManagerID" => "9527", "JoinDate" => '2016-07-04');
        } else {
            $this->userInfo = array();
        }
        $this->priSectors = array("department1", "department2", "APP部");
        $this->scdSectors = array("team1", "team2", "iOS组", "Android组");
        $this->posts = array("搬瓦工", "岗位1");
        $this->levels = array("level1", "level2", "level19");
        $this->isEditing = $isEditing;
        $this->display('detail');
    }

    public function edit() {
//        $this->error("该用户不存在");
        $this->staffId = $_REQUEST["staffId"];
        $this->displayView('true');
    }

    public function add() {
        $this->displayView('false');
    }
}
