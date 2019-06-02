<?php
/**
 * @name ArtController
 * @author pangee
 * @desc 文章控制器
 */
class ArtController extends Yaf_Controller_Abstract {

    public function indexAction() {
        return $this->listAction();
    }
    public function addAction( $artId=0 ) {
        if( !$this->_isAdmin() ) {
            echo json_encode( array("errno"=>-2000, "errmsg"=>"需要管理员权限才可以操作") );
            return FALSE;
        }

        $submit = $this->getRequest()->getQuery( "submit", "0" );
        if( $submit!="1" ) {
            echo json_encode( array("errno"=>-2001, "errmsg"=>"请通过正确渠道提交") );
            return FALSE;
        }

        // 获取参数
        $title = $this->getRequest()->getPost( "title", false );
        $contents = $this->getRequest()->getPost( "contents", false );
        $author = $this->getRequest()->getPost( "author", false );
        $cate = $this->getRequest()->getPost( "cate", false );

        if( !$title || !$contents || !$author || !$cate) {
            echo json_encode( array("errno"=>-2002, "errmsg"=>"标题、内容、作者、分类信息为空，不能为空") );
            return FALSE;
        }

        // 调用Model，做登录验证
        $model = new ArtModel();
        if ( $lastId = $model->add( trim($title), trim($contents), trim($author), trim($cate), $artId) ) {
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
                "data"=>array("lastId"=>$lastId),
            ));
        } else {
            echo json_encode( array(
                "errno"=>$model->errno,
                "errmsg"=>$model->errmsg,
            ));
        }
        return TRUE;
    }
    public function editAction() {
        if( !$this->_isAdmin() ) {
            echo json_encode( array("errno"=>-2000, "errmsg"=>"需要管理员权限才可以操作") );
            return FALSE;
        }

        $artId = $this->getRequest()->getQuery( "artId", "0" );
        if( is_numeric($artId) && $artId ) {
            return $this->addAction( $artId );
        } else {
            echo json_encode( array("errno"=>-2003, "errmsg"=>"缺少必要的文章ID参数") );
        }
        return TRUE;
    }
    public function delAction(){
        if( !$this->_isAdmin() ) {
            echo json_encode( array("errno"=>-2000, "errmsg"=>"需要管理员权限才可以操作") );
            return FALSE;
        }

        $artId = $this->getRequest()->getQuery( "artId", "0" );
        if( is_numeric($artId) && $artId ) {
            $model = new ArtModel();
            if( $model->del( $artId ) ) {
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
        } else {
            echo json_encode( array("errno"=>-2003, "errmsg"=>"缺少必要的文章标题参数") );
        }
        return TRUE;
    }
    public function statusAction(){
        if( !$this->_isAdmin() ) {
            echo json_encode( array("errno"=>-2000, "errmsg"=>"需要管理员权限才可以操作") );
            return FALSE;
        }

        $artId = $this->getRequest()->getQuery( "artId", "0" );
        $status = $this->getRequest()->getQuery( "status", "2" );

        if( is_numeric($artId) && $artId ) {
            $model = new ArtModel();
            if( $model->status( $artId, $status ) ) {
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
        } else {
            echo json_encode( array("errno"=>-2003, "errmsg"=>"缺少必要的文章标题参数") );
        }
        return TRUE;
    }
    public function getAction(){
        $artId = $this->getRequest()->getQuery( "artId", "0" );

        if( is_numeric($artId) && $artId ) {
            $model = new ArtModel();
            if( $data=$model->get( $artId ) ) {
                echo json_encode( array(
                    "errno"=>0,
                    "errmsg"=>"",
                    "data"=>$data,
                ));
            } else {
                echo json_encode( array("errno"=>-2009, "errmsg"=>"获取文章信息失败") );
            }
        } else {
            echo json_encode( array("errno"=>-2003, "errmsg"=>"缺少必要的文章标题参数") );
        }
        return TRUE;
    }
    public function listAction(){
        $pageNo = $this->getRequest()->getQuery( "pageNo", "0" );
        $pageSize = $this->getRequest()->getQuery( "pageSize", "10" );
        $cate = $this->getRequest()->getQuery( "cate", "0" );
        $status = $this->getRequest()->getQuery( "status", "1" );

        $model = new ArtModel();
        if( $data=$model->_list( $pageNo, $pageSize, $cate, $status ) ) {
            echo json_encode( array(
                "errno"=>0,
                "errmsg"=>"",
                "data"=>$data,
            ));
        } else {
            echo json_encode( array("errno"=>-2012, "errmsg"=>"获取文章列表失败") );
        }
        return TRUE;
    }


    private function _isAdmin(){
        return true;
    }
}
