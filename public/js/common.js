// +----------------------------------------------------------------------
// | Zhihuo [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 zhihuo All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaojin <935876982@qq.com>
// +----------------------------------------------------------------------


$(function () {
    //全局ajax处理
    $.ajaxSetup({
        complete: function (jqXHR) {
        },
        data: {},
        error: function (jqXHR, textStatus, errorThrown) {
            //请求失败处理
        }
    });

    Wind.use('noty', function (e) {
        // 所有的ajax form提交,由于大多业务逻辑都是一样的，故统一处理
        var ajaxForm_list = $('form.js-ajax-form');
        if (ajaxForm_list.length ) {
            var flag = true;
            var word;
            $('button.js-ajax-submit').click(
                function (e) {
                    $("*[data-validate]").each(function () {
                        var require = $(this).attr("data-validate");
                        word = require.replace(/required:/, '');
                        var finput = $.trim($(this).val());
                        switch (true) {
                            case ('' == finput):
                                flag = false;
                                return false;
                                break;
                            default:
                                flag = true;
                        }
                    });
                    if (flag) {
                        //取消表单默认提交 改ajax提交
                        e.preventDefault();
                        var btn = $(this), form = btn.parents('form.js-ajax-form');
                        var ajaxForm_list = $('form.js-ajax-form');
                        $.ajax({
                            url: btn.data('action') ? btn.data('action') : ajaxForm_list.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                            data: ajaxForm_list.serialize(),
                            type: 'post',
                            dataType: 'json',
                            beforeSend: function () {
                                btn.data("loading", true);
                                var text = btn.text();
                                //按钮文案、状态修改
                                btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                            },
                            success: function (data1) {
                                var text = btn.text();
                                //按钮文案、状态修改
                                btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();

                                function _refresh() {
                                    if (data1.url) {
                                        //返回带跳转地址
                                        window.location.href = data1.url;
                                    } else {
                                        if (data1.code == 1) {
                                            //刷新当前页
                                            reloadPage(window);
                                        }
                                    }
                                }

                                if (data1.code == 1) {
                                    noty({
                                        text: data1.content,
                                        type: 'success',
                                        layout: 'topCenter',
                                        modal: true,
                                        timeout: data1.time * 350,
                                        callback: {
                                            afterClose: function () {
                                                if (btn.data('refresh') == undefined || btn.data('refresh')) {
                                                    _refresh();
                                                }
                                            }
                                        }
                                    }).show();
                                    $(window).focus();
                                } else if (data1.code == 0) {
                                    noty({
                                        text: data1.content,
                                        type: 'error',
                                        layout: 'topCenter',
                                        modal: true,
                                        timeout: data1.time * 350,
                                        callback: {
                                            afterClose: function () {
                                                //_refresh();
                                            }
                                        }
                                    }).show();
                                    $(window).focus();
                                }


                            },
                            error: function (data) {
                                layer.msg('服务器请求错误', {offset: '30px', icon: 5});
                                return false;
                            }

                        });
                    }else { //取消默认事件 提示错误消息
                        e.preventDefault();
                        notyError(word);
                    }

                }
            );

        }

        //所有的删除操作，删除数据后刷新页面
        if ($('a.js-ajax-status').length) {
            Wind.use('noty', function () {
                $('.js-ajax-status').on('click', function (e) {
                    e.preventDefault();
                    var $_this = this,
                        $this = $($_this),
                        href = $this.data('href'),
                        refresh = $this.data('refresh'),
                        msg = $this.data('msg');
                    href = href ? href : $this.attr('href');

                    $.getJSON(href).done(function (data) {
                        if (data.code == '1') {
                            noty({
                                text: data.content,
                                type: 'success',
                                layout: 'topCenter',
                                modal: true,
                                timeout: data.time * 350,
                                animation: {
                                    open: 'animated bounceInDown', // Animate.css class names
                                    close: 'animated bounceOutUp', // Animate.css class names
                                },

                                callback: {
                                    afterClose: function () {
                                        if (refresh == undefined || refresh) {
                                            if (data.url) {
                                                //返回带跳转地址
                                                window.location.href = data.url;
                                            } else {
                                                //刷新当前页
                                                reloadPage(window);
                                            }
                                        }
                                    }
                                }
                            }).show();

                        } else if (data.code == '0') {
                            noty({
                                text: data.content,
                                type: 'error',
                                layout: 'topCenter',
                                modal: true,
                                timeout: data.time * 350,
                                animation: {
                                    open: 'animated bounceInDown', // Animate.css class names
                                    close: 'animated bounceOutUp', // Animate.css class names
                                },
                                callback: {
                                    afterClose: function () {
                                        // if (refresh == undefined || refresh) {
                                        //     if (data.url) {
                                        //         //返回带跳转地址
                                        //         window.location.href = data.url;
                                        //     } else {
                                        //         //刷新当前页
                                        //         reloadPage(window);
                                        //     }
                                        // }
                                    }
                                }
                            }).show();
                        }
                    });
                });

            });
        }

        //定义错误redirect 返回错误消息
        var code = $('body').data('code');
        if (code != '') {
            noty({
                text: code,
                type: 'warning',
                layout: 'topCenter',
                modal: true,
                timeout: 980,
                animation: {
                    open: 'animated bounceInDown', // Animate.css class names
                    close: 'animated bounceOutUp', // Animate.css class names
                },
                callback: {
                    afterClose: function () {
                        //刷新当前页
                        reloadPage(window);
                    }
                }
            }).show();
        }

        //获取当前url 活动菜单Id
        var parent_id = $('body').data('url_id');
        var active = $('body').data('active');
        if (parent_id != '' && active != 'admin/system/userindex') {
            $("#side-menu li[data-parent_id='" + parent_id + "']").addClass('active');
            $("#side-menu li[data-parent_id='" + parent_id + "'] ul").addClass('in');
            $("#side-menu li[data-parent_id='" + parent_id + "'] ul li[data-active='" + active + "']").addClass('active');
        }
    });

    //加载laydate插件
    Wind.css('laydate');
    Wind.use('laydate', function () {
        //选择开始日期
        laydate.render({
            elem: '.start_date'
        });
        //选择结束日期
        laydate.render({
            elem: '.end_date'
        });
    });

});


