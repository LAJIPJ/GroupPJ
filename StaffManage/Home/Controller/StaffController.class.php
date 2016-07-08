<?php
namespace Home\Controller;
use Think\Controller;


class StaffController extends Controller{
    public function index() {
        $this->detail();
    }

    private function detail() {
        $this->display('detail');
    }
}
