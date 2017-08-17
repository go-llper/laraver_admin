@include('wap.common.header')
<body ontouchstart head="{{ env('APP_URL') }}" uid="{{ $id }}">
<header>
  <a href="/app#tab2" ><i class="icon1"></i>返回</a>
  <h2 class="title">{{$status[$detail['borrow_status']]['status']}}</h2>
  <span class="reff-icon" ></span>
</header>
<span system="{{ $system }}" id="system"></span>
<h2 class="demos-second-title credit-title">{{$status[$detail['borrow_status']]['status']}}</h2>
<div class="weui-cells">
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>借款金额：</p>
    </div>
    <div class="weui-cell__ft">{{$detail['borrow_amount']}}元</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>实际放款金额：</p>
    </div>
    <div class="weui-cell__ft">{{$detail['borrow_real_amount']}}元  @if($detail['borrow_real_amount'] && ($detail['borrow_real_amount']<$detail['borrow_amount']))收取手续费{{$detail['borrow_amount']-$detail['borrow_real_amount']}}元@endif</div>
  </div>
  <div class="weui-cell">
    <div class="weui-cell__bd">
      <p>收款银行卡：</p>
    </div>
    <div class="weui-cell__ft">{{$bank[$detail['borrow_card_bank']]['name']}} {{$detail['borrow_card_no']}}</div>
  </div>
  @if($detail['borrow_status']==0)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>申请日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['borrow_time'])}}</div>
    </div>
  @elseif($detail['borrow_status']==1)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>申请日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['borrow_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>审核日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['audit_time'])}}</div>
    </div>
  @elseif($detail['borrow_status']==2)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>申请日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['borrow_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>审核日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['audit_time'])}}</div>
    </div>
  @elseif($detail['borrow_status']==3)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>通过审核日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['audit_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>成功放款日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['received_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前借款天数：</p>
      </div>
      <!--div class="weui-cell__ft">{{$detail['borrow_days']}}</div-->
      <div class="weui-cell__ft">{{$detail['active_days'] or 0}}天</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前利息：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($interest['interest'],2)}}元</div>
    </div>
  @elseif($detail['borrow_status']==4)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>通过审核日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['audit_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>成功放款日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['received_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前借款天数：</p>
      </div>
      <!--div class="weui-cell__ft">{{$detail['borrow_days']}}</div-->
      <div class="weui-cell__ft">{{$detail['active_days'] or 0}}天</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前利息：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($interest['interest'],2)}}元</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前应还金额：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($detail['repayment_amount'],2)}}元</div>
    </div>
  @elseif($detail['borrow_status']==5)
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>成功放款日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['received_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前借款天数：</p>
      </div>
      <!--div class="weui-cell__ft">{{$detail['borrow_days']}}</div-->
      <div class="weui-cell__ft">{{$detail['active_days'] or 0}}天</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前利息：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($interest['interest'],2)}}元</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前违约金：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($interest['later'],2)}}元</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>当前应还金额：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($detail['repayment_amount'],2)}}元</div>
    </div>
  @else
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>放款日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['received_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>还款日期：</p>
      </div>
      <div class="weui-cell__ft">{{date('Y-m-d',$detail['repayment_end_time'])}}</div>
    </div>
    <div class="weui-cell">
      <div class="weui-cell__bd">
        <p>还款总金额：</p>
      </div>
      <div class="weui-cell__ft">{{number_format($detail['repayment_amount'],2)}}元</div>
    </div>

  @endif

</div>
<div class="weui-cells">
  <div class="weui-cell">
    <div class="weui-cell__bd loan-1">
      <p>未按期还款将影响您的信用评级，并会造成如下影响：</p>
      <ul>
        <li>1.逾期罚息：按未还金额×3‰/天</li>
        <li>2.逾期滞纳金：按未还金额×1‰/天</li>
        <li>3.征信黑名单：将影响您在互联网征信共享组织的信用评级</li>
      </ul>
    </div>
  </div>
</div>
<div class="weui-btn-area mb1em">
  @if($detail['borrow_status']==2)
    <a class="weui-btn weui-btn_primary" href="/app/borrow/apply">再申请一笔</a>
  @elseif($detail['borrow_status']==4||$detail['borrow_status']==5)
    <a class="weui-btn weui-btn_primary" href="/app/borrow/repayment/{{$detail['id']}}">立即还款</a>
    <!--a class="weui-btn weui-btn_primary" href="/app/repayment/{{$detail['id']}}">还款记录</a-->
  @elseif($detail['borrow_status']==6)
    <a class="weui-btn weui-btn_primary" href="/app/borrow/apply">再申请一笔</a>
    <!--a class="weui-btn weui-btn_primary" href="/app/repayment/{{$detail['id']}}">还款记录</a-->
  @endif
</div>

<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script src="{{assetByCdn('/wap/js/fastclick.js')}}"></script>
<script src="{{assetByCdn('/wap/js/city-picker.js')}}"></script>
<script src="{{assetByCdn('/wap/js/codecheck.js')}}"></script>
<script>
  $(function() {
    FastClick.attach(document.body);

    var head = $('body').attr('head');
    var uid  = $('body').attr('uid');
    var system = $('#system').attr('system');

    $(".reff-icon").click(function(){
      $(this).addClass("change");
      if(system == 'android'){
        window.android.refreshWeb(head+"app/borrow/detail/"+uid);
      }else if(system == 'ios'){
        refreshWeb(head+"app/borrow/detail/"+uid);
      }
    });

  });
</script>
<script src="{{assetByCdn('/wap/js/jquery-weui.js')}}"></script>
</body>
</html>