/**
 * 单个图片上传
 * @param dialog_title
 * @param input_selector
 */
function uploadOneImage(dialog_title, input_selector, type, name, image) {
    dialog_title = dialog_title ? dialog_title : '图片上传';
    input_selector = input_selector ? '#' + input_selector : '#js-thumbnail-input-preview';
    type = type ? type : 2;
    name = name ? '#' + name : '#js-thumbnail-input-name';
    image = image ? '#' + image : '';
    //打开弹窗清空session，获取新值存入session
    sessionStorage.clear();
    var oldname = $(name).val();
    sessionStorage.setItem('oldName', oldname);
    var d = dialog({
        title: dialog_title,
        icon: 'succeed',
        content: document.getElementById('artDialog'),
        id: new Date().getTime(),
        width: '600px',
        height: '350px',
        lock: true,
        fixed: true,
        background: "#CCCCCC",
        opacity: 0,
        cancelValue: '关闭',
        okValue: '确定',
        cancel: function () {
            backindex();
        },
        ok: function () {

            var iframewindow = document.getElementById('artDialogIframe').contentWindow;
            var num = iframewindow.get_selected_files();
            //判断是否上传图片
            if (num == 1) {
                //获得上传的值
                var data = JSON.parse(sessionStorage.getItem('newName'));
                $(name).val(data.message.thumbnail);
                if (type == 1) {//data.message.logo_url
                    $(input_selector).attr('src', data.message.thumbnail);
                } else {
                    $(image).attr('src', data.message.thumbnail);
                }
                backindex();
                return true;
            } else {
                layer.msg('请上传图片', {icon: 5});
                return false;
            }

        },
    });
    d.show();
    backindex();
}

//遮罩层加载
function backindex() {
    var back = '<div id="FIXED" style="position: fixed;top:0;left:0;right: 0;bottom: 0;background-color: rgba(0,0,0,0.25)"></div>';
    if (!$('#FIXED').length) {
        $('body').append(back);
    } else {
        $('#FIXED').remove();
    }

}

