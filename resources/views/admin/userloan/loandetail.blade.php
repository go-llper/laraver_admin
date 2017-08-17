@extends('layouts.admin-app')

@section('content')
<style>
     body{
         overflow: auto;
     }
</style>

<div class="pageheader">
    <h2><i class="fa fa-home"></i> Dashboard <span><a href="/admin/userLoan/loanRecord">借款管理</a></span><span>借款详情</span></h2>
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
            <div class="panel panel-default">
                <div class="panel-body panel-body-nopadding">

                    <div class="table-responsive col-md-12">
                        <table class="table mb30">
                            <tbody>
                            <tr>
                                <td colspan="3"><b>借款信息</b></td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{{ $loanInfo['real_name'] or null }}</td>
                                <td></td>
                            </tr>
                            {{--
                            <tr>
                                <td>性别</td>
                                <td>{{ $userInfo->sex or null }}</td>
                                <td></td>
                            </tr>
                            --}}
                            <tr>
                                <td>年龄</td>
                                <td>{{ $loanInfo['age'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>身份证</td>
                                <td>{{ $loanInfo['id_card'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>手机</td>
                                <td>{{ $loanInfo['phone'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>审批额度</td>
                                <td>{{ $loanInfo['credit_total'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>借款金额</td>
                                <td>{{ $loanInfo['borrow_amount'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>运送物品</td>
                                <td>{{ $loanInfo['log_goods'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>运送起始地点</td>
                                <td>{{ $loanInfo['log_point'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>运费</td>
                                <td>{{ $loanInfo['log_price'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>司机姓名</td>
                                <td>{{ $loanInfo['driver_name'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>联系电话</td>
                                <td>{{ $loanInfo['driver_phone'] or null }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>运输车辆车牌号</td>
                                <td>{{ $loanInfo['truck_no'] or null }}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div><!-- panel-body -->
            </div>

            <div id="userContract" style="display: block;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>照片信息</b></td>
                                </tr>
                                <tr>
                                    <td>行驶证</td>
                                    <td><img width="200px" height="200px" src="{{ $loanInfo['truck_image'] or null }}" /></td>
                                    <td></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div><!-- panel-body -->
                </div>
            </div>



            <div id="userLoan" style="display: block;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">
                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>审批操作</b></td>
                                </tr>
                                <tr>
                                    <td ><a href="/admin/userLoan/loanCheck?id={{ $loanInfo['id'] }}&status=1" onclick="return confirm('确定放款？')"><button>同意放款</button></a></td>
                                    <td ><a href="/admin/userLoan/loanCheck?id={{ $loanInfo['id'] }}&status=2" onclick="return confirm('确定拒绝？')"><button>拒绝放款</button></a></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- panel-body -->
                </div>
            </div>

        </div><!-- col-sm-9 -->

    </div><!-- row -->

</div>
@endsection
