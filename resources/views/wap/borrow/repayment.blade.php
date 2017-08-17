@include('wap.common.header')
<body ontouchstart uid="{{ $id }}">
<header>
  <a href="#" onclick="history.go(-1)"><i class="icon1"></i>返回</a>
  <h2 class="title">提交审核资料</h2>
  <span class="reff-icon" ></span>
</header>
<span system="{{ $system }}" id="system"></span>
<div class="weui-cells">
  <div class="weui-cell">
    <div class="weui-cell__bd loan-1">
      <p>• 请先将应还款金额手工转入收款账户内，然后输入您转入时使用的还款账号，并单击我已确认还款。</p>
    </div>
  </div>
</div>
<div class="weui-cells">
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>应还款金额：</p>
    </div>
    <div class="weui-cell__ft">{{number_format($detail['repayment_amount'],2)}}元</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>收款账户：</p>
    </div>
    <div class="weui-cell__ft">{{$bank['bank_name']}}</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>账号：</p>
    </div>
    <div class="weui-cell__ft">{{$bank['card_no']}}</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>开户行</p>
    </div>
    <div class="weui-cell__ft">{{$bank['bank_branch']}}</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__hd">
      <label class="weui-label">还款账号:</label>
    </div>
    <div class="weui-cell__bd">
      <form name="repaymentFrom" id="repaymentFrom" method="post" action="/app/borrow/do_repayment">
      <input class="weui-input t-right" type="number" placeholder="请输入还款账号" name="borrower_bank">
      <input type="hidden" name="borrow_id" value="{{$detail['id']}}">
      <input type="hidden" name="amount" value="{{$detail['repayment_amount']}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      </form>
    </div>
  </div>

</div>

<div class="weui-btn-area mb1em">
  <a class="weui-btn weui-btn_primary" href="javascript:" id="repayment">我已确认还款</a>
</div>

<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script src="{{assetByCdn('/wap/js/fastclick.js')}}"></script>
<script src="{{assetByCdn('/wap/js/city-picker.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery-weui.js')}}"></script>
<script src="{{assetByCdn('/wap/js/codecheck.js')}}"></script>
<script>
  $(function() {
    FastClick.attach(document.body);
  });
</script>

<script type="text/javascript">
  $(function(){
    $("#repayment").on('click',function(){
      var ret = $.empty_check($("input[name='borrower_bank']"));
      if(ret){
         $('#repaymentFrom').submit();
      }
    });

    var head = $('body').attr('head');
    var uid  = $('body').attr('uid');
    var system = $('#system').attr('system');
    $(".reff-icon").click(function(){
      $(this).addClass("change");
      if(system == 'android'){
        window.android.refreshWeb(head+"borrow/repayment/{id}"+uid);
      }else if(system == 'ios'){
        refreshWeb(head+"borrow/repayment/"+uid);
      }
    });

  });
</script>
</body>
</html>
