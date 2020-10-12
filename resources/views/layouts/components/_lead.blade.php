<div class="form-group">
    <label class="col-sm-2 control-label">
        <span class="text-inherit"></span>
    </label>
    <div class="col-sm-10 col-md-6 field">
        <input type="hidden" class="form-control input input-big border-left"  name="code"
               value="@yield('code')"
               data-validate="required:请导入激活码"/>
        <a class="btn btn-outline btn-warning" onclick="layerIframeLead('激活码礼包','{{url('admin/asset/lead')}}')"><i
                    class="fa fa-share-square"></i>导码</a>
    </div>
</div>

{{--js弹窗--}}
<script type="text/javascript">
    //layer-iframe加载表单
    function layerIframeLead(title, url) {
        //打开弹窗清空session，获取新值存入session
        sessionStorage.clear();
        //接受上传的值
        sessionStorage.setItem('iframe-url', url);
        sessionStorage.setItem('iframe-title',title);

        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            offset: 'auto',
            shade: 0.8,
            resizing: true,
            area: ["30%", "50%"],
            content: url,
            btn: ['提交', '取消'],

            yes: function (index, layero) {
                //获取iframe的body元素
                var body = layer.getChildFrame('body', index);
                // 得到iframe页的窗口对象
                var iframeWin = $(layero).find("iframe")[0].contentWindow;

                //执行iframe页的showMsg方法
                var num = iframeWin.get_selected_files();

                //判断是否上传图片
                if (num == 1) {
                    //获得上传的值
                    var data = JSON.parse(sessionStorage.getItem('code'));
                    var code = data.message;
                    $('input[name=code]').val(code);
                    console.log(data);
                    layer.closeAll();
                    return true;
                } else {
                    layer.msg('请上传'+title, {icon: 5});
                    return false;
                }


            },
            btn2: function (index, layero) {
                //关闭弹窗并刷新当前页面
                layer.closeAll();
            }
        });

    }
</script>