<?php
//header("Content-type: text/html; charset=utf-8");
/**
 * 微信公众号开发
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/10/28
 * Time: 23:59
 */
class WeixinAction extends Action{
    public function index(){
        $long_url = 'http://www.oschina.net/odspfpsdf/kdsjfkjsdf';
        $short_url = $this->get_short_url($long_url);
        echo $short_url;die;
        $ipa = '101.226.62.77';
        $ip_list = $this->get_ip();
        //print_r($ip_list);die;
        if(in_array($ipa,$ip_list)){
            echo 'OK';
        }else{
            echo '非法请求';
        }
    }
    //获取二维码
    function get_erweima(){
        $token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "123"}}}';
        $result = json_decode($this->_curl_post($url,$data),true);
        $erweima_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket'];
        echo $this->_curl($erweima_url);
    }
    //获取用户的openid
    public function getOpenId(){
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb16f7ddbfee97b40&secret=d4624c36b6795d1d99dcf0547af5443d&code='.$code.'&grant_type=authorization_code';
        $data = (array)json_decode($this->_curl($url));

        $access_token = $data['access_token'];
        $openid = $data['openid'];
        $userinfo_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $userinfo = (array)json_decode($this->_curl($userinfo_url));
        echo 'hello,'.$userinfo['nickname'];
    }
    //获取用户列表
    public function get_user(){
        $token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$token;
        $result = $this->_curl($url);
        $result = (array)json_decode($result);
        $userOpenidArr = (array)$result['data'];
        foreach($userOpenidArr['openid'] as $k=>$v){
            $userInfoUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$v.'&lang=zh_CN';
            $data = $this->_curl($userInfoUrl);
            $data = (array)json_decode($data);
            echo $data['nickname']."<br>";
        }
    }
    //获取短链接
    function get_short_url($long_url){
        $token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.$token;
        $data = '{"action":"long2short","long_url":"'.$long_url.'"}';
        $result = (array)json_decode($this->_curl_post($url,$data));
        return $result['short_url'];
    }
    //获取token
    public function get_token(){
        $appId = 'wxb16f7ddbfee97b40';
        $appSecret = 'd4624c36b6795d1d99dcf0547af5443d';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
        $token = $this->_curl($url);
        $token = (array)json_decode($token);
        return $token['access_token'];
    }
    //获取微信服务器IP列表
    public function get_ip(){
        $token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$token;
        $iplist = (array)json_decode($this->_curl($url));
        return $iplist['ip_list'];
    }
    public function _curl($url){
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
    public function _curl_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
