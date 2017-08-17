@include('wap.common.header')
<body ontouchstart data-bind-card="{{$bind_card}}" head="{{ env("APP_URL") }}">
<header>
    <a href="/app#tab3"><i class="icon1"></i>返回</a>
    <h2 class="title">绑定银行卡</h2>
    <span class="reff-icon" ></span>
</header>
<span system="{{ $system }}" id="system"></span>
<div class="weui-cells weui-cells_form">

  <div class="weui-cell weui-cell_select">
      <div class="weui-cell__hd ml15px"><label for="date" class="weui-label">借记卡银行</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select pl0px" name="bankName">
        @foreach($bank_list as $key => $bank)
          <option value="{{$key}}">{{$bank['name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd">
      <label class="weui-label">借记卡卡号</label>
    </div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number"  placeholder="请输入您本人的借记卡卡号" name="bankNo">
    </div>
  </div>
  <div class="weui-cell weui-cell_vcode">
    <div class="weui-cell__hd">
      <label class="weui-label">验证码</label>
    </div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" placeholder="请输入短信验证码" name="verifyCode">
    </div>
    <div class="weui-cell__ft">
      <button class="weui-vcode-btn" id="sendCode" data-token="{{$sms_token}}">获取验证码</button>
    </div>
  </div>
</div>
<input type="hidden" name="token" value="{{csrf_token()}}">
<div class="weui-btn-area">
  <a class="weui-btn weui-btn_primary mb1em" href="javascript:" id="nextStep">下一步</a>
</div>

@include('wap.common.creditBottom')


<script type="text/javascript">
  $(function(){
    var head = $('body').attr('head');

      var system = $('#system').attr('system');
      $(".reff-icon").click(function(){
          $(this).addClass("change");
          if(system == 'android'){
              window.android.refreshWeb(head+"app/bind/bankcard");
          }else if(system == 'ios'){
              refreshWeb(head+"app/bind/bankcard");
          }
      });

    var is_bind = $('body').attr('data-bind-card');
    if(is_bind==1){
      $.alert({
        title: '提 示',
        text: '您已经绑定银行卡',
        onOK: function () {
          location.href='/app/borrow/apply';
        }
      });
    }

    var myInter = null;

    function succSend(ret){
        $.hideLoading();
        if(ret.code==1) {
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
                            url : '/app/bind/get_verify',
                            type: 'POST',
                            dataType: 'json',
                            data: {'token':$('#sendCode').attr('data-token')},
                            success : succSend
                        });
                    }).html('获取验证码');
                }
            },1000);
        } else {
            $.alert(ret.msg, "提示");
        }
    }

    $('#sendCode').on('click',function(){
        $.showLoading("验证码发送中...");
        $.ajax({
            url : '/app/bind/get_verify',
            type: 'POST',
            dataType: 'json',
            data: {'token':$('#sendCode').attr('data-token')},
            success : succSend
        });
    });

    $('#nextStep').on('click',function(){
        var ret = $.empty_check($("input[name='bankNo']"));
        if(ret){
            ret =  $.empty_check($("input[name='verifyCode']"));
            if(ret){
                var postData = {
                    'bankName':$("select[name='bankName']").val(),
                    'bankNo':$("input[name='bankNo']").val(),
                    'verifyCode':$("input[name='verifyCode']").val(),
                    '_token':$("input[name='token']").val(),
                };
                //console.log(postData);
                $.showLoading("请求提交中...");
                $('#nextStep').addClass('disable');
                $.ajax({
                    url : '/app/bind/do_bankcard',
                    type: 'POST',
                    dataType: 'json',
                    data: postData,
                    success : function(ret) {
                        $('#nextStep').removeClass('disable');
                        if(ret.code==1) {
                            $.alert({
                                title: '提示',
                                text: '银行卡绑定成功',
                                onOK: function () {
                                    location.href='/app';
                                }
                            });
                        } else {
                            var msg = '绑定银行卡失败,请重试!';
                            if(ret.msg){
                                msg = ret.msg;
                            }
                            $.hideLoading();
                            $.alert(msg, "提示");
                            if(ret.code==2){
                                if(myInter==null){
                                    $('#sendCode').unbind();
                                    setTimeout(function(){
                                        $('#sendCode').removeClass('gray').bind('click',function(){
                                            $.showLoading("验证码发送中...");
                                            $.ajax({
                                                url : '/app/bind/get_verify',
                                                type: 'POST',
                                                dataType: 'json',
                                                data: {'token':$('#sendCode').attr('data-token')},
                                                success : succSend
                                            });
                                        }).html('获取验证码');
                                    },1000);
                                }
                            }
                        }
                    }
                });
            }
        }
    });

  });


</script>
</body>
</html>
