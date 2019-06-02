<?php

class Common_Password
{
    const SALT = "Flourishing_";

    public static function pwdEncode( $pwd )
    {
        return md5( self::SALT . $pwd );
    }
}

