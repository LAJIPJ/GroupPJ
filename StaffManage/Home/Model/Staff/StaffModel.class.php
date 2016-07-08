<?php
    /*
    1 登录（包括：修改密码）
    2 可根据attributes和关键字查找员工信息（默认select*，注意对deleted项的检查）
      部分信息隐藏
    3 authority分级从0 - 3（3最低）
      可修改低级员工的信息（增加/删除/修改）
      不可修改本级及以上信息（包括自己）
    4 删除：编辑deleted项
    create table Staff (
    ID VARCHAR(16) PRIMARY KEY,
    Password VARCHAR(16) NOT NULL DEFAULT '000000',
    Name VARCHAR(40) NOT NULL,
    Tel CHAR(11) NOT NULL,
    EmergCont VARCHAR(40),
    PriSector VARCHAR(40) NOT NULL,
    ScdSector VARCHAR(40),
    Post VARCHAR(40) NOT NULL,
    Level VARCHAR(40),
    Address VARCHAR(160),
    ManagerID VARCHAR(16),
    JoinDate DATE DEFAULT CURDATE(),
    DeletedDate DATE DEFAULT null,
    Authority CHAR(1) DEFAULT '3'
    );
    */
namespace Home\Model\Staff;
use Think\Model;
class StaffModel extends Model {
    private $staff;

    //建构和析构函数可改名后在每次操作前后连接
    function __construct() {
        //更新数据库连接
        $staff = new mysqli(/*本地连接*/"localhost", "root", "", "staff");
        if(!$staff) {
            alert('连接数据库失败，请检查网络。');
        }
    }

    function __destruct() {
        $this->staff->close();
    }

