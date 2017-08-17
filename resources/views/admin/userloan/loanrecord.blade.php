@extends('layouts.admin-app')

@section('content')

<div class="pageheader">
    <h2><i class="fa fa-home"></i> Dashboard <span>借款管理</span></h2>
</div>

<div class="contentpanel panel-email">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <h5 class="subtitle mb5">借款审核记录</h5>
                <div class="table-responsive col-md-12">
                    <table class="table mb30">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>身份证</th>
                            <th>手机号</th>
                            <th>借款金额</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $record)
                            <tr>
                                <td>
                                    {{ $record->id }}
                                </td>
                                <td>{{ $record->real_name }}</td>
                                <td>{{ $record->id_card }}</td>
                                <td>{{ $record->phone }}</td>
                                <td>{{ $record->borrow_amount }}</td>
                                <td>{{ $record->created_at }}</td>
                                <td>
                                    <a href="/admin/userLoan/loanDetail/{{$record->id}}"
                                       class="btn btn-white btn-xs"><i class="fa fa-pencil"></i>审核</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $list->links() !!}
                </div>
            </div><!-- panel-body -->
        </div><!-- panel -->

    </div><!-- row -->

</div>
@endsection

