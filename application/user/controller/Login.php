<?php

namespace app\user\controller;

use alidayu\api_demo\SmsDemo;
use app\user\model\User;
use think\Controller;
use think\Exception;
use think\Request;
use think\Db;
use app\common\lib\Alidayu;
use app\common\validate\CodeValidate;

class Login extends Controller
{
    public function index()
    {
        $data = Db::table('announcements') -> where('is_delete', 0) -> limit(5) -> select();
        $this -> assign('data',$data);

        return $this -> fetch("login/index");
    }

    public function login()
    {
        $invite['user_id'] = input('get.user_id');
        $invite['invite_no'] = input('get.invite_no');
        // 通过邀请链接过来用户,存储邀请数据
        if (!empty($invite['invite_no']) && !empty($invite['user_id'])) {
            cookie('inviteInfo', $invite);
        }
        if(!empty(session("user"))){
            return redirect("/user/signup");
        }
        return $this -> fetch("login/login");
    }

    public function loginVerification(Request $request)
    {
        if($request -> isAjax()){
            $user = new User;
            $res = $user -> login(input('cardid'),input('password'));
            return $res;
        }else{
            return '请求错误';
        }
    }

    /**
     * 用户忘记密码
     * @return mixed
     */
    public function changePwd()
    {
        return $this -> fetch("login/changeUserPwd");
    }

    /**
     * 单位忘记密码
     * @return mixed
     */
    public function changeUnitPwd()
    {
        return $this->fetch('login/changeUnitPwd');
    }

    /**
     * 发送随机验证码
     * @return \think\response\Json
     * @throws Exception
     */
    public function code()
    {
        $data['telephone'] = trim(input('post.telephone'));
        if (empty($data['telephone'])) {
            return json(['code' => 0, 'msg' => '手机号码不能为空', 'name' => 'phone']);
        }
        if (!checkTelephone($data['telephone'])) {
            return json(['code' => 0, 'msg' => '手机号码格式错误', 'name' => 'phone']);
        }
        $code = mt_rand(100000, 999999);
        Alidayu::sendAliMessage($data['telephone'], $code);
        cookie($data['telephone'].$code, $code,120);
        return json(['code' => 1, 'name' => true]);
    }

    /**
     * 设置新密码
     * @return \think\response\Json
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function setNewPwd()
    {
        $name = input('post.name');  // 区分用户或单位,user是用户,unit是单位
        $data['telephone'] = trim(input('post.telephone'));
        $data['code'] = trim(input('post.code'));
        $data['password'] = trim(input('post.password'));
        $confirmPwd = trim(input('post.confirmPassword'));
        $verify = $this->verifyField($data);
        if ($verify !== true) {
            return $verify;
        }
        if ($confirmPwd != $data['password']) {
            return json(['code' => 0, 'msg' => '确认密码不一致', 'name' => 'confirmPassword']);
        }
        // 验证密码是否为原密码
        if ($this->checkNewPasswordIsExists($data['password'], $data['telephone'])) {
            return json(['code' => 0, 'msg' => '新密码与原密码一致', 'name' => 'password']);
        }
        if ($name == "user") {
            // 修改用户密码
            $res = Db::name('user')->where('phone', $data['telephone'])->update([
                'password' => md5($data['password']),
            ]);
        }
        else {
            // 修改单位密码
            $res = Db::name('unit')->where('phone', $data['telephone'])->update([
                'password' => md5($data['password']),
            ]);
        }
        if ($res === false) {
            return json(['code' => 0, 'msg' => '修改密码失败']);
        }
        return json(['code' => 1, 'msg' => '设置新密码成功', 'url' => '/user/index']);
    }


    public function setPassword()
    {
        $cardid = input('cardid');
        $problem = input('pwdproblem');
        $answer = input('pwdanswer');
        $password = md5(input('password'));
        $data = Db::name('user')->join('userinfo t2','user.id=t2.uid')->where('cardid',$cardid)->find();
        if($problem == $data['pwdproblem']){
            if($answer == $data['pwdanswer']){
                $res = Db::name('user')->where('cardid',$cardid)->update(['password'=>$password]);
                if($res==1){
                    return json(['code'=>200,'error'=>null]);
                }else{
                    return json(['code'=>201,'error'=>'数据错误，请联系站长']);
                }
            }
            return json(['code'=>201,'error'=>'密码找回答案错误']);
        }
        return json(['code'=>201,'error'=>'密码找回问题错误']);
    }

    /**
     * 检查新密码是否存在
     * @param $password
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkNewPasswordIsExists($password, $phone)
    {
        $res = Db::name('user')->where(array('password' => md5($password), 'phone' => $phone))->field('id')->find();
        if (empty($res)) {
            return false;
        }
        return true;
    }

    /**
     * 验证字段
     * @param $data
     * @return bool|\think\response\Json
     */
    private function verifyField($data)
    {
        if (empty($data['telephone'])) {
            return json(['code' => 0, 'msg' => '手机号码不能为空', 'name' => 'phone']);
        }
        if (!checkTelephone($data['telephone'])) {
            return json(['code' => 0, 'msg' => '手机号码格式错误', 'name' => 'phone']);
        }
        if (empty($data['code'])) {
            return json(['code' => 0, 'msg' => '验证码不能为空', 'name' => 'code']);
        }
        if (strlen($data['code']) != 6) {
            return json(['code' => 0, 'msg' => '验证码只能为6位', 'name' => 'code']);
        }
        if ($data['code'] != cookie($data['telephone'].$data['code'])) {
            return json(['code' => 0, 'msg' => '验证码输入错误', 'name' => 'code']);
        }
        if (empty($data['password'])) {
            return json(['code' => 0, 'msg' => '新密码不能为空', 'name' => 'password']);
        }
        return true;
    }
}