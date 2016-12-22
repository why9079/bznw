<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Api\Logic;
use JPush\Client;

/**
 * Description of JpushLogic
 *
 * @author Administrator
 */
class JpushLogic {
    private $appkey;
    private $master_secret;
    private $push;
            
    function __construct() {
        $this->appkey = C('APPKEY');
        $this->master_secret = C('MASTERSECRET');
        $this->push = new \JPush\Client($this->appkey, $this->master_secret);
    }
    
    /*
     * 全体推送
     */
    public function push_all($message){
        $push_payload = $this->push->push()
                ->setPlatform('all')
                ->addAllAudience()
                ->setNotificationAlert($message);
        try {
        $response = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
        print $e;
    }
        print_r($response);
    }

}
