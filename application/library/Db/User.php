<?php

class Db_User extends Db_Base
{
    public function find( $uname )
    {
        $query = self::getDb()->prepare("select `password`,`id` from `user` where `name`= ? ");
        $query->execute( array($uname) );
        $ret = $query->fetchAll();
        if ( !$ret || count($ret)!=1 ) {
            list( self::$errno,self::$errmsg ) = Err_Map::get( 1003 );
//            self::$errno = -1003;
//            self::$errmsg = "用户查找失败";
            return false;
        }
        return $ret[0];
    }

    public function checkExists( $uname )
    {
        $query = self::getDb()->prepare("select count(*) as c from `user` where `name`= ? ");
        $query->execute( array($uname) );
        $count = $query->fetchAll();
        if ( $count[0]['c'] != 0 ) {
            list( self::$errno,self::$errmsg ) = Err_Map::get( 1005 );
//            self::$errno = -1005;
//            self::$errmsg = "用户名已存在";
            return false;
        }
        return true;
    }

    public function addUser( $uname ,$password, $datetime)
    {
        $query = self::getDb()->prepare("insert into `user` (`id`, `name`,`password`,`create_time`) VALUES ( null, ?, ?, ? )");
        $ret = $query->execute( array($uname, $password, date("Y-m-d H:i:s")) );
        if( !$ret ) {
            list( self::$errno,self::$errmsg ) = Err_Map::get( 1006 );
//            self::$errno = -1006;
//            self::$errmsg = "注册失败，写入数据失败";
            return false;
        }
        return true;
    }
}
