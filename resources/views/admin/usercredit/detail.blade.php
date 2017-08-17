@extends('layouts.admin-app')

@section('content')
<style>
     body{
         overflow: auto;
     }
</style>

<div class="pageheader">
    <h2><i class="fa fa-home"></i> Dashboard <span><a href="/admin/user_credit">授信管理</a></span><span>授信详情</span></h2>
</div>

<div class="contentpanel">

    <div class="row">
        <div class="col-sm-9 col-lg-10">
            @if(Session::has('fail'))
                <div class="alert alert-error alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
                    {{ Session::get('fail') }}
                </div>
            @endif
            <div class="panel-heading">
                <div class="panel-btns">
                    <a href="" class="panel-close">×</a>
                    <a href="" class="minimize">−</a>
                </div>
                <div class="box-header">
                    <button  class="btn  btn-primary active" show-type="userInfo" >用户基本信息</button>
                    <button  class="btn  btn-primary " show-type="userContract">通讯信息</button>
                    <button  class="btn  btn-primary " show-type="userPhoto">照片信息</button>
                </div>
            </div>
            <div id="userInfo">
            <div class="panel panel-default">
                <div class="panel-body panel-body-nopadding">

                    <div class="table-responsive col-md-12">
                        <table class="table mb30">
                            <tbody>
                            <tr>
                                <td colspan="3"><b>用户基本信息</b></td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{{ $userInfo['real_name'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>性别</td>
                                <td>{{ $userInfo['sex'] or null }}</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>年龄</td>
                                <td>{{ $userInfo['age'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>身份证</td>
                                <td>{{ $userInfo['id_card'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>手机</td>
                                <td>{{ $userInfo['phone'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>文化程度</td>
                                <td>{{ $userInfo['cul_level'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>婚姻状况</td>
                                <td>{{ $userInfo['marital_status'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>居住地址</td>
                                <td>{{ $userInfo['home_address'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>授信地理位置</td>
                                <td>{{ $userInfo['home_address'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>从业年限</td>
                                <td>{{ $userInfo['service_years'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>人脸识别</td>
                                <td>{{ $userInfo['confidence'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>通讯录数量</td>
                                <td>{{ $userInfo['phone_num'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>联系人匹配数量</td>
                                <td>{{ $userInfo['phone_contract'] or null }}</td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div><!-- panel-body -->
            </div>
            <div class="panel panel-default">
                <div class="panel-body panel-body-nopadding">

                    <div class="table-responsive col-md-12">
                        <table class="table mb30">
                            <tbody>
                            <tr>
                                <td colspan="3"><b>公司信息</b></td>
                            </tr>
                            <tr>
                                <td>公司名称</td>
                                <td>{{ $userInfo['company_name'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>成立时间</td>
                                <td>{{ $userInfo['company_start'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>注册地址</td>
                                <td>{{ $userInfo['company_address'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>主营业务</td>
                                <td>{{ $userInfo['main_biz'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>经营地址</td>
                                <td>{{ $userInfo['biz_address'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>雇员人数</td>
                                <td>{{ $userInfo['employee_number'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>年收入（万元）</td>
                                <td>{{ $userInfo['money_total'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>年净利润（万元）</td>
                                <td>{{ $userInfo['profit_total'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>挂靠车辆数量</td>
                                <td>{{ $userInfo['anchored_car'] or null }}</td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div><!-- panel-body -->
            </div>

            <div class="panel panel-default">
                <div class="panel-body panel-body-nopadding">

                    <div class="table-responsive col-md-12">
                        <table class="table mb30">
                            <tbody>
                            <tr>
                                <td colspan="3"><b>用户照片信息</b></td>
                            </tr>
                            <tr>
                                <td>身份证正面照</td>
                                <td><a href="{{ $userPhoto['ocr_pic'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['ocr_pic'] or null }}" /></a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>身份证反面照</td>
                                <td><a href="{{ $userPhoto['ocr_pic_back'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['ocr_pic_back'] or null }}" /></a></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div><!-- panel-body -->
            </div>
            </div>
            <div id="userContract" style="display: none;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>联系人信息</b></td>
                                </tr>

                                <tr>
                                    <td>姓名</td>
                                    <td>{{ $userContract['family_name']   or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>关系</td>
                                    <td>直系亲属</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>手机号</td>
                                    <td>{{ $userContract['family_tel']  or null }}</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>姓名</td>
                                    <td>{{ $userContract['workmate_name']   or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>关系</td>
                                    <td>同事</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>手机号</td>
                                    <td>{{ $userContract['workmate_tel']  or null }}</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>姓名</td>
                                    <td>{{ $userContract['friend_name']   or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>关系</td>
                                    <td>朋友</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>手机号</td>
                                    <td>{{ $userContract['friend_tel']  or null }}</td>
                                    <td></td>
                                </tr>


                                <tr>
                                    <td>姓名</td>
                                    <td>{{ $userContract['marital_name']   or null }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>关系</td>
                                    <td>配偶</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>手机号</td>
                                    <td>{{ $userContract['marital_tel']  or null }}</td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>

                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="4"><b>手机通讯录</b></td>
                                </tr>
                                <tr>
                                    <td>姓名</td>
                                    <td>电话号码</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @if( !empty($phoneContract) )
                                    @foreach($phoneContract as $phoneContr)
                                        <tr>
                                            <td>{{ $phoneContr['name']   or null }}</td>
                                            <td>{{ $phoneContr['tel']    or null }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">暂无通讯录信息</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>

                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="4"><b>手机通话记录</b></td>
                                </tr>
                                <tr>
                                    <td>序号</td>
                                    <td>电话号码</td>
                                    <td>通话时长（秒）</td>
                                    <td>匹配通讯录名称</td>
                                </tr>
                                @if( !empty($phoneRecord) )
                                    @foreach($phoneRecord as $phoneRe)
                                        <tr>
                                            <td>{{ $phoneRe['tel_id'] }}</td>
                                            <td>{{ $phoneRe['tel_no']   or null }}</td>
                                            <td>{{ $phoneRe['tel_time'] or null }}</td>
                                            <td>{{ $phoneRe['tel_name'] or null }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">暂无通话信息</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>

            </div>

            <div id="userPhoto" style="display: none;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>用户照片信息</b></td>
                                </tr>
                                <tr>
                                    <td>营业执照</td>
                                    <td>
                                        <a href="{{ $userPhoto['business'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['business'] or null }}" /></a></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>道路运输许可证</td>
                                    <td><img width="320px" height="200px" src="{{ $userPhoto['transport'] or null }}" /></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>结婚证</td>
                                    <td><a href="{{ $userPhoto['marry'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['marry'] or null }}" /></a></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>行驶证</td>
                                    <td><a href="{{ $userPhoto['driver'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['driver'] or null }}" /></a></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>房产证-封面</td>
                                    <td><a href="{{ $userPhoto['house_cover'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_cover'] or null }}" /></a></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>房产证-发证机关</td>
                                    <td><a href="{{ $userPhoto['house_sign'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_sign'] or null }}" /></a></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>房产证-房屋信息</td>
                                    <td><a href="{{ $userPhoto['house_info'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_info'] or null }}" /></a></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>房产证-平面图</td>
                                    <td><a href="{{ $userPhoto['house_pic'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_pic'] or null }}" /></a></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>房产证-权利摘要</td>
                                    <td><a href="{{ $userPhoto['house_title'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_title'] or null }}" /></a></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>房产证-注意事项</td>
                                    <td><a href="{{ $userPhoto['house_tip'] or null }}" target="_blank"><img width="320px" height="200px" src="{{ $userPhoto['house_tip'] or null }}" /></a></td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>
            </div>

            <div id="userPhoto" style="display: block;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">
                        <form action="/admin/userCredit/creditStatus" method="post">
                            <input type="hidden" name="uid" value="{{ $userInfo['uid'] }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>审批结果</b></td>
                                </tr>
                                @if( $userInfo["credit_status"] > \App\Models\Credit\CreditModel::CREDIT_ING )
                                    <tr>
                                        <td>审核额度：{{ $userInfo["credit_total"] or null }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><input type="radio" name="credit_total" value="100000" checked>100000</td>
                                        <td><input type="radio" name="credit_total" value="90000">90000</td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="credit_total" value="80000">80000</td>
                                        <td><input type="radio" name="credit_total" value="70000">70000</td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="credit_total" value="60000">60000</td>
                                        <td><input type="radio" name="credit_total" value="50000">50000</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="radio" name="credit_total" value="-1">拒绝通过</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="submit"  value="提交审批结果"></td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div><!-- panel-body -->
                </div>
            </div>

        </div><!-- col-sm-9 -->

    </div><!-- row -->

</div>
@endsection
<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        // 搜索切换
        $(".box-header button").click(function(){
            $(this).addClass("active").siblings().removeClass("active");
            var btnType = $(this).attr('show-type');
            //alert(btnType);
            if( btnType == "userContract"){
                $("#userInfo").hide();
                $("#userContract").show();
                $("#userPhoto").hide();

            }else if(btnType == "userPhoto"){
                $("#userInfo").hide();
                $("#userContract").hide();
                $("#userPhoto").show();
            }else{
                $("#userInfo").show();
                $("#userContract").hide();
                $("#userPhoto").hide();
            }
        });


    });
</script>
