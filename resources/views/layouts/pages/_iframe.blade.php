<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    {{--css样式 引入--}}
    @section('css')
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/js/plugins/layui/css/layui.css" rel="stylesheet">
        <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="/css/animate.css" rel="stylesheet">
        <link href="/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
        <link href="/css/pintuer.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
        <link href="/css/common.css" rel="stylesheet">
        <link href="/js/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
        <link href="/js/plugins/artDialog/dialog.css" rel="stylesheet">
        <script src="/js/jquery.js"></script>
    @show
</head>

<body class="frame-bg-gray" data-code="{{session('code')}}">


<div id="artDialog">
    <iframe id="artDialogIframe" src="/admin/asset/webuploader"></iframe>
</div>


<div class="container mt30">
    <div class="row">
        {{--内容上面--}}
        @yield('content-top')
        {{--主体内容--}}
        <div class="col-xs-10 col-sm-10">
            <fieldset class="form-horizontal">
                <form class="m-t js-ajax-form" role="form" method="post"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{--引入主题内容--}}
                    @section('content')
                        <div class="form-group">
                            <label class="col-xs-3 col-sm-3 control-label">
                                <span class="text-inherit">房间号：</span>
                            </label>
                            <div class="col-xs-9 col-sm-9 field">
                                <input type="text" class="form-control input input-big border-left" placeholder="请输入房间号"
                                       name="code"
                                       data-validate="required:请输入房间号"/>
                                <div class="tips"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 col-sm-3 control-label">
                                <span class="text-inherit">道具ID号:</span>
                            </label>
                            <div class="col-xs-9 col-sm-9 field">
                                <input type="text" class="form-control input input-big border-left"
                                       placeholder="请输入道具ID号"
                                       name="props_id"
                                       data-validate="required:请输入道具ID号"/>
                                <div class="tips"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 col-sm-3 control-label">
                                <span class="text-inherit">围观人数:</span>
                            </label>
                            <div class="col-xs-9 col-sm-9 field">
                                <input type="text" class="form-control input input-big border-left"
                                       placeholder="请输入围观人数"
                                       name="set_person"
                                       data-validate="required:请输入围观人数"/>
                                <div class="tips"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 col-sm-3 control-label">
                                <span class="text-inherit">logo图片:</span>
                            </label>
                            <div class="col-xs-9 col-sm-9 field">
                                <input type="hidden" class="input w40 default-read" value="{{old('logo_name')}}"
                                       name="logo_name" id="js-thumbnail-input-name"
                                />
                                <input type="hidden" class="input w40 default-read" value="{{old('logo_url')}}"
                                       name="logo_url"
                                       id="js-thumbnail-input-url"
                                />

                                <img src="/img/default-thumbnail.png" onclick="uploadOneImage('logo图片上传',this.id,1)"
                                     id="js-thumbnail-input-preview" width="135" style="cursor: pointer">

                                <br/><br/>
                                <input type="button" class="btn btn-sm btn-cancel-thumbnail" value="取消图片"/>
                                <div class="tips"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 col-sm-3 control-label">
                                <span class="text-inherit">备注：</span>
                            </label>
                            <div class="col-xs-9 col-sm-9 field">
                    <textarea class="form-control input input-big border-left" rows="5" placeholder="请输入备注"
                              name="remark"
                              data-validate="required:请输入备注"></textarea>
                                <div class="tips"></div>
                            </div>
                        </div>
                    @show

                </form>
            </fieldset>
        </div>

        {{--内容下面--}}
        @yield('content-bot')
    </div>
</div>


{{--js引入js文件--}}
@section('js')
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/pintuer.js"></script>
    <script src="/js/plugins/layer/layer.js"></script>
    <script src="/js/wind.js"></script>
    <script src="/js/plugins/laydate/laydate.js"></script>
    <script src="/js/plugins/artDialog/dialog-plus.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/upload.js"></script>
    <script type="text/javascript">
        //提交表单
        function iframeAjaxSubmit() {
            //定义全局变量 开关
            var flag = true;
            $("*[data-validate]").each(function () {
                var require = $(this).attr("data-validate");
                var word = require.replace(/required:/, '');
                var finput = $.trim($(this).val());
                switch (true) {
                    case ('' == finput):
                        flag = false;
                        notyError(word);
                        break;
                    default:
                        flag = true;
                }
            });
            //假如flag 正确提交表单
            if (flag) {
                //获取session里的url参数
                var iframeUrl = sessionStorage.getItem('iframe-url');
                var ajaxForm_list = $('form.js-ajax-form');
                $.ajax({
                    url: iframeUrl,
                    data: ajaxForm_list.serialize(),
                    type: 'post',
                    dataType: 'json',
                    success: function (data1) {

                        if (data1.code == 1) {
                            noty({
                                text: data1.content,
                                type: 'success',
                                layout: 'topCenter',
                                modal: true,
                                timeout: data1.time * 350,
                                callback: {
                                    afterClose: function () {
                                        //关闭父窗口的弹窗
                                        window.parent.layer.closeAll();
                                        //刷新父窗口的页面
                                        window.parent.refresh();
                                    }
                                }
                            }).show();

                        } else if (data1.code == 0) {
                            noty({
                                text: data1.content,
                                type: 'error',
                                layout: 'topCenter',
                                modal: true,
                                timeout: data1.time * 350,
                                callback: {
                                    afterClose: function () {

                                    }
                                }
                            }).show();
                            $(window).focus();
                        }


                    },
                    error: function (data) {
                        //关闭父窗口的弹窗
                        window.parent.layer.closeAll();
                        return false;
                    }

                });

            }

        }
    </script>
@show

</body>

</html>
