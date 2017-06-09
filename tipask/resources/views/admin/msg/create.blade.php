@extends('admin/public/layout')
@section('title')
    新增推送消息
@endsection
@section('css')
    <link href="{{ asset('/static/js/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/static/js/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            推送消息管理
            <small>新增推送消息</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <form role="form" name="addForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.msg.store') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" id="tags" name="skill" value="0" />
						<input type="hidden" id="tags_user" name="skill" value="0" />
                        <div class="box-body">

                            <div class="form-group @if($errors->has('title')) has-error @endif">
                                <label>消息标题</label>
                                <input type="text" name="title" class="form-control " placeholder="标题" value="">
                                @if($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                            </div>
							<div class="form-group">
                                <label>消息类型</label>
                                <span class="text-muted"></span>
                                <div class="radio">
                                    <label>
                                        <input type="radio" class="article_type" name="type" value="1" checked/> 文章
                                    </label>&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" class="article_type" name="type" value="2" /> 内容
                                    </label>
                                </div>
                            </div>

                            <div id="post_content" style="display:none;" class="form-group @if($errors->has('content')) has-error @endif">
                                <label>消息内容</label>
                                <input type="text" name="content" class="form-control " placeholder="推送内容" value="">
                                @if($errors->has('content')) <p class="help-block">{{ $errors->first('content') }}</p> @endif
                            </div>
							<div id="post_article" class="form-group">
								<label>推送文章</label>
								<div>
									<select id="select_article" name="select_article" class="form-control" >

										@if($article)
												@foreach($article as $v)
													@if($v->id == -100)
															<option value="{{ $v->id }}" >{{ $v->title }}</option>
													@endif
												@endforeach
											@endif
									</select>
								</div>
							</div>
							
                            <div class="form-group @if($errors->has('to_user')) has-error @endif">
                                <label>推送对象</label>
                                <div>
									<select id="select_user" name="select_user" class="form-control" >
										<option value=0>所有设备</option>
										@if($user)
												@foreach($user as $v)
													@if($v->id == -100)
															<option value="{{ $v->id }}" >{{ $v->name }}</option>
													@endif
												@endforeach
											@endif
									</select>
								</div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">保存</button>
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
	<script src="{{ asset('/static/js/select2/js/select2.min.js')}}"></script>
    <script type="text/javascript">
        $(function(){
			$("#select_article").select2({
                theme:'bootstrap',
                placeholder: "文章关键词",
                ajax: {
                    url: '/ajax/loadArticles',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            word: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength:1
                //tags:true
            });

            $("#select_article").change(function(){
                $("#tags").val($("#select_article").val());
            });
			$("#select_user").select2({
                theme:'bootstrap',
                placeholder: "用户关键词",
                ajax: {
                    url: '/ajax/loadTouser',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            word: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength:1
                //tags:true
            });

            $("#select_user").change(function(){
                $("#tags_user").val($("#select_user").val());
            });

        });
		$(".article_type").change(function(){
			if($(this).val()==1)
			{
				$('#post_content').hide();
				$('#post_article').show();
			}else{
				$('#post_content').show();
				$('#post_article').hide();
			}
		});
    </script>
@endsection
