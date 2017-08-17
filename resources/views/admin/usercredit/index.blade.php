@extends('layouts.admin-app')

@section('content')

<div class="pageheader">
    <h2><i class="fa fa-home"></i> Dashboard <span>授信管理</span></h2>
</div>

<div class="contentpanel panel-email">
    <div class="row">
        <div class="panel panel-default">
            @if(Session::has('message'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
                    {{ Session::get('message') }}
                </div>
            @endif
            <div class="panel-body">
                <div class="box-header">
                    <a  class="btn  btn-primary active" href="/admin/user_credit?status={{ \App\Models\Credit\CreditModel::CREDIT_ING }}" >授信待审核</a>
                    <a  class="btn  btn-primary " href="/admin/user_credit?status={{ \App\Models\Credit\CreditModel::CREDIT_PASS }}">授信已审核</a>
                    <a  class="btn  btn-primary " href="/admin/user_credit?status={{ \App\Models\Credit\CreditModel::CREDIT_REFUSE }}">授信拒绝</a>
                </div>
                <div class="table-responsive col-md-12">
                    <table class="table mb30">
                        <thead>
                        <tr>
                            <th>UID</th>
                            <th>姓名</th>
                            <th>身份证</th>
                            <th>手机号</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if( !empty($list) )
                            @foreach($list as $record)
                                <tr>
                                    <td>
                                        {{ $record->uid }}
                                    </td>
                                    <td>{{ $record->real_name }}</td>
                                    <td>{{ $record->id_card }}</td>
                                    <td>{{ $record->phone }}</td>
                                    <td>{{ $record->created_at }}</td>
                                    <td>
                                        <a href="/admin/userCredit/creditDetail/{{$record->uid}}"
                                           class="btn btn-white btn-xs"><i class="fa fa-pencil"></i>审核</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">暂无信息</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    {!! $list->links() !!}
                </div>
            </div><!-- panel-body -->
        </div><!-- panel -->

    </div><!-- row -->

</div>
@endsection
<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        // 搜索切换
        $(".box-header button").click(function(){
            $(this).addClass("active").siblings().removeClass("active");
        });
    });
</script>
