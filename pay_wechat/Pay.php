<?php
/**
 *
 */
namespace app\api\controller;

use think\Controller;
use think\Request;

class Pay extends Controller
{

    static $wxpayconf = [
        'wx_smallprogram_appid' => 'wx6393fe293790ee55', // 小程序appid
        'mch_id'                => '1487471542', // 小程序商户号
        'key'                   => 'bibeibei888888888888888888888888', //支付密钥
        'appsecret'             => '84e0fe0cdb5d30c01bf35756b58c13d2',
        'notify_url'            => 'https://api.zhibixing.cn/api/pay/not_url', // 支付回调地址
    ];
// ******************************************************获取实时的openid+code=>appsecret
    public function getOpenid()
    {

        $wxpayconf= self::$wxpayconf;
        $code    = Request::instance()->param('code');
        $url     = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $wxpayconf['wx_smallprogram_appid'] . "&secret=" . $wxpayconf['appsecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $url);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($curlobj);
        $data = json_decode($data, true);
        // 此处做修改 file_get_content不能访问https
        return $data['openid'];
    }

    public function nonceStr()
    {
        $charts   = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789";
        $max      = strlen($charts);
        $noncestr = "";
        for ($i = 0; $i < 32; $i++) {
            $noncestr .= $charts[mt_rand(0, $max - 1)];
        }
        return $noncestr;
    }

    public function postXmlCurl( $xmldata, $url, $second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);


        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xmldata);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }
    public function xml2array($xml){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }
    public function simplest_xml_to_array($xmlstring) {
        return json_decode(json_encode( simplexml_load_string($xmlstring)), true);
    }
    public function return_success(){
        $return='<xml> 
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                </xml>';
        return json_encode($return);
    }

// ******************************************************统一下单,返回小程序端需要的五个信息参数***************************************************
    public function unifiedorder()
    {
        // 初始化小程序支付配置
        $wxpayconf                = self::$wxpayconf;
        $userIP                   = $_SERVER['REMOTE_ADDR'];
        $sign['appid']            = $appid            = $wxpayconf['wx_smallprogram_appid'];
        $sign['mch_id']           = $mch_id           = $wxpayconf['mch_id'];
        $sign['nonce_str']        = $nonce_str        = $this->nonceStr();
        $sign['body']             = $body             = 'Gjanury';
        $sign['out_trade_no']     = $out_trade_no     = time();
        $sign['total_fee']        = $total_fee        = 1/*$paydata['actualpayment'] * 100*/; // 单位是分 1元 = 100分
        $sign['spbill_create_ip'] = $spbill_create_ip = $userIP; // 终端客户端ip
        $sign['trade_type']       = $trade_type       = 'JSAPI';
        $sign['openid']           = $openid           = $this->getOpenid();

        $sign['notify_url']       = $notify_url       = $wxpayconf['notify_url'];
        $sign['sign']             = $this->getSign($sign, $wxpayconf['key']);
        // 统一下单接口
        $url  = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $data = $this->arrayToXml($sign);
        $res  =$this->xml2array($this->postXmlCurl($data, $url));
        if(!$res){
            print_r('false');
        }
         // var_dump($res);echo "<br>";
        if (!$res) {
            echo json_encode($res);
            die;
        }
        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {
            $prepay_id = $res['prepay_id'];

            // prepay_id的为预支付
            // 小程序提供的是接口而已。直接调出即可
            // 支付环境参数配置部分

            $yuzhifudata['appId']     = $wxpayconf['wx_smallprogram_appid'];
            $yuzhifudata['package']   = 'prepay_id=' . $prepay_id;
            $yuzhifudata['timeStamp'] = (string) time();
            $yuzhifudata['nonceStr']  = $this->nonceStr();
            $yuzhifudata['signType']  = 'MD5';
            $yuzhifudata['paySign']   = $this->getSign($yuzhifudata, $wxpayconf['key']);
             // echo "<br>";var_dump($yuzhifudata);
            exit(json_encode($yuzhifudata));

        } else {
            if ($res['return_code'] == 'FAIL') {
                $info['return_msg'] = $res['return_msg'];
            }
            if ($res['result_code'] == 'FAIL') {
                $info['err_code']     = $res['err_code'];
                $info['err_code_des'] = $res['err_code_des'];
            }
            exit($info); //预支付单信息
        }
    }
    public function getSign($params, $key)
    {
        //签名步骤一：按字典序排序数组参数
        // 去空
        $data=array_filter($params);
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string_a=http_build_query($params);
        $string_a=urldecode($string_a);
        //签名步骤二：在string后加入KEY
        //$config=$this->config;
        $string_sign_temp=$string_a."&key=".$key;
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);
        // 签名步骤四：所有字符转为大写
        $result=strtoupper($sign);
        return $result;
    }
    // 数组转xml
    public function arrayToXml($arr, $is_array = false)
    {
        if (!$is_array) {
            $xml = '<xml>';
        }
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . $this->arrayToXml($val, true) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        if (!$is_array) {
            $xml .= "</xml>";
        }
        return $xml;
    }

    public function not_url()
    {
        $receipt = $_POST;
        if ($receipt == null) {
            $receipt = file_get_contents("php://input");
        }
        if ($receipt == null) {
            $receipt = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        file_put_contents('go_back0.txt', $receipt, FILE_APPEND);
        $post_data = $this->xml2array($receipt);
        file_put_contents('go_back.txt', $post_data, FILE_APPEND);
        $postSign  = $post_data['sign'];
        unset($post_data['sign']);
        ksort($post_data); // 对数据进行排序
        $str         = http_build_query($post_data); //对数组数据拼接成key=value字符串
        $user_sign   = strtoupper(md5($post_data)); //再次生成签名，与$postSign比较
        $ordernumber = $post_data['out_trade_no']; // 订单可以查看一下数据库是否有这个订单
        file_put_contents('out_trade_no.txt', $post_data['out_trade_no'], FILE_APPEND);
        // if ($post_data['return_code'] == 'SUCCESS' && $postSign) {
        //     // 查询订单是否已经支付

        //     $result = M('userorder')->where('ordernumber = "' . $ordernumber . '"')->select();


        //     if ($result) {
        //         if ($result[0]['paystatus'] == 0) {
        //             // 进行更改支付成功状态
        //             $obj = array(
        //                 "paystatus" => 1,
        //             );
        //             $res = M('userorder')->where('ordernumber = "' . $ordernumber . '"')->save($obj);
        //             file_put_contents('gg.txt', $res);
        //             if ($res) {
        //                 $this->return_success();
        //             }
        //         } else {
        //             $this->return_success();
        //         }
        //     } else {
        //         echo '微信支付失败,数据未存在该订单。';
        //     }
        // } else {
        //     // 写个日志记录
        //     file_put_contents('wxpayerrorlog.txt', $post_data['return_code'] . PHP_EOL, FILE_APPEND);
        //     echo '微信支付失败';
        // }
        # code...
    }

    // *******************************************************************************

}
