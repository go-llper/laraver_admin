@include('wap.common.header')

  <body ontouchstart  head="{{ env("APP_URL") }}">
  <header>
      <a href="/app"><i class="icon1"></i>返回</a>
      <h2 class="title">提交审核资料</h2>
      <span class="reff-icon"></span>
  </header>
  <span system="{{ $system }}" id="system"></span>
   <h2 class="demos-second-title credit-title">请输入联系人信息：</h2>
     <div class="weui-cells weui-cells_form">
       <div class="weui-cell">
          <div class="weui-cell__hd"><label class="weui-label">关系</label></div>
          <div class="weui-cell__bd">
           直系亲属
          </div>
        </div>
        <div class="weui-cell">
          <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
          <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="family_name" placeholder="请输入姓名">
          </div>
        </div>
        <div class="weui-cell">
          <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
          <div class="weui-cell__bd">
            <input class="weui-input" type="number" name="family_tel" placeholder="请输入联系电话">
          </div>
        </div>
      </div>

  @if( $marital_status == \App\Models\Credit\CreditModel::MARRY_YES )
       <div class="weui-cells weui-cells_form" id="married" marry="yes">
         <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">关系</label></div>
            <div class="weui-cell__bd">
             配偶
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="text" name="marital_name" placeholder="请输入姓名">
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="number" name="marital_tel" placeholder="请输入联系电话">
            </div>
          </div>
        </div>
  @endif

        <div class="weui-cells weui-cells_form">
         <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">关系</label></div>
            <div class="weui-cell__bd">
             同事
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="text" name="workmate_name" placeholder="请输入姓名">
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="number" name="workmate_tel" placeholder="请输入联系电话">
            </div>
          </div>
        </div>

        <div class="weui-cells weui-cells_form">
         <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">关系</label></div>
            <div class="weui-cell__bd">
             朋友
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="text" name="friend_name" placeholder="请输入姓名">
            </div>
          </div>
          <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
            <div class="weui-cell__bd">
              <input class="weui-input" type="number" name="friend_tel" placeholder="请输入联系电话">
            </div>
          </div>
        </div>
     <input type="hidden" name="token" value="{{csrf_token()}}">
     <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary mb1em" href="javascript:void(0)" id="showTooltips">下一步</a>
      </div>

   @include('wap.common.creditBottom')

    <script>
      $(document).on("click", "#showTooltips", function() {
//        $.alert("请输入有效的手机号码！", "提示");
          var ret1 = $.china_check($("input[name='family_name']"));
          var ret2 = $.phone_check($("input[name='family_tel']"));
          var ret3 = $.china_check($("input[name='workmate_name']"));
          var ret4 = $.phone_check($("input[name='workmate_tel']"));
          var ret5 = $.china_check($("input[name='friend_name']"));
          var ret6 = $.phone_check($("input[name='friend_tel']"));
          if(ret1 && ret2 && ret3 && ret4 && ret5 && ret6){
              var check = true;
              var postData = {
                  'family_name':$("input[name='family_name']").val(),
                  'family_tel':$("input[name='family_tel']").val(),
                  'workmate_name':$("input[name='workmate_name']").val(),
                  'workmate_tel':$("input[name='workmate_tel']").val(),
                  'friend_name':$("input[name='friend_name']").val(),
                  'friend_tel':$("input[name='friend_tel']").val(),
                  '_token':$("input[name='token']").val(),
              }
          }else{
              var check = false;
          }
          if($("#married").attr('marry')){
              var ret7 = $.china_check($("input[name='marital_name']"));
              var ret8 = $.phone_check($("input[name='marital_tel']"));
              if(check && ret7 && ret8){
                  var check = true;
                  var postData = {
                    'family_name':$("input[name='family_name']").val(),
                    'family_tel':$("input[name='family_tel']").val(),
                    'workmate_name':$("input[name='workmate_name']").val(),
                    'workmate_tel':$("input[name='workmate_tel']").val(),
                    'friend_name':$("input[name='friend_name']").val(),
                    'friend_tel':$("input[name='friend_tel']").val(),
                    'marital_name':$("input[name='marital_name']").val(),
                    'marital_tel':$("input[name='marital_tel']").val(),
                    '_token':$("input[name='token']").val(),
                  }
              }else{
                  var check = false;
              }
          }

          if(check){
              $.ajax({
                  url : '/app/do_credit/step/3',
                  type: 'POST',
                  dataType: 'json',
                  data: postData,
                  success : function(ret) {
                      if(ret.status) {
                          location.href='/app/credit/step/4';
                      } else {
                          $.alert("信息提交失败,请重试!", "提示");
                      }
                  }
              });
          }

      });

      var head = $('body').attr('head');
      var system = $('#system').attr('system');
      $(".reff-icon").click(function(){
          $(this).addClass("change");
          if(system == 'android'){
              window.android.refreshWeb(head+"app/credit/step/3");
          }else if(system == 'ios'){
              refreshWeb(head+"app/credit/step/3");
          }
      });
    </script>
  </body>
</html>
