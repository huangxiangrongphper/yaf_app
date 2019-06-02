<?php
require __DIR__ . '/../vendor/autoload.php';
use \Curl\Curl;

$host = "http://yaf_app.test/user";
$curl = new Curl();
$uname = 'apitest_'.rand(10000,99999);
$pwd = 'apitest123';
/**
 * 注册用户
 */
$curl->post( $host."/register", array(
			'uname' => $uname,
			'pwd'	=> $pwd,
		));
if ($curl->error) {
    die( 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n" );
} else {
	$rep = json_decode( $curl->response, true );

	if( $rep['errno'] != 0 ) {
		die( '注册用户失败，注册接口异常。错误信息:'.$rep['errmsg']."\n" );
	}
	echo "注册用户接口测试成功，注册新用户:{$uname}\n";
}

/**
 * 用户登录
 */
$curl->post( $host."/login&submit=1", array(
			'uname' => $uname,
			'pwd'	=> $pwd,
		));
if ($curl->error) {
    die( 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n" );
} else {
	$rep = json_decode( $curl->response, true );
	if( $rep['errno'] != 0 ) {
		die( '用户登录失败，错误信息:'.$rep['errmsg']."\n" );
	}
	echo "使用新用户账号密码测试登录接口成功，账号:{$uname}，密码:{$pwd}\n";
}

/**
 * 使用错误密码登录！
 */
$curl->post( $host."/login&submit=1", array(
			'uname' => $uname,
			'pwd'	=> $pwd . rand(),
		));
if ($curl->error) {
    die( 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n" );
} else {
	$rep = json_decode( $curl->response, true );
	if( $rep['errno'] != 0 ) {
		die( '用户登录接口异常，错误信息:'.$rep['errmsg']."\n" );
	}
	echo '使用错误密码登录失败，正常。'."\n";
}

echo "用户接口测试完毕。\n";



