@extends('layouts.admin-app')

@section('content')
<style>
     body{
         overflow: auto;
     }
</style>

<div class="pageheader">
    <h2><i class="fa fa-home"></i> Dashboard <span><a href="/admin/userLoan/loanRecord">待放款记录</a></span><span>借款详情</span></h2>
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
                                <td>银行卡号</td>
                                <td>{{ $loanInfo['borrow_card_no'] or null }}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div><!-- panel-body -->
            </div>

            <div id="userLoan" style="display: block;">
                <div class="panel panel-default">
                    <div class="panel-body panel-body-nopadding">
                        <form action="/admin/userLoan/loanOperate" method="post">
                            <input type="hidden" name="uid" value="{{ $loanInfo['uid'] }}">
                            <input type="hidden" name="id"  value="{{ $loanInfo['id'] }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <tbody>
                                <tr>
                                    <td colspan="3"><b>借款操作</b></td>
                                </tr>
                                {{--
                                <tr>
                                    <td>借款手续费 <input type="text" name="borrow_need_fee"></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                --}}
                                <tr>
                                    <td colspan="3"><input type="submit" onclick="return confirm('确定打款？')" value="打款"></td>
                                </tr>
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