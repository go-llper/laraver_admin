<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 20:19
 */

return [



    //融宝支付配置
    'REAPAY_CONFIG' => array(
        'merchant_id'       => '100000001300212',
        'seller_email'      => 'weixin@9douyu.com',
        'privateKey'        => 'Lib/ORG/Util/ReaPay/cert/private.pem',
        'publicKey'         => 'Lib/ORG/Util/ReaPay/cert/public.pem',
        'apiKey'            => '44038972e37ba3c97b23f89f021b44210e11agdd9671dcfega8efdda694b4e84',
        'signUrl'           => 'http://api.reapal.com/fast/debit/portal',
        'payUrl'            => 'http://api.reapal.com/fast/pay',
        'cert_type'         => '01',
        'notify_url'        => '/user/pay/toNotice/platform/reapay',
        'online_pay_url'    => 'http://epay.reapal.com/portal?charset=utf-8',
        'online_return_url' => '/pay/return/platform/reapay',
        'sendCodeUrl'       => 'http://api.reapal.com/fast/sms',
        'selectOrderUrl'    => 'http://api.reapal.com/fast/search',
        'cardAuthUrl'       => 'http://reagw.reapal.com/reagw/bankcard/cardAuth.htm',

        'cardIdentifyUrl'   => 'http://api.reapal.com/fast/identify',
        'merchantIdCheckCard'=> '100000000236345',
        'apiKeyCheckCard'   => 'g4dae656472g13a0fdc198e9f71eb0eb611f9be4d5g3b0ae83bee01edad45e5c',
        'checkPublicKey'    => 'Lib/ORG/Util/ReaPay/check/public.pem',
        'checkPrivateKey'   => 'Lib/ORG/Util/ReaPay/check/private.pem',
        'certificateUrl'    => 'http://api.reapal.com/fast/certificate',//招行卡密接口
        'bindCardUrl'       => 'http://api.reapal.com/fast/cancle/bindcard',//解绑的接口
    ),

];