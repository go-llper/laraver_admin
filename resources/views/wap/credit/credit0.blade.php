@include('wap.common.header')
<body ontouchstart head="{{ env("APP_URL") }}">
<header>
  <a href="/app"><i class="icon1"></i>返回</a>
  <h2 class="title">提交审核资料</h2>
  <span class="reff-icon"></span>
</header>
<span system="{{ $system }}" id="system"></span>
<h2 class="demos-second-title credit-title">请输入授信审核信息：</h2>

<form action="/app/do_credit/step/1" method="post">
  <input type="hidden" name="token" value="{{csrf_token()}}">
<div class="weui-cells weui-cells_form">

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="real_name" placeholder="请输入姓名" value="{{ old('real_name') }}">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">身份证号码</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="id_card" placeholder="请输入身份证号码">
    </div>
  </div>

  <div class="weui-cell weui-cell_select">
    <div class="weui-cell__hd ml15px"><label class="weui-label">文化程度</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select pl0px" name="cul_level"  placeholder="请选择文化程度">
        <option selected="" value="">请选择</option>
        @foreach($culData as $key=>$value)
        <option value="{{$key}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="weui-cell weui-cell_select">
    <div class="weui-cell__hd ml15px"><label class="weui-label">婚姻状况</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select pl0px" name="marital_status" placeholder="请选择婚姻状况">
        <option selected="" value="">请选择</option>
        @foreach($marital as $key=>$value)
          <option value="{{$key}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>


  <div class="weui-cell">
    <div class="weui-cell__hd"><label for="name" class="weui-label">居住地址</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" name="home_address" id="start" type="text" value="北京 北京市 东城区">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="home_address_ext" placeholder="请填写详细居住地址">
    </div>
  </div>

  <div class="weui-cell weui-cell_select">
    <div class="weui-cell__hd ml15px"><label class="weui-label">从业年限</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select pl0px" name="service_years" placeholder="请选择从业年限">
        <option selected="" value="">请选择</option>
        @foreach($serviceYears as $key=>$value)
          <option value="{{$key}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>

</div>
<div class="weui-cells weui-cells_form">

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">公司名称</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="company_name" placeholder="请输入公司名称">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label for="date" class="weui-label">成立时间</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="date" type="text" placeholder="请填写成立时间" name="company_start">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label for="date" class="weui-label">注册地址</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="end" type="text" name="company_address" value="北京 北京市 东城区">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="company_address_ext" placeholder="请填写详细注册地址">
    </div>
  </div>

  <div class="weui-cell weui-cell_select">
    <div class="weui-cell__hd ml15px"><label for="date" class="weui-label">主营业务</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select  pl0px" name="main_biz"  placeholder="请选择主营业务">
        <option selected="" value="">请选择</option>
        @foreach($mainBiz as $key=>$value)
          <option value="{{$key}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label for="home-city" class="weui-label">经营地址</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="home-city" type="text" name="biz_address" value="北京 北京市">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__bd">
      <input class="weui-input" type="text" name="biz_address_ext" placeholder="请填写详细经营地址">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">雇员人数</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="employee_number" placeholder="请输入雇员人数">
    </div>
  </div>

  <div class="weui-cell weui-cell_select">
    <div class="weui-cell__hd ml15px"><label for="date" class="weui-label">年收入</label></div>
    <div class="weui-cell__bd">
      <select class="weui-select pl0px" name="money_total" placeholder="请填写年收入(万元)">
        <option selected="" value="">请选择(万元)</option>
        @foreach($profitTotal as $key=>$value)
          <option value="{{$key}}">{{$value}}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">年净利润</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="profit_total" placeholder="请输入年净利润（万元）">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">挂靠车辆数量</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="anchored_car" placeholder="请输入挂靠车辆数量">
    </div>
  </div>

  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">自有车辆数量</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" type="number" name="own_car" placeholder="请输入自有车辆数量">
    </div>
  </div>
<span id="span" errormsg="{{ Session::get('msg') }}"></span>
</div>

<div class="weui-btn-area">
  <input type="button" value="下一步" class="weui-btn weui-btn_primary mb1em" id="showTooltips">
</div>
</form>
<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script src="{{assetByCdn('/wap/js/fastclick.js')}}"></script>
<script src="{{assetByCdn('/wap/js/city-picker.js')}}"></script>
<script src="{{assetByCdn('/wap/js/common.js')}}"></script>
<script src="{{assetByCdn('/wap/js/codecheck.js')}}"></script>
<script>
  $(function() {
    FastClick.attach(document.body);
  });
</script>
<script src="{{assetByCdn('/wap/js/jquery-weui.js')}}"></script>

