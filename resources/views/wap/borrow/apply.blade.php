@include('wap.common.header')
<body ontouchstart data-bind-card="{{$bind_card}}" head="{{ env("APP_URL") }}">
<header>
  <a href="/app#tab2"><i class="icon1"></i>返回</a>
  <h2 class="title">申请借款</h2>
  <span class="reff-icon" ></span>
</header>
<h2 class="demos-second-title credit-title">请输入申请借款信息：</h2>
<form name="applyForm" id="applyForm" method="post" action="/app/borrow/do_apply" enctype="multipart/form-data">
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">借款金额</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="borrow_amount" placeholder="请输入借款金额" >
    </div>
  </div>
</div>
<div class="weui-cells__title mb1em">为提高您的信用等级，请按实际运费填写借款金额，金额在2000-20000之间，100的整数倍 ，借款最长期限为60天。</div>
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">
        运送物品</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="log_goods" placeholder="请输入运送物品">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label for="date" class="weui-label">运送起始地点</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="point" type="text"  name="log_point" value="北京 北京市 朝阳区">
    </div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">
        运费</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number"  name="log_price" placeholder="请输入运费">
    </div>
  </div>

</div>
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">司机姓名</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="driver_name" placeholder="请输入司机姓名">
    </div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">联系电话
      </label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number"  name="driver_phone"  placeholder="请输入司机联系电话">
    </div>
  </div>
</div>
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">车辆车牌号</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="truck_no" placeholder="请输入运输车辆车牌号">
    </div>
  </div>
  <div class="weui-cells  h4em">
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>上传行驶证照片</p>
      </div>
      <div class="weui-uploader__input-box">
        <img src="" class="img_src1">
        <input type="hidden" name="truck_image" value=""  placeholder="请上传行驶证照片">
      </div>
    </div>
  </div>
</div>
  <span system="{{ $system }}" id="system"></span>
<input type="hidden" name="_token" value="{{csrf_token()}}">
</form>
<div class="reg_1">
  <label for="weuiAgree" class="weui-agree apply-1">
    <input id="weuiAgree" type="checkbox" name="agreement" class="weui-agree__checkbox">
        <span class="weui-agree__text">
          同意
        </span>
  </label>
  <a href="/app/loan_deal">《借款协议》</a> <a href="/app/server_deal">《借款服务协议》</a>
</div>
<div class="weui-btn-area">
  <a class="weui-btn weui-btn_primary mb1em" href="javascript:" id="submitApply">提交借款申请</a>
</div>
@include('wap.common.creditBottom')
<script src="{{assetByCdn('/wap/js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery.iframe-transport.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery.fileupload.js')}}"></script>
<script>
  $(function(){

    var system = $('#system').attr('system');
    $(".weui-uploader__input-box").click(function(){
      var type = $(this).find('input').attr('name');
      if(system == 'android'){
        window.android.uploadPic(type);
      }else if(system == 'ios'){
        uploadPic(type);
      }
    });

    var head = $('body').attr('head');

    $(".reff-icon").click(function(){
      $(this).addClass("change");
      if(system == 'android'){
        window.android.refreshWeb(head+"app/borrow/apply");
      }else if(system == 'ios'){
        refreshWeb(head+"app/borrow/apply");
      }
    });

    var is_bind = $('body').attr('data-bind-card');
    if(is_bind==0){
      $.alert({
        title: '提 示',
        text: '请先绑定银行卡',
        onOK: function () {
          location.href='/app/bind/bankcard/';
        }
      });
    }
    $("#point").cityPicker({
      title: "货运起始地址",
      showDistrict: true,
      onChange: function (picker, values, displayValues) {
        //console.log(values, displayValues);
      }
    });

    $('#submitApply').on('click',function(){
      if($("input[name='borrow_amount']").val()>=2000 && $("input[name='borrow_amount']").val()<=20000){
        ret = $.n100_check($("input[name='borrow_amount']"),"借款金额不能为空","借款金额为100的倍数");
        if(ret){
          ret = $.empty_check($("input[name='log_goods']"));
          if(ret){
            ret = $.empty_check($("input[name='log_price']"));
            if(ret){
              ret = $.empty_check($("input[name='driver_name']"));
              if(ret){
                ret = $.empty_check($("input[name='driver_phone']"));
                if(ret){
                  ret = $.empty_check($("input[name='truck_no']"));
                  if(ret){
                    ret = $.empty_check($("input[name='truck_image']"));
                    if(ret){
                      ret = $("input[name=agreement]").is(':checked');
                      if(ret){
                        console.log('All data is ok');
                        $('#applyForm').submit();
                      }else{
                        $.alert('请确认阅读《借款协议》和《借款服务协议》',' 提示');
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }else{
        $.alert({
          title: '提示',
          text: '借款金额在2000~20000之间',
          onOK: function () {
            $("input[name='borrow_amount']").val(2000).focus();
          }
        });
      }
    });

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

</script>
</body>
</html>
