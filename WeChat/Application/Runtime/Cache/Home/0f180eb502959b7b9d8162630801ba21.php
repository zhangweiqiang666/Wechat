<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微信分享JS接口</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
</head>
<style type="text/css">
    button{
        width: 100%;
        height: 100px;
        background: #36b52a;
        font-size: 50px;
    }
</style>
<body>
<script>

    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wx0291ed993373aabf', //测试号  //必填，公众号的唯一标识
        //appId: 'wx612271828ee4de36',//公众号（我是大嘤雄）
        
        timestamp: '<?php echo ($timestamp); ?>', // 必填，生成签名的时间戳
        nonceStr: '<?php echo ($noncestr); ?>', // 必填，生成签名的随机串
        signature: '<?php echo ($signature); ?>',// 必填，签名
        jsApiList: [
             'openLocation',//使用微信内置地图查看位置接口
            'getLocation',//获取地理位置接口
            'uploadImage',//上传图片
            'chooseImage',//拍照或从手机相册中选图接口
            'previewImage',//预览图片接口
            'getLocalImgData',//获取本地图片
            'scanQRCode'//扫一扫接口

        ] // 必填，需要使用的JS接口列表
    });
    //上传图片
    function uploadImage(){
        //选择图片
        wx.chooseImage({
            count: 1, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
            success: function (res) {
                var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片

                //上传图片
                wx.uploadImage({
                    localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function (res) {
                        alert('上传成功！');
                        var serverId = res.serverId; // 返回图片的服务器端ID
                        // $("#faceImg").attr('src',localIds);
                        document.getElementById('faceImg').src=localIds;
                        /*图片预览*/
                        //  wx.previewImage({
                        // current: serverId, // 当前显示图片的http链接
                        // urls: [serverId] // 需要预览的图片http链接列表
                        //  });
                    },
                    cancel:function (res) {
                        alert('已取消');
                    }
                });
            },
            cancel:function (res) {
                alert('已取消');
            }
        });

    }
    //获取当前位置
    function getLocation(){
        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                wx.openLocation({
                    latitude: res.latitude, // 纬度，浮点数，范围为90 ~ -90
                    longitude: res.longitude, // 经度，浮点数，范围为180 ~ -180。
                    name: '', // 位置名
                    address: '', // 地址详情说明
                    scale: 13, // 地图缩放级别,整形值,范围从1~28。默认为最大
                    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
                });
            }
        });

    }
    //扫一扫
    function scanQR(){
        wx.scanQRCode({
            needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
            }
        });
    }
    wx.error(function(res){
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    });
</script>

<div><img id="faceImg" src="/myWorkspace/WeChat/Public/image/1.png" width="100%"></div>
<p><button onclick="uploadImage()">上传图片</button></p>
<p><button onclick="getLocation()">地理定位</button></p>
<p><button onclick="scanQR()">扫一扫</button></p>

</body>
</html>