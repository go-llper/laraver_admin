@include('wap.common.header')
<body ontouchstart>
<header>
    <a href="/app"><i class="icon1"></i>返回</a>
    <h2 class="title">登录</h2>
</header>
<form action="/app/do_login" method="post" id="form">
    <input type="hidden" name="token" value="{{csrf_token()}}">
<div class="weui-cells weui-cells_form">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="tel" pattern="[0-9]*" placeholder="请输入您的手机号"  name="username" id="phone">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">登录密码</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="password"  placeholder="6-16位字母数字组合" name="password" id="pwd">
        </div>
    </div>
</div>
    <div class="weui-btn-area">
        <input type="button" class="weui-btn weui-btn_primary" id="showTooltips" value="登录">
    </div>
</form>
<a href="/app/register" class="weui-agree login-1">没有账号，立即注册</a>
</body>
@include('wap.common.bottom')
<script>

    $(function() {
        FastClick.attach(document.body);


        $("#showTooltips").click(function(){
            var ret = $.phone_check($("input[name=username]"));
            if(ret){
                var ret = $.pwd_check($("input[name=password]"));
                if(ret){
                    $.showLoading("登录中...");
                    var postData = {
                        'username':$("input[name=username]").val(),
                        'password':$("input[name=password]").val(),
                        '_token':$("input[name='token']").val(),
                    };

                    $.ajax({
                        url : '/app/do_login',
                        type: 'POST',
                        dataType: 'json',
                        data: postData,
                        success : function(ret) {
                            $.hideLoading();
                            if(ret.status) {
                                location.href='/app';
                            } else {
                                $.alert("用户名或密码错误,请重新登录", "提示");
                            }
                        }
                    });
                }
            }
        })
    });
</script>