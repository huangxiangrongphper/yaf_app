<?php
/**
 * @name SmsModel
 * @desc 短信操作Model类,使用sms.cn服务，账号phpapi密码phpapi321
 * @author pangee
 */

class SmsModel {
    public $errno = 0;
    public $errmsg = "";
    private $_db;

    public function __construct() {
        $this->_db = new PDO("mysql:host=127.0.0.1;dbname=yaf_app;", "homestead", "secret");
    }

    public function send( $uid, $contents ) {
        $query = $this->_db->prepare("select `mobile` from `user` where `id`= ? ");
        $query->execute( array(intval($uid)) );
        $ret = $query->fetchAll();
        if ( !$ret || count($ret)!=1 ) {
            $this->errno = -4003;
            $this->errmsg = "用户手机号信息查找失败";
            return false;
        }
        $userMobile = $ret[0]['mobile'];
        if( !$userMobile || !is_numeric($userMobile) || strlen($userMobile)!=11 ) {
            $this->errno = -4004;
            $this->errmsg = "用户手机号信息不符合标准，手机号为：".(!$userMobile?"空":$userMobile);
            return false;
        }

        $smsUid = "flourishing728";
        $smsPwd = "75788a7274941c318b74216da1c8533f";
        $sms = new ThirdParty_Sms( $smsUid, $smsPwd );

        $contentParam = array( 'code'=>rand(1000,9999) );
        $template = '100006';
        $result = $sms->send($userMobile, $contentParam, $template);
        if($result['stat']=='100') {
            /**
             * 成功则记录，用于日后对账
             */
            $query = $this->_db->prepare("insert into `sms_record` (`uid`,`contents`,`template`) VALUES ( ?, ?, ? )");
            $ret = $query->execute( array($uid, json_encode($contentParam), $template) );
            if( !$ret ){
                /**
                 * TODO 应该返回true还是false，有待商榷
                 */
                $this->errno = -4006;
                $this->errmsg = '消息发送成功，但发送记录失败。';
                return false;
            }
            return true;
        } else {
            $this->errno = -4005;
            $this->errmsg = '发送失败:'.$result['stat'].'('.$result['message'].')';
            return false;
        }
    }

}
