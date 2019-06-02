<?php
class Err_Map
{
    const ERRMAP = [
        1001 => '请通过正确渠道提交',
        1002 => '用户名与密码必须传递',
        1003 => '用户查找失败',
        1004 => '密码错误',
        1005 => '用户名已存在',
        1006 => '密码太短,请设置至少8位的密码',
        /**
         * 2**** 3**** 4**** ....
         */
    ];

    public static function get( $code )
    {
        if( isset( self::ERRMAP[$code]) )
        {
            return [
                 0 - $code ,
                 self::ERRMAP[$code]
             ];
        }
        return [0-$code ,'undefined this error number'];
    }
}