<script>
  $(function() {

    var errormsg = $('#span').attr('errormsg');

    if(errormsg){
      $.alert(errormsg, "提 示");
    }

    FastClick.attach(document.body);

  });

    $(document).on("click", "#showTooltips", function() {

      var ret = $.china_check($("input[name='real_name']"));
      if(ret){
        var ret = $.identity_check($("input[name='id_card']"));
        if(ret){
          var ret = $.empty_check($("select[name='cul_level']"));
          if(ret){
            var ret = $.empty_check($("select[name='marital_status']"));
            if(ret){
              var ret = $.empty_check($("input[name='home_address_ext']"));
              if(ret){
                var ret = $.empty_check($("select[name='service_years']"));
                if(ret){
                  var ret = $.empty_check($("input[name='company_name']"));
                  if(ret){
                    var ret = $.empty_check($("input[name='company_start']"));
                    if(ret){
                      var ret = $.empty_check($("input[name='company_address_ext']"));
                      if(ret){
                        var ret = $.empty_check($("select[name='main_biz']"));
                        if(ret){
                          var ret = $.empty_check($("input[name='biz_address_ext']"));
                          if(ret){
                            var ret = $.num_check($("input[name='employee_number']"),'请输入雇员人数','数字不能以0开头');
                            if(ret){
                              var ret = $.empty_check($("select[name='money_total']"));
                              if(ret){
                                var ret = $.num_check($("input[name='profit_total']"),'请输入年净利润（万元）','数字不能以0开头');
                                if(ret){
                                  var ret = $.num_check($("input[name='anchored_car']"),'请输入挂靠车辆数量','数字不能以0开头');
                                  if(ret){
                                    var ret = $.num_check($("input[name='own_car']"),'请输入自有车辆数量','数字不能以0开头');
                                    if(ret){
                                      $.showLoading("审核信息提交中...");
                                      var postData = {
                                        'real_name':$("input[name='real_name']").val(),
                                        'id_card':$("input[name='id_card']").val(),
                                        'cul_level':$("select[name='cul_level']").val(),
                                        'marital_status':$("select[name='marital_status']").val(),
                                        'home_address':$("input[name='home_address']").val(),
                                        'home_address_ext':$("input[name='home_address_ext']").val(),
                                        'service_years':$("select[name='service_years']").val(),
                                        'company_name':$("input[name='company_name']").val(),
                                        'company_start':$("input[name='company_start']").val(),
                                        'company_address':$("input[name='company_address']").val(),
                                        'company_address_ext':$("input[name='company_address_ext']").val(),
                                        'main_biz':$("select[name='main_biz']").val(),
                                        'biz_address':$("input[name='biz_address']").val(),
                                        'biz_address_ext':$("input[name='biz_address_ext']").val(),
                                        'employee_number':$("input[name='employee_number']").val(),
                                        'money_total':$("select[name='money_total']").val(),
                                        'profit_total':$("input[name='profit_total']").val(),
                                        'anchored_car':$("input[name='anchored_car']").val(),
                                        'own_car':$("input[name='own_car']").val(),
                                        '_token':$("input[name='token']").val(),
                                      };

                                      $.ajax({
                                        url : '/app/do_credit/step/1',
                                        type: 'POST',
                                        dataType: 'json',
                                        data: postData,
                                        success : function(ret) {
                                          $.hideLoading();
                                          if(ret.status) {
                                            $.alert({
                                              title: '提示',
                                              text: '授信信息提交成功',
                                              onOK: function () {
                                                location.href='/app/credit/step/2';
                                              }
                                            });
                                          } else {
                                            $.alert(ret.msg, "提示");
                                          }
                                        }
                                      });
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }



    });
  var head = $('body').attr('head');
  var system = $('#system').attr('system');
  $(".reff-icon").click(function(){
    $(this).addClass("change");
    if(system == 'android'){
      window.android.refreshWeb(head+"app/credit/step/1");
    }else if(system == 'ios'){
      refreshWeb(head+"app/credit/step/1");
    }
  });

  $("#start").cityPicker({
    title: "居住地址",
    onChange: function (picker, values, displayValues) {
      console.log(values, displayValues);
    }
  });
  $("#end").cityPicker({
    title: "注册地址"
  });

  $("#home-city").cityPicker({
    title: "经营地址",
    showDistrict: false,
    onChange: function (picker, values, displayValues) {
      console.log(values, displayValues);
    }
  });

  $("#date").calendar({
    onChange: function (p, values, displayValues) {
      console.log(values, displayValues);
    }
  });

</script>
</body>
</html>
