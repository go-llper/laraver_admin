@include('wap.common.header')
  <body ontouchstart  head="{{ env("APP_URL") }}">
  <header>
      <a href="/app"><i class="icon1"></i>返回</a>
      <h2 class="title">提交审核资料</h2>
      <span class="reff-icon" ></span>
  </header>
    <div class="weui-cells__title mt3em">请授权运单贷读取您的手机通讯录、短信、通话记录和位置信息：</div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary mb1em" href="javascript:" id="showTooltips">确认授权</a>
    </div>
    <div class="weui-cells__title">点击确认授权后，请在弹出的对话框中点击“允许”按钮。数据仅供信用审核用，运单贷不会泄露您的隐私数据</div>
  <p id="api"></p>
    <span system="{{ $system }}" id="system"></span>
    <div class="weui-btn-area">
        <a href="javascript:void(0)" id='show-error' class="weui-btn weui-btn_primary">下一步</a>
    </div>
    @include('wap.common.creditBottom')
    <script>
        var system = $('#system').attr('system');
        var callBackPhone = false;
        var callBackHistory = false;
        var callBackSms = false;
        var iosCallBackPhone = false;

        var head = $('body').attr('head');

        $(".reff-icon").click(function(){
            $(this).addClass("change");
            if(system == 'android'){
                window.android.refreshWeb(head+"app/credit/step/2");
            }else if(system == 'ios'){
                refreshWeb(head+"app/credit/step/2");
            }
        });

        $("#showTooltips").click(function(){
            $.showLoading("授权中...");

            if(system == 'android'){
                window.android.submitPhoneBook('phonebook');
            }else if(system == 'ios'){
                submitIos('phonebook');
            }
        })

        $("#show-error").click(function(){
            if($("#showTooltips").html() == '授权成功'){
                location.href='/app/credit/step/3';
            }else{
                $.alert('请先进行授权','提示');
            }
        })

        function returnPhoneBook(a){
            if(a != 1000){
                $.toptip('授权失败，请检查手机设置，然后点击重新授权', 'error');
                $(this).html('重新授权');
            }else{
                callBackPhone = true;
            }
        }

        function returnCallhistory(a){
            if(a != 1000){
                $.toptip('授权失败，请检查手机设置，然后点击重新授权', 'error');
                $(this).html('重新授权');
            }else{
                callBackHistory = true;
            }
        }

        function returnSms(a){
            if(a != 1000){
                $.toptip('授权失败，请检查手机设置，然后点击重新授权', 'error');
                $(this).html('重新授权');
            }else{
                callBackSms = true;
            }
        }

        function returnPhone(a){
            if(a.a != 1000){
                $.toptip('授权失败，请检查手机设置，然后点击重新授权', 'error');
                $(this).html('重新授权');
            }else{
                iosCallBackPhone = true;
            }
        }

        if(system == 'android'){
            if(callBackPhone && callBackHistory && callBackSms){
                $.hideLoading();
            }
        }else if(system == 'ios'){
            if(iosCallBackPhone){
                $.hideLoading();
            }
        }
    </script>
  </body>
</html>
