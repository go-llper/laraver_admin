@include('wap.common.header')
  <body ontouchstart head="{{ env("APP_URL") }}">
  <header>
    <a href="/app"><i class="icon1"></i>返回</a>
    <h2 class="title">提交审核资料</h2>
    <span class="reff-icon"></span>
  </header>
  <span system="{{ $system }}" id="system"></span>
    <div class="weui-msg">
      <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
      <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">额度申请已提交</h2>
        <p class="weui-msg__desc">请耐心等待审核结果，结果会在1-3个工作日公布，请留意短信或打开APP查看</p>
      </div>
      <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
          <a href="/app" class="weui-btn weui-btn_primary">确定</a>
        </p>
      </div>

    </div>
    @include('wap.common.creditBottom')
  </body>
</html>
<script>
    var head = $('body').attr('head');
    var system = $('#system').attr('system');
    $(".reff-icon").click(function(){
        $(this).addClass("change");
        if(system == 'android'){
            window.android.refreshWeb(head+"app/credit/step/5");
        }else if(system == 'ios'){
            refreshWeb(head+"app/credit/step/5");
        }
    });
</script>