    //返回密码或false（查找失败）
    public function getPassword($ID) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        /*
        $stmt = $this->staff->prepare("select Password from staff where ID= ?");
        $stmt->bind_param('s', $ID);
        $stmt->execute();
        */
        $result = $this->staff->query("select Password from staff where ID='{$ID}'");
        if($result->num_fields() === 1) {
            return $result->fetch_array();
        } else {
            return false;
        }
    }

    public function setPassword($ID, $Password) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        if(strlen($Password) > 16 || strlen($Password) < 6)
            return false;
        /*
        $stmt = $this->staff->prepare("update staff set Password = ? where ID=?");
        $stmt->bind_param('ss', $Password, $ID);
        return $stmt->execute();
        */
            $result = $this->staff->query("update staff set Password = (case when ID='{$ID}' then '{$Password}' end)");
        if($result->num_fields() === 1) {
            return true;
        } else {
            return false;
        }
    }

    //返回所有用户的ID
    function getAllUsers() {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        return $this->staff->query("select ID from staff");
    }

    //传入 一级部门，二级部门，姓名
    //若一级部门空缺，则忽略二级部门
    public function getInfo($Name, $PriSector, $ScdSector, $start, $end) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        if($PriSector === '') {
            return $this->staff->query("select * from staff where Name='%{$Name}%' limit {$start}, {$end} - {$start} + 1");
        }
        else if ($ScdSector ==='') {
            return $this->staff->query("select * from staff where PriSector = '{$PriSector}' and Name='%{$Name}%' limit {$start}, {$end} - {$start} + 1");
        }
        else {
            return $this->staff->query("select * from staff where PriSector = '{$PriSector}' and ScdSector = '{$ScdSector}' and Name='%{$Name}%' limit limit {$start}, {$end} - {$start} + 1");
        }
    }

    /*
    //检查权限
    //Controller?
    public function checkAuthority() {

    }
    */
    private function checkInput($NewStaff) {
        if(array_key_exists('Name', $NewStaff)) {
            if($NewStaff['Name'] === '' || strlen($NewStaff['Name']) > 40)
                return false;
        } else {
            return false;
        }

        if(array_key_exists('Tel', $NewStaff)) {
            if(!preg_match("/^1((3|5|8){1}\d{1}|70)\d{8}$/", $NewStaff['Tel']))
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('EmergCont', $NewStaff)) {
            if(strlen($NewStaff['EmergCont']) > 40)
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('PriSector', $NewStaff)) {
            if($NewStaff['PriSector'] === '' || strlen($NewStaff['PriSector']) > 40)
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('ScdSector', $NewStaff)) {
            if(strlen($NewStaff['ScdSector']) > 40)
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('Post', $NewStaff)) {
            if($NewStaff['Post'] === '' || strlen($NewStaff['Post']) > 40)
                return false;
        } else {
            return false;
        }


        if(!array_key_exists('Level', $NewStaff)) {
            if(strlen($NewStaff['Level']) > 40)
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('Address', $NewStaff)) {
            if(strlen($NewStaff['Address']) > 160)
                return false;
        } else {
            return false;
        }

        if(!array_key_exists('ManagerID', $NewStaff)) {
            //加入第一个人
            //修改
            $result1 = $this->staff->query("select * from staff");
            if($result1->num_fields() === 0) {
                return true;
            }
            //检查是否存在此直属经理
            $result2 = $this->staff->query("select * from staff where ID='{$NewStaff['ManagerID']}'");
            if($result2->num_fields() != 1)
                return false;
        } else {
            return false;
        }
    }

    //ID,JoinDate，Authority自动生成
    //返回ID或false（插入失败）
    public function addStaff($NewStaff) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        if(checkInput($NewStaff) === false) {
            return false;
        }
        //自动生成ID
        while(true) {
            $ID = uniqid();
            preg_replace('/\D/s', '', $ID);
            if(strlen($ID) < 16) continue;
            $ID = substr($ID,0,16);
            $result = $this->staff->query("select * from staff where ID='{$ID}'");
            if($result->num_fields() === 1) continue;
        }
        $result = $this->staff->query("insert into staff(ID,Name,Tel,EmergCont,PriSector,ScdSector,Post,Level,Address,ManagerID) 
            values('{$ID}','{$NewStaff['Name']}','{$NewStaff['Tel']}','{$NewStaff['EmergCont']}','{$NewStaff['PriSector']}','{$NewStaff['ScdSector']}','{$NewStaff['Post']}','{$NewStaff['Level']}','{$NewStaff['Address']}','{$NewStaff['ManagerID']}')");
        if($result->num_fields() === 1) {
            return $result->fetch_array();
        } else {
            return false;
        }
    }

    public function delStaff($ID) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        $result = $this->staff->query("update staff set DeletedDate = CURDATE() where ID='{$ID}'");
        if($result->num_fields() === 1) {
            return true;
        } else {
            return false;
        }
    }

    //不允许修改ID,Password,JoinDate,DeleteDate,Authority
    //返回ID或false(如果修改不成功)
    public function update($ID, $NewStaff) {
        if(!$this->staff) {
            alert('连接数据库失败，请检查网络。');
            return false;
        }
        if(checkInput($NewStaff) === false) {
            return false;
        }
        $result = $this->staff->query("update staff set Name='{$NewStaff['Name']}',Tel='{$NewStaff['Tel']}',EmergCont='{$NewStaff['EmergCont']}',PriSector='{$NewStaff['PriSector']}',ScdSector='{$NewStaff['ScdSector']}',Post='{$NewStaff['Post']}',Level='{$NewStaff['Level']}',Address='{$NewStaff['Address']}',ManagerID='{$NewStaff['ManagerID']}' where ID ='{$ID}'");
        if($result->num_fields() === 1) {
            return $result->fetch_array();
        } else {
            return false;
        }
    }

    public function test() {
        //for(int $i = 0;$i < 1;$i = $i + 1) {

       // }
        $Name = 'Alex';$Tel = '13000000000';$EmergCont = 'Jason';$PriSector = 'Development';$Post = 'Engineer';
        $ScdSector = 'Front-end';$Level = 'Primary';$Address='Shanghai China';$ManagerID = '0000000000000000';
        $NewStaff = [
            'Name'=>$Name,
            'Tel'=>$Tel,
            'EmergCont'=>$EmergCont,
            'PriSector'=>$PriSector,
            'ScdSector'=>$ScdSector,
            'Post'=>$Post,
            'Level'=>$Level,
            'Address'=>$Address,
            'ManagerID'=>$ManagerID,
        ];
        $ID = $this->addStaff($NewStaff);
        $this->show($ID);
    }
}
