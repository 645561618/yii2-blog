<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Common;

class TestController extends Controller
{
    //生成器使用
    public function actionRun()
    {
	$res = $this->getOpenIdData();
	//foreach($res as $key=>$value){
	//	echo $key."-->".$value."\n";
	//}	
    }


    public function getOpenIdData()
    {
	$access_token = Yii::$app->cache->get('access_token');
        if(!$access_token){
                $access_token = Common::getAccessToken();
	}	

	$result  = $this->getData($access_token);
	foreach($result as $k=>$v){
		//yield $v;
		echo $v."\r\n";
	}

    }

    public function getData($access_token)
    {

        if($openid_Data = Yii::$app->cache->get('openid')){
                $this->getOpenid($openid_Data,$access_token);
        }else{
                $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                $result = json_decode(Common::https_request($url));
                $openid_Data = $result->data->openid;
		return $openid_Data;
        }
    }


    /*public function actionTool()
    {
	$ssh_user='root';　　　　　　　　//登陆linux的ssh2用户名
	$ssh_pwd='';　　　　　　//登陆linux的密码
	$ssh_port='22';                          //端口号22
	$ssh_host='192.168.0.100';        //服务器IP地址

	//先测试拓展是否安装并开启
	if(!function_exists("ssh2_connect")){
        	exit('SSH扩展没有安装或者没有安装成功');
    	}

	//建立ssh2连接
	$ssh2 = ssh2_connect($ssh_host, $ssh_port);
    	if(!$ssh2){
        	exit('连接服务器失败');
    	}else{
        	echo '成功连接上了服务器';
    	}

	//连接成功后进行密码验证，没验证无法进行其他操作。
	if(!ssh2_auth_password( $ssh2, $ssh_user,  $ssh_pwd )){
        	return false;
        }	
	$e="mkdir -m 777 /var/www/html/test";   //shell脚本语句
	ssh2_exec($ssh2, $e);            //通过ssh2_exec执行语句

    }*/


    public function actionTests()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://huangxinqiang.ala.bs/api/groups");
//        curl_setopt($ch, CURLOPT_URL, "https://huangxinqiang.ala.bs/api/domains/553366/keywords");
//        curl_setopt($ch, CURLOPT_URL, "https://huangxinqiang.ala.bs/api/rank_averages?domain_id=553366");
        curl_setopt($ch, CURLOPT_URL, "https://huangxinqiang.ala.bs/api/ranks?keyword_id=37836301");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: vnd.authoritylabs+json; version=1",
            "X-API-KEY: 7e9bacce3727eecd71a2c76e0d574ed2"
        ));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查

        $user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36";
        curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);

        $response = curl_exec($ch);
        $response = json_decode($response);


        curl_close($ch);
        echo "pre";
        print_r($response);exit;
    }
 

    public function actionTestScreen()
    {
	for($i=1;$i<1000000;$i++){
		echo $i."\r\n";
		sleep(2);
        }


    }




}





