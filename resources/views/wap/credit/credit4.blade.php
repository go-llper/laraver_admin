@include('wap.common.header')
<body ontouchstart  head="{{ env('APP_URL') }}">
<header>
    <a href="/app"><i class="icon1"></i>返回</a>
    <h2 class="title">提交审核资料</h2>
    <span class="reff-icon" ></span>
</header>
<h2 class="demos-second-title credit-title">请上传如下证件的照片：</h2>
<span system="{{ $system }}" id="system"></span>
<form method="post" enctype="multipart/form-data">
<div class="weui-cells  h4em">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>营业执照</p>
        </div>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="business" value="">
        </div>
    </div>
</div>

<div class="weui-cells  h4em">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>运输许可证</p>
        </div>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="transport" value="">
        </div>
    </div>
</div>

<div class="weui-cells  h4em">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>结婚证</p>
        </div>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="marry" value="">
        </div>
    </div>
</div>

<div class="weui-cells  h4em">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>行驶证</p>
        </div>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="driver" value="">
        </div>
    </div>
    {{--<div class="weui-cell">--}}
        {{--<a href="#" class="add_1">+点击新增</a>--}}
    {{--</div>--}}
</div>

<div class="weui-cells h4em">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-封面</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb1">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="houseCover" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-发证机关页</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb2">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="houseSign" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-房屋信息页</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb3">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="houseInfo" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-平面图页</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb4">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="housePic" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-权利摘要页</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb5">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="houseTitle" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p>房产证-注意事项页</p>
        </div>
        <a href="javascript:void(0)" class="img_pb pb6">样例</a>
        <div class="weui-uploader__input-box">
            <img src="" class="img_src1">
            <input type="hidden" name="houseTip" value="">
        </div>
    </div>

    {{--<div class="weui-cell">--}}
        {{--<a href="#" class="add_1">+点击新增</a>--}}
    {{--</div>--}}
</div>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
</form>

<div class="weui-btn-area">
    <a class="weui-btn weui-btn_primary mb1em" href="javascript:" id="showTooltips">提交审核</a>
</div>


<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script src="{{assetByCdn('/wap/js/fastclick.js')}}"></script>
<script src="{{assetByCdn('/wap/js/city-picker.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery-weui.js')}}"></script>
<script src="{{assetByCdn('/wap/js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery.iframe-transport.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery.fileupload.js')}}"></script>
<script src="{{assetByCdn('/wap/js/swiper.js')}}"></script>
<script src="{{assetByCdn('/wap/js/common.js')}}"></script>

<script>
    $(function() {
        FastClick.attach(document.body);

        var head = $('body').attr('head');
        var system = $('#system').attr('system');

        $(".reff-icon").click(function(){
            $(this).addClass("change");
            if(system == 'android'){
                window.android.refreshWeb(head+"app/credit/step/4");
            }else if(system == 'ios'){
                refreshWeb(head+"app/credit/step/4");
            }
        });

        $(".weui-uploader__input-box").click(function(){
            var type = $(this).find('input').attr('name');
            if(system == 'android'){
                window.android.uploadPic(type);
            }else if(system == 'ios'){
                uploadPic(type);
            }
        });

        $("#showTooltips").click(function(){
            var ret1  = $.value_check($("input[name='business']"));
            var ret2  = $.value_check($("input[name='transport']"));
            var ret3  = $.value_check($("input[name='marry']"));
            var ret4  = $.value_check($("input[name='driver']"));
            var ret5  = $.value_check($("input[name='houseCover']"));
            var ret6  = $.value_check($("input[name='houseSign']"));
            var ret7  = $.value_check($("input[name='houseInfo']"));
            var ret8  = $.value_check($("input[name='housePic']"));
            var ret9  = $.value_check($("input[name='houseTitle']"));
            var ret10 = $.value_check($("input[name='houseTip']"));
            if(ret1 && ret2 && ret3 && ret4 && ret5 && ret6 && ret7 && ret8 && ret9 && ret10){
                var postData = {
                    'business':$("input[name='business']").val(),
                    'transport':$("input[name='transport']").val(),
                    'marry':$("input[name='marry']").val(),
                    'driver':$("input[name='driver']").val(),
                    'houseCover':$("input[name='houseCover']").val(),
                    'houseSign':$("input[name='houseSign']").val(),
                    'houseInfo':$("input[name='houseInfo']").val(),
                    'housePic':$("input[name='housePic']").val(),
                    'houseTitle':$("input[name='houseTitle']").val(),
                    'houseTip':$("input[name='houseTip']").val(),
                };
                $.ajax({
                    url : '/app/do_credit/step/save_photo',
                    type: 'POST',
                    dataType: 'json',
                    data: postData,
                    success : function(ret) {
                        if(ret.status) {
                            location.href = '/app/credit/step/5'
                        } else {
                            $.alert("信息提交失败,请重试!", "提示");
                        }
                    }
                });
            }else{
                $.alert("请确认所有图片上传成功!", "提示");
            }

        })

    });

    function backPic(result){
        $.hideLoading();
        if(result){
            $("input[name="+result.type+"]").val(result.url);
            $("input[name="+result.type+"]").prev().attr('src',result.url);
        }else{
            $("input[name=business]").parent().parent().append("<ul class='weui-uploader__files'><li class='weui-uploader__file weui-uploader__file_status mlr0px'><div class='weui-uploader__file-content'><i class='weui-icon-warn'></i></div></li></ul>")
            $("input[name=business]").parent().remove();
        }
    }

    function picUploading(result){
        $.showLoading("图片上传中...");
    }
        var data1=["{{assetByCdn('/wap/images/swiper-3.jpg')}}"];
        var data2=["{{assetByCdn('/wap/images/swiper-1.jpg')}}"];
        var data3=["{{assetByCdn('/wap/images/swiper-2.jpg')}}"];
        var data4=["{{assetByCdn('/wap/images/swiper-4.jpg')}}"];
        var data5=["{{assetByCdn('/wap/images/swiper-5.jpg')}}"];
        var data6=["{{assetByCdn('/wap/images/swiper-6.jpg')}}"];

        var pb1 = $.photoBrowser({
            items: data1
        });
        var pb2 = $.photoBrowser({
            items: data2
        });
        var pb3 = $.photoBrowser({
            items: data3
        });
        var pb4 = $.photoBrowser({
            items: data4
        });
        var pb5 = $.photoBrowser({
            items: data5
        });
        var pb6 = $.photoBrowser({
            items: data6
        });

        $(".pb1").click(function() {
            pb1.open();
        });
        $(".pb2").click(function() {
            pb2.open();
        });
        $(".pb3").click(function() {
            pb3.open();
        });
        $(".pb4").click(function() {
            pb4.open();
        });
        $(".pb5").click(function() {
            pb5.open();
        });
        $(".pb6").click(function() {
            pb6.open();
        });



</script>
</body>
</html>

