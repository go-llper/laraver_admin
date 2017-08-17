@include('wap.common.header')
<body ontouchstart>
<header>
  <a href="/app"><i class="icon1"></i>返回</a>
  <h2 class="title">注册</h2>
</header>
  <input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="username" id="phone" pattern="[0-9]*" placeholder="请输入您的手机号">
    </div>
  </div>
  <div class="weui-cell weui-cell_vcode">
    <div class="weui-cell__hd">
      <label class="weui-label">验证码</label>
    </div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" placeholder="短信验证码" name="code" id="code">
    </div>
    <div class="weui-cell__ft">
      <button class="weui-vcode-btn"  id="sendCode">获取验证码</button>
    </div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">登录密码</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="password" name="password" placeholder="6-16位字母数字组合" id="pwd">
    </div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">确认密码</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="password" name="secondpwd"  placeholder="确认密码" id="pwd1">
    </div>
  </div>
</div>
<div class="reg_1">
  <label for="weuiAgree" class="weui-agree apply-1">
    <input id="weuiAgree" type="checkbox" name="agreement" class="weui-agree__checkbox">
        <span class="weui-agree__text">
          同意
        </span>
  </label>
  <a href="/app/register_deal">《用户帐户注册及使用协议》</a>
</div>
<div class="weui-btn-area">
  <input type="button" class="weui-btn weui-btn_primary" id="showTooltips" value="立即注册">
</div>

<a href="/app/login" class="weui-agree login-1">直接登录</a>
</body>
@include('wap.common.bottom')
<script type="text/javascript" src="{{assetByCdn('/wap/js/common.js')}}"></script>
<script>

  $(function() {
    FastClick.attach(document.body);

    var myInter = null;

    function succSend(ret){
      $.hideLoading();
      if(ret.status) {
        $.alert(ret.msg, "提示");
        $('#sendCode').addClass('gray').unbind().html('验证码已发送');
        var timeOut = 30;
        myInter = setInterval(function(){
          $('#sendCode').html('请'+timeOut+'秒后再试');
          timeOut = timeOut-1;
          if(timeOut==0){
            window.clearInterval(myInter);
            $('#sendCode').removeClass('gray').bind('click',function(){
              $.showLoading("验证码发送中...");
              $.ajax({
                url : '/app/register/send_verify',
                type: 'POST',
                dataType: 'json',
                data: {'phone':$("input[name=username]").val()},
                success : succSend
              });
            }).html('获取验证码');
          }
        },1000);
      } else {
        $.alert(ret.msg, "提示");
      }
    }

    $("#sendCode").click(function(){
      var ret = $.phone_check($("input[name=username]"));
      if(ret){
        $.showLoading("验证码发送中...");
        $.ajax({
          url : '/app/register/send_verify',
          type: 'POST',
          dataType: 'json',
          data: {'phone':$("input[name=username]").val()},
          success : succSend
        });
      }
    });

    $("#showTooltips").click(function(){
      var ret = $.phone_check($("input[name=username]"));
      if(ret){
        var ret = $.captcha_check($("#code"));
        if(ret){
          var ret = $.pwd_check($("#pwd"),$("#pwd1"));
          if(ret){
            var ret = $("input[name=agreement]").is(':checked');
            if(ret){
              $.showLoading("注册中...");
              var postData = {
                'username':$("input[name=username]").val(),
                'code':$("input[name=code]").val(),
                'password':$("input[name=password]").val(),
                'agreement':$("input[name=agreement]").val(),
                '_token':$("input[name='token']").val(),
              };

              $.ajax({
                url : '/app/do_register',
                type: 'POST',
                dataType: 'json',
                data: postData,
                success : function(ret) {
                  $.hideLoading();
                  if(ret.status) {
                    location.href='/app';
                  } else {
                    $.alert(ret.msg, "提示");
                  }
                }
              });
            }else{
              $.alert('请确认阅读《用户帐户注册及使用协议》',' 提示');
            }
          }
        }
      }
    })
  });
</script>