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
 * $Author: IT宇宙人 2015-08-10 $
 */ 
namespace Api\Controller;
use Think\Controller;
use Api\Logic;



class IndexController extends BaseController {
    public function index(){
       // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }
 
    /*
     * 获取首页数据
     */
    public function home(){
        //获取轮播图
        $data = M('ad')->where('pid = 2')->field(array('ad_link','ad_name','ad_code'))->cache(true,TPSHOP_CACHE_TIME)->select();
        //广告地址转换
        foreach($data as $k=>$v){
//            exit($this->http_url);
            if(!strstr($v['ad_link'],'http'))
//                exit($this->http_url);
                $data[$k]['ad_link'] = SITE_URL.$v['ad_link'];
            $data[$k]['ad_code'] = SITE_URL.$v['ad_code'];

        }
        //获取大分类
        $category_arr = M('goods_category')->where('parent_id=0')->field('id,name')->limit(8)->cache(false,TPSHOP_CACHE_TIME)->select();
        $result = array();
        foreach($category_arr as $c){
            $cat_arr = getCatGrandson($c['id']);
            //获取商品
            //$sql = "select goods_name,goods_id,original_img,shop_price from __PREFIX__goods where  cat_id in (".implode(',',$cat_arr).") limit 4";
            //$goods = M()->query($sql);
            if($cat_arr)
                $cat_id = implode (',', $cat_arr);
            $goodsList = M('goods')->where("cat_id in($cat_id)")->limit(8)->cache(false,TPSHOP_CACHE_TIME)->getField("goods_id,goods_name,goods_remark,original_img,market_price,shop_price");
            foreach($goodsList as $k => $v){
                $v['original_img'] = SITE_URL.$v['original_img'];
                $c['goods_list'][] = $v;
            }            
            $result[] = $c;
        }

        exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>array('goods'=>$result,'ad'=>$data))));
    }
    
    public function homePages(){
        $a = new \Api\Logic\JpushLogic();
        $message = "%#￥@！#！#~";
        $a->push_all($message);
    }
 /**
     * 获取APP首页数据信息
     */
    public function homePage(){

        //获取轮播图信息
        $data = M('ad')->where('pid = 2')->field(array('ad_link','ad_name','ad_code'))->cache(true,TPSHOP_CACHE_TIME)->select();
        foreach($data as $k=>$v){
//            exit($this->http_url);
            if(!strstr($v['ad_link'],'http'))
//                exit($this->http_url);
                $data[$k]['ad_link'] = SITE_URL.$v['ad_link'];
            $data[$k]['ad_code'] = SITE_URL.$v['ad_code'];

        }
        //获取新品推荐
//        $newgoods = M('goods')->where('is_new=1')->field('goods_id,goods_name,goods_remark,original_img')->limit(2)->cache(false,TPSHOP_CACHE_TIME)->select();
//        foreach($newgoods as $k => $v){
//                $v['original_img'] = SITE_URL.$v['original_img'];
//                $c['goods_list'][] = $v;
//            }            
//            $result1[] = $c;
//        //获取每周特价
//        $cheapgoods = M('goods')->where('prom_type=3')->field('goods_id,goods_name,goods_remark,original_img,market_price,shop_price')->limit(2)->cache(false,TPSHOP_CACHE_TIME)->select();
//        foreach($cheapgoods as $k => $v){
//                $v['original_img'] = SITE_URL.$v['original_img'];
//                $c['goods_list'][] = $v;
//            }            
//            $result2[] = $c;
//        //获取热卖特产
//        $hotgoods = M('goods')->where('is_hot=1')->field('goods_id,goods_name,goods_remark,original_img,shop_price')->limit(2)->cache(false,TPSHOP_CACHE_TIME)->select();
//        foreach($hotgoods as $k => $v){
//                $v['original_img'] = SITE_URL.$v['original_img'];
//                $c['goods_list'][] = $v;
//            }            
//            $result3[] = $c;
            
        exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>array('ad'=>$data))));
    }
    
    /**
     * 获取APP首页新品推荐信息
     */
    public function newgoods(){
        $newgoods = M('goods')->where('is_new=1')->field('goods_id,goods_name,goods_remark')->limit(3)->cache(false,TPSHOP_CACHE_TIME)->select();
        foreach($newgoods as $k => $v){
                $v['original_img'] = SITE_URL.$v['original_img'];
                $c['goods_list'][] = $v;
            }            
            $result1[] = $c;
            exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>array('newgoods'=>$result1))));
    }
    /**
     * 获取APP首页每周特价信息
     */
    public function cheapgoods(){
        $cheapgoods = M('goods')->where('prom_type=3')->field('goods_id,goods_name,goods_remark,market_price,shop_price')->limit(4)->cache(false,TPSHOP_CACHE_TIME)->select();
        foreach($cheapgoods as $k => $v){
                $v['original_img'] = SITE_URL.$v['original_img'];
                $c['goods_list'][] = $v;
            }            
            $result2[] = $c;
            exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>array('cheapgoods'=>$result2))));
    }
    /**
     * 获取APP首页热卖特产信息
     */
    public function hotgoods(){
        $hotgoods = M('goods')->where('is_hot=1')->field('goods_id,goods_name,goods_remark,shop_price')->limit(3)->cache(false,TPSHOP_CACHE_TIME)->select();
        foreach($hotgoods as $k => $v){
                $v['original_img'] = SITE_URL.$v['original_img'];
                $c['goods_list'][] = $v;
            }            
            $result3[] = $c;
            exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>array('hotgoods'=>$result3))));
    }
    /**
     * 获取服务器配置
     */
    public function getConfig()
    {
        $config_arr = M('config')->select();
        exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>$config_arr)));
    }
    /**
     * 获取插件信息
     */
    public function getPluginConfig()
    {
        $data = M('plugin')->where("type='payment' OR type='login'")->select();
        $arr = array();
        foreach($data as $k=>$v){
            unset( $data[$k]['config']);
            unset( $data[$k]['config']);

            $data[$k]['config_value'] = unserialize($v['config_value']);
            if($data[$k]['type'] == 'payment'){
                $arr['payment'][] =  $data[$k];
            }
            if($data[$k]['type'] == 'login'){
                $arr['login'][] =  $data[$k];
            }
        }
        exit(json_encode(array('status'=>1,'msg'=>'获取成功','result'=>$arr ? $arr : '')));
    }
}