// 单个更新排序
function singleSort(obj, ajaxUrl, type) {
    var thisObj = $(obj);
    var idSelect = thisObj.data('id');
    var sort = $('#' + idSelect).data('sort');
    var previous = $('#' + idSelect).data('previous');
    var next = $('#' + idSelect).data('next');

    if (type == 1) {
        if (sort == previous) {
            sort = sort - 1;
        } else {
            sort = previous;
        }

    }
    if (type == 2) {
        if (sort == next) {
            sort = sort + 1;
        } else {
            sort = next;
        }

    }

    var ajaxData = {};
    ajaxData['id'] = idSelect;
    ajaxData['sort'] = sort;
    $.ajax({
        url: ajaxUrl,
        data: ajaxData,
        type: 'post',
        async: false,
        timeout: 2000,
        success: function (data1) {
            data1 = JSON.parse(data1);
            if (data1.status == 0) {
                layer.msg(data1.message, {icon: 5});
                return false;
            }

            if (data1.status == '1') {
                layer.msg(data1.message, {icon: 6});
                refresh();
            }
        },
        error: function (data) {
            layer.msg('服务器请求错误', {icon: 5});
            return false;
        }
    });

}

//更新排序
function listOrder(obj, ajaxUrl) {
    var thisObj = $(obj);
    var id = thisObj.data('id');
    var data = thisObj.val();
    if (data == null || data == '' || (isNaN(data)) || data < 0) {
        layer.msg('排序请输入大于1的正整数', {icon: 5});
        return false;
    }

    var ajaxData = {};
    ajaxData['id'] = id;
    ajaxData['sort'] = data;
    $.ajax({
        url: ajaxUrl,
        data: ajaxData,
        type: 'post',
        async: false,
        timeout: 2000,
        success: function (data1) {
            data1 = JSON.parse(data1);
            if (data1.status == 0) {
                layer.msg(data1.message, {icon: 5});
                return false;
            }

            if (data1.status == '1') {
                layer.msg(data1.message, {icon: 6});
                refresh();
            }
        },
        error: function (data) {
            layer.msg('服务器请求错误', {icon: 5});
            return false;
        }
    });
}

//刷新当前页面
function refresh() {
    window.location.reload(); //刷新当前页面.
}

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

/**
 * 确认弹框
 * @param title
 * @param content
 */
function checkValid(title, content) {
    //定义全局变量 开关
    var flag;
    $("*[data-validate]").each(function () {
        var require = $(this).attr("data-validate");
        var word = require.replace(/required:/, '');
        var finput = $.trim($(this).val());
        switch (true) {
            case ('' == finput):
                layer.msg(word, {icon: 5});
                flag = false;
                return false;
                break;
            default:
                flag = true;
        }
    });
    //假如flag 正确跳出弹框
    if (flag) {
        layer.confirm(content,
            {icon: 3, title: title},
            function (index) {
                Wind.use('noty', function () {
                    var ajaxForm_list = $('form.js-ajax-form');
                    var btn = $('button.js-ajax-confirm');
                    $.ajax({
                        url: btn.data('action') ? btn.data('action') : ajaxForm_list.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
                        data: ajaxForm_list.serialize(),
                        type: 'post',
                        dataType: 'json',
                        beforeSend: function () {
                            btn.data("loading", true);
                            var text = btn.text();
                            //按钮文案、状态修改
                            btn.text(text + '中...').prop('disabled', true).addClass('disabled');
                            layer.closeAll(); //关闭所有层
                        },
                        success: function (data1) {
                            var text = btn.text();
                            //按钮文案、状态修改
                            btn.removeClass('disabled').prop('disabled', false).text(text.replace('中...', '')).parent().find('span').remove();

                            function _refresh() {
                                if (data1.url) {
                                    //返回带跳转地址
                                    window.location.href = data1.url;
                                } else {
                                    if (data1.code == 1) {
                                        //刷新当前页
                                        reloadPage(window);
                                    }
                                }
                            }

                            if (data1.code == 1) {
                                noty({
                                    text: data1.content,
                                    type: 'success',
                                    layout: 'topCenter',
                                    modal: true,
                                    timeout: data1.time * 350,
                                    callback: {
                                        afterClose: function () {
                                            if (btn.data('refresh') == undefined || btn.data('refresh')) {
                                                _refresh();
                                            }
                                        }
                                    }
                                }).show();
                                $(window).focus();
                            } else if (data1.code == 0) {
                                noty({
                                    text: data1.content,
                                    type: 'error',
                                    layout: 'topCenter',
                                    modal: true,
                                    timeout: data1.time * 350,
                                    callback: {
                                        afterClose: function () {
                                            //_refresh();
                                        }
                                    }
                                }).show();
                                $(window).focus();
                            }


                        },
                        error: function (data) {
                            layer.msg('服务器请求错误', {offset: '30px', icon: 5});
                            return false;
                        }

                    });
                });

                return true;
            },
            function (index) {
                layer.msg('已取消！', {icon: 1});
                return false;
            }
        );
    }
    return false;
}

