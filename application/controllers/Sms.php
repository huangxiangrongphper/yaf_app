<?php
/**
 * @name SmsController
 * @author pangee
 * @desc 短信处理功能
 */
class SmsController extends Yaf_Controller_Abstract {

    public function indexAction() {
    }
    public function sendAction() {
        $submit = $this->getRequest()->getQuery( "submit", "0" );
        if( $submit!="1" ) {
            echo json_encode( array("errno"=>-4001, "errmsg"=>"请通过正确渠道提交") );
            return FALSE;
        }

        // 获取参数
        $uid = $this->getRequest()->getPost( "uid", false );
        $contents = $this->getRequest()->getPost( "contents", false );
        if( !$uid || !$contents ) {
            echo json_encode( array("errno"=>-4002, "errmsg"=>"用户ID、邮件标题、邮件内容均不能为空。") );
            return FALSE;
        }

        // 调用Model, 发邮件
        $model = new SmsModel();
        if ( $model->send( intval($uid), trim($contents) ) ) {
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
}
