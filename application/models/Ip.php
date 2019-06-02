<?php
/**
 * @name IpModel
 * @desc IP地址归属地查询功能
 * @author pangee
 */

class IpModel {
    public $errno = 0;
    public $errmsg = "";

    public function get( $ip ){
        $rep = ThirdParty_Ip::find( $ip );
        return $rep;
    }

}
