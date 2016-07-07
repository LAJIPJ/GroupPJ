<?php
namespace Home\Controller;
use Think\Controller;


class StaffController extends Controller{
    public function index() {
        $this->overview();
    }

    private function overview() {
        $this->display('Overview');
    }
}
