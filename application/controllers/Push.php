<?php
/**
 * @name PushController
 * @author pangee
 * @desc 推送服务接口
 */

class PushController extends Yaf_Controller_Abstract {

    public function singleAction() {
        if( !$this->_isAdmin() ) {
            if( !$cid ) {
                echo json_encode( array("errno"=>-7001, "errmsg"=>"仅管理员可以进行此操作") );
                return FALSE;
            }
        }
        $cid = $this->getRequest()->getQuery( "cid", "" );
        $msg = $this->getRequest()->getQuery( "msg", "" );
        if( !$cid || !$msg ) {
            echo json_encode( array("errno"=>-7002, "errmsg"=>"请输入推送用户的设备ID与要推送的内容") );
            return FALSE;
        }

        // 调用Model
        $model = new PushModel();
        if ( $model->single( $cid, $msg ) ) {
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
            ));
        } else {
            echo json_encode( array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg,
            ));
        }
        return TRUE;
    }
    public function toallAction() {
        if( !$this->_isAdmin() ) {
            if( !$cid ) {
                echo json_encode( array("errno"=>-7001, "errmsg"=>"仅管理员可以进行此操作") );
                return FALSE;
            }
        }
        $msg = $this->getRequest()->getQuery( "msg", "" );
        if( !$msg ) {
            echo json_encode( array("errno"=>-7004, "errmsg"=>"请输入要推送的内容") );
            return FALSE;
        }

        // 调用Model
        $model = new PushModel();
        if ( $model->toAll( $msg ) ) {
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
            ));
        } else {
            echo json_encode( array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg,
            ));
        }
        return TRUE;
    }

    private function _isAdmin(){
        return true;
    }
}
