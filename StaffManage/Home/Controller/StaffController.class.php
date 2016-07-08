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
        $this->ajaxReturn($staffModel ->addStaff($_REQUEST));
    }

    public function edit() {
        $this->userInfo = array("Name" => 'Nick', "Tel" => 13270808162, "EmergCont" => "WTF",
            "PriSector" => "APP部", "ScdSector" => "iOS组", "Post" => "搬瓦工", "Level" => "level1",
            "Address" => "NJU", "ManagerID" => "9527", "JoinDate" => '2016-07-04');
        $this->priSectors = array("department1", "department2", "APP部");
        $this->scdSectors = array("team1", "team2", "iOS组", "Android组");
        $this->posts = array("搬瓦工", "岗位1");
        $this->levels = array("level1", "level2", "level19");
        $this->isEditing = 'true';
        $this->display('detail');
    }

    public function add() {
        $this->userInfo = array();
        $this->priSectors = array("department1", "department2", "APP部");
        $this->scdSectors = array("team1", "team2", "iOS组", "Android组");
        $this->posts = array("搬瓦工", "岗位1");
        $this->isEditing = 'false';

        $this->display('detail');
    }
}
