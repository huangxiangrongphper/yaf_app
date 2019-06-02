<?php
/**
 * @name IpController
 * @author pangee
 * @desc IP地址归属地查询功能
 */
class IpController extends Yaf_Controller_Abstract {

    public function indexAction() {
    }
    public function getAction() {
        $ip = $this->getRequest()->getQuery( "ip", "" );
        if( !$ip || !filter_var($ip, FILTER_VALIDATE_IP) ) {
            echo json_encode( array("errno"=>-5001, "errmsg"=>"请传递正确的IP地址") );
            return FALSE;
        }

        // 调用Model, 查IP归属地
        $model = new IpModel();
        if ( $data=$model->get( trim($ip) ) ) {
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
                "data" => $data,
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
