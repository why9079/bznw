<?php
namespace TheJpush;
//require 'conf.php';
/*
极光推送类
 */
class the_jpush {
    public $jpush;
    function __construct() {
        $this->jpush = new \JPush\Client("9cbc718ce6b4ff7e0f1850a9", "07d7433588b13890044ef342");
    }
    //put your code here
    
    public function push_all($message){
        $push_payload = $this->jpush->push()
            ->setPlatform('all')
            ->addAllAudience()
            ->setNotificationAlert($message);
        try {
            $response = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            print $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            print $e;
        }
        print_r($response);
    }
}
