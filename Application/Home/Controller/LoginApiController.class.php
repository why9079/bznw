<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 微信交互类
 */
namespace Home\Controller;
use Home\Logic\UsersLogic;

class LoginApiController extends BaseController {
    public $config;
    public $oauth;
    public $class_obj;

    public function __construct(){
        parent::__construct();
        $this->oauth = I('get.oauth');
        //获取配置
        $data = M('Plugin')->where("code='{$this->oauth}' and  type = 'login' ")->find();
        $this->config = unserialize($data['config_value']); // 配置反序列化
        if(!$this->oauth)
            $this->error('非法操作',U('User/login'));
        include_once  "plugins/login/{$this->oauth}/{$this->oauth}.class.php";
        $class = '\\'.$this->oauth; //
        $login = new $class($this->config); //实例化对应的登陆插件
        $this->class_obj = $login;
    }

    public function login(){
        if(!$this->oauth)
            $this->error('非法操作',U('User/login'));
        include_once  "plugins/login/{$this->oauth}/{$this->oauth}.class.php";
        $this->class_obj->login();
    }

    public function callback(){
        $data = $this->class_obj->respon();
        //exit(print_r($data));
        $logic = new UsersLogic();
        $data = $logic->thirdLogin($data);
        if($data['status'] != 1)
            $this->error($data['msg']);
        session('user',$data['result']);
        setcookie('user_id',$data['result']['user_id'],null,'/');
        setcookie('is_distribut',$data['result']['is_distribut'],null,'/');
        $nickname = empty($data['result']['nickname']) ? '第三方用户' : $data['result']['nickname'];
        setcookie('uname',urlencode($nickname),null,'/');
        setcookie('cn',0,time()-3600,'/');
        // 登录后将购物车的商品的 user_id 改为当前登录的id            
        //M('cart')->where("session_id = '{$this->session_id}'")->save(array('user_id'=>$data['result']['user_id']));
        if(isMobile())
            $this->success('登陆成功',U('Mobile/User/index'));
        else
            $this->success('登陆成功',U('User/index'));
    }
}