/**
 * 确认删除弹框
 * @param title
 * @param content
 * @returns {boolean}
 */
function checkDel(title, content, ajaxUrl, obj) {
    var thisObj = $(obj);
    var id = thisObj.data('id');
    layer.confirm(content,
        {icon: 3, title: title},
        function (index) {
            var ajaxData = {};
            ajaxData['id'] = id;
            $.ajax({
                url: ajaxUrl,
                data: ajaxData,
                type: 'post',
                async: false,
                timeout: 2000,
                success: function (data1) {
                    data1 = JSON.parse(data1);
                    if (data1.status == 0) {
                        layer.msg(data1.message, {icon: 5});
                        return false;
                    }

                    if (data1.status == '1') {
                        layer.msg(data1.message, {icon: 6});
                        refresh();
                    }
                },
                error: function (data) {
                    console.log('请求错误');
                    layer.msg('服务器请求错误', {icon: 5});
                    return false;
                }
            });
            return true;
        },
        function (index) {
            layer.msg('已取消！', {icon: 1});
            return false;
        }
    );
    return false;
}

/**
 * 信息错误弹窗
 * @param text
 */
function notyError(text = '提交错误') {
    noty({
        text: text,
        type: 'error',
        layout: 'topCenter',
        modal: true,
        timeout: 980,
        animation: {
            open: 'animated bounceInDown', // Animate.css class names
            close: 'animated bounceOutUp', // Animate.css class names
        },
        callback: {}
    }).show();
    return false;
}

//layer-iframe加载表单
function layerIframe(title, url) {
    //打开弹窗清空session，获取新值存入session
    sessionStorage.clear();

    //接受上传的值
    sessionStorage.setItem('iframe-url', url);
    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        offset: 'auto',
        shade: 0.8,
        resizing: true,
        area: ["50%", "70%"],
        content: url,
        btn: ['提交', '取消'],

        yes: function (index, layero) {
            //获取iframe的body元素
            var body = layer.getChildFrame('body', index);
            // 得到iframe页的窗口对象
            var iframeWin = window[layero.find('iframe')[0]['name']];
            //执行iframe页的showMsg方法
            iframeWin.iframeAjaxSubmit();
            //关闭弹窗并刷新当前页面
            // layer.closeAll();
            // refresh();
        },
        btn2: function (index, layero) {
            //关闭弹窗并刷新当前页面
            layer.closeAll();
        }
    });

}

//layer-iframe加载弹窗页面
function layerIframeLook(title, url) {
    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        offset: 'auto',
        shade: 0.8,
        resizing: true,
        area: ["50%", "70%"],
        content: url,
        btn: ['关闭'],
        yes: function (index, layero) {
            //关闭弹窗并刷新当前页面
            layer.closeAll();
        },
    });
}


/**
 * 查看图片对话框
 * @param img 图片地址
 */
function imagePreviewDialog(img) {
    layer.photos({
        photos: {
            "title": "", //相册标题
            "id": 'image_preview', //相册id
            "start": 0, //初始显示的图片序号，默认0
            "data": [   //相册包含的图片，数组格式
                {
                    "alt": "",
                    "pid": 666, //图片id
                    "src": img, //原图地址
                    "thumb": img //缩略图地址
                }
            ]
        } //格式见API文档手册页
        , anim: 5, //0-6的选择，指定弹出图片动画类型，默认随机
        shadeClose: true,
        // skin: 'layui-layer-nobg',
        shade: [0.5, '#000000'],
        shadeClose: true,
    })
}
