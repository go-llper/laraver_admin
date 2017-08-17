@include('wap.common.header')
<body ontouchstart is-login="{{!empty($msg)?$msg:''}}" head="{{ env("APP_URL") }}">
<span system="{{ $system }}" id="system"></span>
<div class="weui-tab">
    <div class="weui-tab__bd">
        <!--tab1-->
        <div class="weui-tab__bd-item weui-tab__bd-item--active Js_tab_main_click">
            <header>
                <h2 class="title">首页</h2>
                <span class="reff-icon" id="refresh1"></span>
            </header>
            <h2 class="index-1">可用额度</h2>
            <p class="index-num">{{!empty($credit_total)?$credit_total:0}}</p>
            <div class="weui-btn-area">
                @if(!isset($credit_status) || $credit_status == 0)
                    <a class="weui-btn weui-btn_primary" href="/app/credit/step/1" id="showTooltips">立即申请额度</a>
                @elseif($credit_status == \App\Models\Credit\CreditModel::CREDIT_ING)
                    <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">授信审核中</a>
                @elseif($credit_status == \App\Models\Credit\CreditModel::CREDIT_PASS)
                    <a class="weui-btn weui-btn_primary" href="/app/borrow/apply" id="showTooltips">立即借款</a>
                @elseif($credit_status == \App\Models\Credit\CreditModel::CREDIT_REFUSE)
                    <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">授信未通过</a>
                @endif
            </div>
        </div>

        <!--tab1-->
        <!--tab2-->
        <div class="weui-tab__bd-item Js_tab_main_click">
            <header>
                <h2 class="title">借款记录</h2>
                <span class="reff-icon" id="refresh2"></span>
            </header>
           <table id="trade-records" class="wap2-table-1">
                <thead>
                    <tr>
                       <th width="33%">借款日期</th>
                       <th width="33%">借款金额</th>
                       <th width="34%">当前状态</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($borrow_list))
                        @foreach($borrow_list as $borrow)
                            <tr onclick="location.href='/app/borrow/detail/{{$borrow['id']}}'">
                                <th>{{date('Y-m-d',$borrow['borrow_time'])}}</th>
                                <th>{{number_format($borrow['borrow_amount'],2)}}</th>
                                <th>{{$borrow_status[$borrow['borrow_status']]['status']}}&gt;></th>
                            </tr>
                        @endforeach
                    @else
                        <p>暂无借款数据</p>
                    @endif
                </tbody>
            </table>

        </div>
        <!-- tab2-->

        <div class="weui-tab__bd-item Js_tab_main_click">
            <header>
                <h2 class="title">设置</h2>
                <span class="reff-icon" id="refresh3" ></span>
            </header>
            <!-- 设置 登录 -->
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>收款卡</p>
                    </div>
                    @if(!empty($card))
                        <div class="weui-cell__ft">{{$card}}</div>
                    @else
                        <div class="weui-cell__ft"><a href="/app/bind/bankcard" class="well-sm">绑定银行卡</a></div>
                    @endif
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>当前版本</p>
                    </div>
                    <div class="weui-cell__ft">V1.0.1</div>
                </div>
                @if(isset($_COOKIE['USER_INFO']))
                <a class="weui-cell weui-cell_access" href="javascript:void(0)" id="logout" >
                    <div class="weui-cell__bd">
                        <p>退出当前账号</p>
                    </div>
                    <div class="weui-cell__ft">
                    </div>
                </a>
                @else
                <a class="weui-cell weui-cell_access" href="/app/login" id="login" >
                    <div class="weui-cell__bd">
                        <p>登录/注册</p>
                    </div>
                    <div class="weui-cell__ft">
                    </div>
                </a>
                @endif
            </div>
            <p class="weui-tel">客服电话：010-56592060</p>
        </div>

    </div>
    <div class="weui-tabbar">
        <a href="#0" class="weui-tabbar__item1 cur" >
            <div class="weui-tabbar__icon">
                <img src="{{assetByCdn('/wap/images/icon_nav_panel.png')}}" alt="">
            </div>
            <p class="weui-tabbar__label">首页</p>

        </a>

        <a href="#1" class="weui-tabbar__item1" >
            <div class="weui-tabbar__icon">
                <img src="{{assetByCdn('/wap/images/icon_nav_article.png')}}" alt="">
            </div>
            <p class="weui-tabbar__label">借款记录</p>

        </a>
        <a href="#2" class="weui-tabbar__item1">
            <div class="weui-tabbar__icon">
                <img src="{{assetByCdn('/wap/images/icon_nav_toast.png')}}" alt="">
            </div>
            <p class="weui-tabbar__label">设置</p>

        </a>
    </div>
</div>
<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script src="{{assetByCdn('/wap/js/fastclick.js')}}"></script>
<script src="{{assetByCdn('/wap/js/jquery-weui.js')}}"></script>
<script>
    $(function() {

        FastClick.attach(document.body);

        var head = $('body').attr('head');
        var system = $('#system').attr('system');
        $("#refresh1").click(function(){
            $(this).addClass("change");
            if(system == 'android'){
                window.android.refreshWeb(head+"app");
            }else if(system == 'ios'){
                refreshWeb(head+"app");
            }
        });
        $("#refresh2").click(function(){
            $(this).addClass("change");
            if(system == 'android'){
                window.android.refreshWeb(head+"app#1");
            }else if(system == 'ios'){
                refreshWeb(head+"app#1");
            }
        });
        $("#refresh3").click(function(){
            $(this).addClass("change");
            if(system == 'android'){
                window.android.refreshWeb(head+"app#2");
            }else if(system == 'ios'){
                refreshWeb(head+"app#2");
            }
        });

        $("#logout").click(function(){
            $.ajax({
                url : '/app/logout',
                type: 'GET',
                dataType: 'json',
                success : function(ret) {
                    if(ret.status) {
                        location.href='/app';
                        loginOut();
                    }
                }
            });
        });

        function tabclick(tab,tabmain,cur){
            $(tab).click(function(){
                var index = $(tab).index(this);
                $(this).addClass(cur).siblings(tab).removeClass(cur);
                $(tabmain).eq(index).show().siblings(tabmain).hide();
            })
        };

        tabclick('.weui-tabbar a','.Js_tab_main_click','cur');

        function tablink(num,tab,tab1,cur){
            var str=window.location.hash;
            if(window.location.hash.substring(1)==num){
                $(tab).eq(num).show().siblings(tab).hide();
                $(tab1).eq(num).addClass(cur).siblings(tab1).removeClass(cur);
            }
        }

        tablink("0",".Js_tab_main_click",".weui-tabbar__item1","cur");
        tablink("1",".Js_tab_main_click",".weui-tabbar__item1","cur");
        tablink("2",".Js_tab_main_click",".weui-tabbar__item1","cur");
    });
</script>
</body>
</html>