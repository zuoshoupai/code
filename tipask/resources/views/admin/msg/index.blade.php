@extends('admin/public/layout')

@section('title')商城管理@endsection

@section('content')
    <section class="content-header">
        <h1>推送消息管理</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form role="form" name="item_form" id="item_form" method="post">
                        <input name="_method" type="hidden" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.msg.create') }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="新增消息"><i class="fa fa-plus"></i></a>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="删除选中项" onclick="confirm_submit('item_form','{{  route('admin.msg.destroy') }}','确认删除选中项？')"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body  no-padding">
                            <table class="table table-striped">
                                <tr>
                                    <th><input type="checkbox" class="checkbox-toggle"/></th>
                                    <th>id</th>
                                    <th>标题</th>
									<th>类型</th>
                                    <th>内容</th>
                                    <th>接收人</th>
                                    <th>创建时间</th>
									<th>修改时间</th>
									<th>发送时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($msgs as $msg)
                                    <tr>
                                        <td><input type="checkbox" value="{{ $msg->id }}" name="ids[]"/></td>
										<td>{{ $msg->id }}</td>
                                        <td>{{ $msg->title }}</td>
										<td>{{ $msg->type==1?'文章':'内容'}}</td>
                                        <td>{{ $msg->content }}</td>
                                        <td>{{ $msg->to_user }}</td>
                                        <td>{{ $msg->create_time }}</td>
										<td>{{ $msg->update_time }}</td>
										<td>{{ $msg->post_time }}</td>
                                        <td>
                                            <div class="btn-group-xs" >
                                                <a class="btn btn-default" href="{{ route('admin.msg.edit',['id'=>$msg->id]) }}" data-toggle="tooltip" title="编辑推送信息"><i class="fa fa-edit"></i></a>
												<a class="btn btn-default" href="{{ route('admin.msg.postmsg',['id'=>$msg->id]) }}" data-toggle="tooltip" title="发布信息"><i class="fa fa-hand-o-right"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="box-footer clearfix">
                            {!! str_replace('/?', '?', $msgs->render()) !!}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        set_active_menu('operations',"{{ route('admin.notice.index') }}");
    </script>
@endsection