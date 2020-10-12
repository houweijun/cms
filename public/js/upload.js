/**
 * Created by own on 2018/6/10.
 */

//取消图片
$('.btn-cancel-thumbnail').click(function () {

    if($('#js-thumbnail-input-url').val() != ''){
        var ajaxStatus = delImg('/admin/asset/cancel', '', 1, 'logo_name', '');
        ajaxStatus = JSON.parse(ajaxStatus);

        if (ajaxStatus.status == 0) {
            layer.msg(ajaxStatus.message, {icon: 5});
            return false;
        }
        $('#js-thumbnail-input-preview').attr('src', '/img/default-thumbnail.png');
        $('#js-thumbnail-input-name').val('');
        $('#js-thumbnail-input-url').val('');
    }
});

//取消图片2

function cancelImage(Obj, ajaxUrl, type, arrName) {
    //获取ajax状态
    var selectInput = $('#js-thumbnail-input-name');
    var ajaxStatus = delImg(ajaxUrl, selectInput, type, arrName);
    if (ajaxStatus.status == 0) {
        layer.msg(ajaxStatus.message, {icon: 5});
        return false;
    }
    $('#js-thumbnail-input-preview').attr('src', '/img/default-thumbnail.png');
    $('#js-thumbnail-input-name').val('');
    $('#js-thumbnail-input-url').val('');
}

//时间选择器
laydate.render({
    elem: '.active_start_time'
    , type: 'datetime'
});
laydate.render({
    elem: '.active_end_time'
    , type: 'datetime'
});
laydate.render({
    elem: '.every_start_time'
    , type: 'time'

});
laydate.render({
    elem: '.every_end_time'
    , type: 'time'

});


laydate.render({
    elem: '.start_time'
    , type: 'datetime'
});
laydate.render({
    elem: '.end_time'
    , type: 'datetime'
});


laydate.render({
    elem: '.day_start_time'
    , type: 'time'

});
laydate.render({
    elem: '.day_end_time'
    , type: 'time'

});

//获取选项数据
function ajaxPropsSelect(ajaxURrl) {
    var propsHtml = '';
    $.ajax({
        url: ajaxURrl,
        data: {},
        type: 'post',
        dataType: 'json',
        async: false,
        timeout: 2000,
        success: function (data) {
            if (data.status == 1) {

                $.each(data.message, function (k, v) {
                    propsHtml += '<option value="' + this.id + '">' + this.name + '</option>';
                });

            }
        },
        error: function (data) {
            console.log('请求错误');
        }
    });

    return propsHtml;
}

function addAward() {
    var dayCount = $('[name="day_count"]').val();
    if (dayCount == null || dayCount == '' || (isNaN(dayCount)) || dayCount < 0) {
        layer.msg('添加失败，天数请输入大于1的正整数', {icon: 5});
        return false;
    }

    var size = $('#pannel-external>li').size();
    var num = size + 1;
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var html = ' <li>'+
        '                            <div class="panel panel-default">'+
        '                                <div class="panel-heading">'+
        '                                    第'+ num +'天奖励'+
        '                                </div>'+
        '                                <div class="panel-body">'+
        '                                    <ul id="inside-content-'+ num +'" data-inside="'+ num +'">'+
        '                                        <li data-list="1" class="li-bd-gray">'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">选择道具：</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <select class="form-control m-b border-left" name="props_id_'+ num +'[]">'+
        propsHtml +
        '                                                    </select>'+
        '                                                    <div class="tips"></div>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">道具数量：</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <input type="text" class="form-control input input-big border-left"'+
        '                                                           placeholder="请输入道具数量" name="props_num_'+ num +'[]"'+
        '                                                    />'+
        '                                                    <div class="tips"></div>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">上传道具图片:</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <input type="hidden" class="input default-read inside-content-name"'+
        '                                                           value="" name="image_name_'+ num +'[]" data-time=""'+
        '                                                           id="inside-content-'+ num +'-1-name"/>'+
        '                                                    <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+ num +'[]"'+
        '                                                           id="inside-content-'+ num +'-1-url"/>'+
        '                                                    <input type="hidden" class="input default-read" value="" name="image_id_'+ num +'[]"'+
        '                                                           />'+
        '                                                    <img src="/img/default-thumbnail.png"'+
        '                                                         id="inside-content-'+ num +'-1-image" width="135">'+
        '                                                    <br/>'+
        '                                                    <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+ num +'-1"'+
        '                                                       onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                                class="fa fa-image"></i>上传图片</a>'+
        '                                                    <a class="btn btn-outline btn-primary image-cursor"'+
        '                                                       onclick="addSubAward(this)"><i'+
        '                                                                class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                                    <a class="btn btn-outline btn-danger image-cursor"'+
        '                                                       onclick="delSubAward(this,\'/admin/ativemission/missionajaxdprops\',3,\'image_name\')"'+
        '                                                       data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                        </li>'+
        '                                    </ul>'+
        '                                    <a class="btn btn-outline btn-warning delAward-btn"'+
        '                                       data-id="'+ num +'"'+
        '                                       onclick="delAward(this,\'/admin/ativemission/missionajaxdprops\',2,\'image_name_\')"><i class="fa fa-bank"></i>删除</a>'+
        '                                    <input type="hidden" class="input" name="day_num_'+ num +'" value="'+ num +'"'+
        '                                    />'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>';
    if (num <= dayCount) {
        $(html).appendTo($('#pannel-external'));
    } else {
        layer.msg('添加失败,已经超过填写活动' + dayCount + '天', {icon: 5});
    }


}

//删除节点
function delAward(obj, ajaxUrl, type, arrName) {
    var size = $('#pannel-external>li').size();
    var thisObj = $(obj);
    var dataId = thisObj.attr("data-id");
    switch (true) {
        case (dataId == 1):
            layer.msg('删除失败，第1天奖励不能删', {icon: 5});
            break;
        case (dataId < size && dataId != 1):
            layer.msg('删除失败，请按从下到上顺序删除', {icon: 5});
            break;
        default:
            //获取ajax状态
            var ajaxSeletN = thisObj.data('id');
            var ajaxStatus = delImg(ajaxUrl, 'image_name_' + ajaxSeletN + '[]', type, arrName + ajaxSeletN, ajaxSeletN);
            if (ajaxStatus.status == 0) {
                layer.msg(ajaxStatus.message, {icon: 5});
                return false;
            }

            $('#pannel-external>li:nth-child(' + dataId + ')').remove();
            var sizeN = $('#pannel-external>li').size();
            $("input[name='day_count']").val(sizeN);
            $('input[name="check_num"]').val(sizeN);
    }
}

//添加子道具里
function addSubAward(subObj) {
    var thisObj = $(subObj);
    var inside = thisObj.parent().parent().parent().parent();
    var list = inside.find('li').last().data('list');
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');

    var insideId = inside.data('inside');
    var num = list + 1;

    var subHtml = ' <li data-list="' + num +'" class="li-bd-gray">'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">选择道具：</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <select class="form-control m-b border-left" name="props_id_'+ insideId +'[]">'+
        propsHtml +
        '                                                    </select>'+
        '                                                    <div class="tips"></div>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">道具数量：</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <input type="text" class="form-control input input-big border-left"'+
        '                                                           placeholder="请输入道具数量" name="props_num_'+ insideId +'[]"'+
        '                                                    />'+
        '                                                    <div class="tips"></div>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                            <div class="form-group">'+
        '                                                <label class="col-sm-2 control-label">'+
        '                                                    <span class="text-inherit">上传道具图片:</span>'+
        '                                                </label>'+
        '                                                <div class="col-sm-10 col-md-10 field">'+
        '                                                    <input type="hidden" class="input default-read inside-content-name"'+
        '                                                           value="" name="image_name_'+ insideId +'[]" data-time=""'+
        '                                                           id="inside-content-'+insideId+'-'+num+'-name"/>'+
        '                                                    <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+insideId+'[]"'+
        '                                                           id="inside-content-'+insideId+'-'+num+'-url"/>'+
        '                                                    <input type="hidden" class="input default-read" value="" name="image_id_' + insideId +'[]"'+
        '                                                           />'+
        '                                                    <img src="/img/default-thumbnail.png"'+
        '                                                         id="inside-content-' + +insideId + '-' + num + '-image" width="135">'+
        '                                                    <br/>'+
        '                                                    <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+insideId+'-'+num+'"'+
        '                                                       onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                                class="fa fa-image"></i>上传图片</a>'+
        '                                                    <a class="btn btn-outline btn-primary image-cursor"'+
        '                                                       onclick="addSubAward(this)"><i'+
        '                                                                class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                                    <a class="btn btn-outline btn-danger image-cursor"'+
        '                                                       onclick="delSubAward(this,\'/admin/ativemission/missionajaxdprops\',3,\'image_name\')"'+
        '                                                       data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                                </div>'+
        '                                            </div>'+
        '                                        </li>';
    $(subHtml).appendTo(inside);

}

//删除子道具
function delSubAward(subObj, ajaxUrl, type, arrName) {
    var thisObj = $(subObj);
    var inside = thisObj.parent().parent().parent().parent();
    var parentEle = thisObj.parent().parent().parent();
    var listSize = inside.find('>li').size();
    var insideNum = inside.data('inside');
    var listNum = parentEle.data('list');


    switch (true) {
        case (listSize == 1):
            layer.msg('删除失败，最少保留1个子道具', {icon: 5});
            break;

        default:
            //获取ajax状态
            var ajaxSeletN = inside.data('inside');
            var selectInput = $('#inside-content-' + insideNum + '-' + listNum + '-name');
            var ajaxStatus = delImg(ajaxUrl, selectInput, type, arrName, ajaxSeletN);
            if (ajaxStatus.status == 0) {
                layer.msg(ajaxStatus.message, {icon: 5});
                return false;
            }
            $(parentEle).remove();
    }
}


//获取数组name值
function getValues(name) {
    var arr = [];
    $(name).each(function (t) {
        arr.push($(this).val());
    })
    return arr;
}

//获取数据name dataCreate创建时间值
function getCreateTimes(name) {
    var arr = [];
    $(name).each(function (t) {
        arr.push($(this).data('time'));
    })
    return arr;
}

//ajax请求删除图片
function delImg(ajaxUrl, imageSelect, type, arrName, dayNum, dataId, selectField) {
    var dataInfo = '';
    var ajaxData = {};
    dayNum = dayNum ? dayNum : '';
    imageSelect = imageSelect ? imageSelect : '';
    dataId = dataId ? dataId : '';
    selectField = selectField ? selectField : '';
    if (ajaxUrl == '' || ajaxUrl == null || ajaxUrl == undefined) {
        return false;
    }

    if (type != 1) {
        if (imageSelect == '' || imageSelect == null || imageSelect == undefined) {
            return false;
        }
    }

    if (arrName == '' || arrName == null || arrName == undefined) {
        return false;
    }


    type = type ? type : 1;
    if (type == 1) {
        ajaxData[arrName] = $('input[name=logo_name]').val();
    }


    var id = $('input[name=id]').val();

    if (id != null && id != '' && !(isNaN(id)) && id != undefined) {
        ajaxData['id'] = id;
    }


    //获取当前天所有节点
    if (type == 2) {
        if (dayNum == null || dayNum == '' || (isNaN(dayNum)) || dayNum < 0) {
            return false;
        }

        var dataSelect = document.getElementsByName(imageSelect);
        var arr = getValues(dataSelect);
        var timeSelect = document.getElementsByName(imageSelect);
        var arrTime = getCreateTimes(timeSelect);
        ajaxData['created_at'] = arrTime;
        ajaxData[arrName] = arr;
        ajaxData['day_num'] = dayNum;
    }

    //获取当前节点
    if (type == 3) {
        if (dayNum == null || dayNum == '' || (isNaN(dayNum)) || dayNum < 0) {
            return false;
        }
        ajaxData['created_at_s'] = $(imageSelect).data('time');
        ajaxData[arrName] = $(imageSelect).val();
        ajaxData['day_num'] = dayNum;
    }

    //获取当前节点
    if (type == 4) {
        ajaxData['imgs_name'] = $(imageSelect).val();
    }

    //获取单个图片
    if (type == 5) {
        ajaxData[arrName] = $(imageSelect).val();
    }

    //获取更新删除图片
    if (type == 6) {
        if (dataId == '' || dataId == undefined) {
            dataId = '';
        }
        ajaxData[arrName] = $(imageSelect).val();
        ajaxData['id'] = dataId;
        ajaxData[selectField] = selectField;
    }

    //获取更新删除整个道具组
    if (type == 7) {
        if (dataId == '' || dataId == undefined) {
            dataId = '';
        }

        var imageNameStr = arrName + '_' + dayNum + '[]';
        var imageNameSelect = document.getElementsByName(imageNameStr);
        var imageName = getValues(imageNameSelect);
        ajaxData[arrName] = imageName;

        var taxImageNameStr = 'tax_' + arrName + '_' + dayNum + '[]';
        var taxImageNameSelect = document.getElementsByName(taxImageNameStr);
        var taxImageName = getValues(taxImageNameSelect);
        ajaxData['tax_' + arrName] = taxImageName;

        ajaxData['id'] = dataId;
    }

    //获取更新删除整个道具组 充值额度面板组

    if (type == 8) {
        if (dataId == '' || dataId == undefined) {
            dataId = '';
        }

        var imageNameStr = arrName + '_' + dayNum + '[]';
        var imageNameSelect = document.getElementsByName(imageNameStr);
        var imageName = getValues(imageNameSelect);
        ajaxData[arrName] = imageName;
        ajaxData['id'] = dataId;
    }

    //删除图片 节点
    if(type == 9){
        ajaxData['id'] = dataId;
        ajaxData[arrName] = $(imageSelect).val();
    }

    //ajax请求
    $.ajax({
        url: ajaxUrl,
        data: ajaxData,
        type: 'post',
        async: false,
        timeout: 2000,
        success: function (data1) {
            dataInfo = data1;


        },
        error: function (data) {
            console.log('请求错误');
            layer.msg('服务器请求错误', {icon: 5});
            return false;
        }
    });

    return dataInfo;
}


//设置当前节点检查天数
function checkDayNum() {
    var size = $('#pannel-external>li').size();
    $('input[name="check_num"]').val(size);
}


//添加图片
function addImage(Obj) {
    var thisObj = $(Obj);
    var parentUl = thisObj.parent().parent();
    var size = parentUl.find('li').size();
    var num = parentUl.find('li').last().data('id') + 1;
    if (size >= 4) {
        layer.msg('最多添加4张图片', {icon: 5});
        return false;
    }
    var html = '<li data-id="'+num+'">'+
        '                            <div class="tp-img">'+
        '                                <input type="hidden" class="input w40 default-read" value=""'+
        '                                       name="imgs_name[]" id="content-'+num+'-name"'+
        '                                />'+
        '                                <input type="hidden" class="input w40 default-read" value="" name="imgs_url[]"'+
        '                                       id="content-'+num+'-url"'+
        '                                />'+
        '                                <img src="/img/default-thumbnail.png"'+
        '                                     id="content-'+num+'-image" width="135">'+
        '                            </div>'+
        '                                <a class="btn btn-outline btn-default image-cursor" id="content-'+num+'" onclick="uploadOneImage(\'实物道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i class="fa fa-image"></i>上传图片</a>'+
        '                                <a class="btn btn-outline btn-primary image-cursor" onclick="addImage(this)"><i class="fa fa-plus-square-o"></i>添加实物道具图片</a>'+
        '                                <a class="btn btn-outline btn-danger image-cursor" onclick="delImage(this,\'/admin/propsmanage/ajaxdprops\',4,\'image_name\')"><i class="fa fa-trash-o fa-lg"></i>删除实物道具图片</a>'+
        '                        </li>';

    $(html).appendTo(parentUl);
}

//删除图片
function delImage(Obj, ajaxUrl, type, arrName) {
    var thisObj = $(Obj);
    var parentUl = thisObj.parent().parent();
    var parentLi = thisObj.parent();
    var size = parentUl.find('li').size();
    var num = parentLi.data('id');


    switch (true) {
        case (size == 1):
            layer.msg('最少保留1张图片', {icon: 5});
            break;

        default:
            //获取ajax状态
            var selectInput = $('#content-' + num + '-name');
            var ajaxStatus = delImg(ajaxUrl, selectInput, type, arrName);
            if (ajaxStatus.status == 0) {
                layer.msg(ajaxStatus.message, {icon: 5});
                return false;
            }
            $(parentLi).remove();
    }

}

//添加道具组
function addProps() {

    var parentUl = $('#props-external');
    var num = parentUl.find('li').last().data('list') + 1;
    var propsHtml = ajaxPropsSelect('/admin/propsmanage/ajaxsprops');
    var html = '<li data-list="'+num+'">'+
        '    <div class="form-group">'+
        '        <label class="col-sm-2 control-label">'+
        '            <span class="text-inherit">选择道具：</span>'+
        '        </label>'+
        '        <div class="col-sm-10 col-md-10 field">'+
        '            <select class="form-control m-b border-left" name="props_id[]">'+
        propsHtml +
        '            </select>'+
        '            <div class="tips"></div>'+
        '        </div>'+
        '    </div>'+
        '    <div class="form-group">'+
        '        <label class="col-sm-2 control-label">'+
        '            <span class="text-inherit">道具数量：</span>'+
        '        </label>'+
        '        <div class="col-sm-10 col-md-10 field">'+
        '            <input type="text" class="form-control input input-big border-left"'+
        '                   placeholder="请输入道具数量" id="props_num_'+num+'" name="props_num[]"'+
        '            />'+
        '            <div class="tips"></div>'+
        '        </div>'+
        '    </div>'+
        '    <div class="form-group">'+
        '        <label class="col-sm-2 control-label">'+
        '            <span class="text-inherit">道具概率:</span>'+
        '        </label>'+
        '        <div class="col-sm-10 col-md-10 field">'+
        '            <input type="text" class="form-control input input-big border-left"'+
        '                   placeholder="请输入道具概率" id="props_odds_'+num+'"'+
        '                   name="props_odds[]"'+
        '            />'+
        '            <div class="tips"></div>'+
        '            <a class="btn btn-outline btn-primary image-cursor"'+
        '               onclick="addProps(this)"><i'+
        '                        class="fa fa-plus-square-o"></i>加新道具</a>'+
        '            <a class="btn btn-outline btn-danger image-cursor"'+
        '               onclick="delProps(this)"'+
        '               data-id="'+num+'"><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '        </div>'+
        '    </div>'+
        '</li>';

    $(html).appendTo(parentUl);
}


//礼包组道具组
function addGiftProps() {
    var parentUl = $('#props-external');
    var num = parentUl.find('li').last().data('list') + 1;
    var propsHtml = ajaxPropsSelect('/admin/propsmanage/ajaxsprops');
    var html = '<li data-list="'+num+'">'+
        '    <div class="form-group">'+
        '        <label class="col-sm-2 control-label">'+
        '            <span class="text-inherit">选择道具：</span>'+
        '        </label>'+
        '        <div class="col-sm-10 col-md-10 field">'+
        '            <select class="form-control m-b border-left" name="props_id[]">'+
        propsHtml +
        '            </select>'+
        '            <div class="tips"></div>'+
        '        </div>'+
        '    </div>'+
        '    <div class="form-group">'+
        '        <label class="col-sm-2 control-label">'+
        '            <span class="text-inherit">道具数量：</span>'+
        '        </label>'+
        '        <div class="col-sm-10 col-md-10 field">'+
        '            <input type="text" class="form-control input input-big border-left"'+
        '                   placeholder="请输入道具数量" id="props_num_'+num+'" name="props_num[]"'+
        '            />'+
        '            <a class="btn btn-outline btn-primary image-cursor"'+
        '               onclick="addGiftProps(this)"><i'+
        '                        class="fa fa-plus-square-o"></i>加新道具</a>'+
        '            <a class="btn btn-outline btn-danger image-cursor"'+
        '               onclick="delProps(this)"'+
        '               data-id="'+num+'"><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '            <div class="tips"></div>'+
        '        </div>'+
        '    </div>'+
        '</li>';

    $(html).appendTo(parentUl);
}

//删除道具组
function delProps(obj) {
    var parentUl = $('#props-external');
    var size = parentUl.find('li').size();
    var thisObj = $(obj);
    var parentLi = thisObj.parent().parent().parent();
    switch (true) {
        case (size == 1):
            layer.msg('最少保留1个道具', {icon: 5});
            break;

        default:
            $(parentLi).remove();
    }

}


/**
 * 增加另一个 子选项
 * @param obj
 */
function addAnotherSub(obj) {
    var thisObj = $(obj);
    var outsideObj = thisObj.parent().parent().parent().parent();
    var sideType = outsideObj.data('side');
    var prefix = outsideObj.data('prefix');
    var list = outsideObj.find('li').last().data('list');
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var outsideId = outsideObj.data(sideType);
    console.log(outsideId);
    var num = list + 1;
    var subHtml = ' <li data-list="' + num +'">'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">选择道具：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <select class="form-control m-b border-left" name="'+ prefix +'props_id_'+outsideId+'[]">'+
        propsHtml +
        '                                                            </select>'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">道具数量：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="text" class="form-control input input-big border-left"'+
        '                                                                   placeholder="请输入道具数量" name="' + prefix +'props_num_'+ outsideId +'[]"'+
        '                                                            />'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">上传道具图片:</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="hidden" class="input default-read '+sideType+'-content-name"'+
        '                                                                   value="" name="'+prefix+'image_name_'+outsideId+'[]" data-time=""'+
        '                                                                   id="'+sideType+'-content-'+outsideId+'-'+num+'-name"/>'+
        '                                                            <input type="hidden" class="input default-read '+sideType+'-content-name" value="" name="'+prefix+'image_url_'+outsideId+'[]"'+
        '                                                                   id="'+sideType+'-content-'+outsideId+'-'+num+'-url"/>'+
        '                                                            <img src="/img/default-thumbnail.png"'+
        '                                                                 id="'+sideType+'-content-'+outsideId+'-'+num+'-image" width="135">'+
        '                                                            <br/>'+
        '                                                            <a class="btn btn-outline btn-default image-cursor" id="'+sideType+'-content-'+outsideId+'-'+ num +'"'+
        '                                                               onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                                        class="fa fa-image"></i>上传图片</a>'+
        '                                                            <a class="btn btn-outline btn-primary image-cursor"'+
        '                                                               onclick="addAnotherSub(this)"><i'+
        '                                                                        class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                                            <a class="btn btn-outline btn-danger image-cursor"'+
        '                                                               onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                                               data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                </li>';
    $(subHtml).appendTo(outsideObj);
}


/**
 * 删除子道具 更新
 * @param subObj
 * @param ajaxUrl
 * @param type
 * @param selectInput
 * @param arrName
 */
function delAnotherSub(subObj, ajaxUrl, type, arrName) {
    var thisObj = $(subObj);
    var id = thisObj.data('id');
    var side = thisObj.parent().parent().parent().parent();
    var sideStr = side.data('side');
    var parentEle = thisObj.parent().parent().parent();
    var listSize = side.find('>li').size();
    var sideNum = side.data(sideStr);
    var listNum = parentEle.data('list');
    var selectField = side.data('field');
    ajaxUrl = ajaxUrl ? ajaxUrl : side.data('url');
    switch (true) {
        case (listSize == 1):
            layer.msg('删除失败，最少保留1个子道具', {icon: 5});
            break;
        default:
            //获取ajax
            var ajaxSelectN = side.data(sideStr);

            var selectInput = $('#' + sideStr + '-content-' + sideNum + '-' + listNum + '-name');

            var imageData = selectInput.val();

            if (imageData != '' || imageData != undefined) {
                var ajaxStatus = delImg(ajaxUrl, selectInput, type, arrName, ajaxSelectN, id, selectField);
                if (ajaxStatus.status == 0) {
                    layer.msg(ajaxStatus.message, {icon: 5});
                    return false;
                }
            }

            $(parentEle).remove();


    }

}

/**
 * 添加税换条件、奖励面板组
 */
function addAnotherProps() {
    var size = $('#pannel-external>li').size();
    var num = size + 1;
    $("input[name='count']").val(num);
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var html = ' <li>'+
        '                            <div class="panel panel-default">'+
        '                                <div class="panel-heading">'+
        '                                    税换条件、奖励面板组'+num+
        '                                </div>'+
        '                                <div class="panel-body">'+
        '                                    <div class="panel panel-info">'+
        '                                        <div class="panel-heading">'+
        '                                            <h3 class="panel-title">税换条件'+num+'</h3>'+
        '                                        </div>'+
        '                                        <div class="panel-body">'+
        '                                            <ul id="num-content-'+num+'">'+
        '                                                <li class="li-border">'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">兑换次数：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="text" class="form-control input input-big border-left"'+
        '                                                                   placeholder="请输入填写税换次数" name="num_'+num+'"'+
        '                                                                   data-validate="required:请输入填写税换次数"'+
        '                                                            />'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                </li>'+
        '                                            </ul>'+
        '                                            <ul id="inside-content-'+num+'" data-inside="'+num+'" data-side="inside"'+
        '                                                data-prefix="" data-field="change" data-url="">'+
        '                                                <li data-list="1">'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">选择道具：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <select class="form-control m-b border-left" name="props_id_'+num+'[]">'+
        propsHtml+
        '                                                            </select>'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">道具数量：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="text" class="form-control input input-big border-left"'+
        '                                                                   placeholder="请输入道具数量" name="props_num_'+num+'[]"'+
        '                                                            />'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">上传道具图片:</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="hidden" class="input default-read inside-content-name"'+
        '                                                                   value="" name="image_name_'+num+'[]" data-time=""'+
        '                                                                   id="inside-content-'+num+'-1-name"/>'+
        '                                                            <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+num+'[]"'+
        '                                                                   id="inside-content-'+num+'-1-url"/>'+
        '                                                            <img src="/img/default-thumbnail.png"'+
        '                                                                 id="inside-content-'+num+'-1-image" width="135">'+
        '                                                            <br/>'+
        '                                                            <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+num+'-1"'+
        '                                                               onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                                        class="fa fa-image"></i>上传图片</a>'+
        '                                                            <a class="btn btn-outline btn-primary image-cursor"'+
        '                                                               onclick="addAnotherSub(this)"><i'+
        '                                                                        class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                                            <a class="btn btn-outline btn-danger image-cursor"'+
        '                                                               onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                                               data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                </li>'+
        '                                            </ul>'+
        '                                        </div>'+
        '                                    </div>'+
        '                                    <div class="panel panel-warning">'+
        '                                        <div class="panel-heading">'+
        '                                            <h3 class="panel-title">税换奖励'+num+'</h3>'+
        '                                        </div>'+
        '                                        <div class="panel-body">'+
        '                                            <ul id="outside-content-'+num+'" data-outside="'+num+'" data-side="outside"'+
        '                                                data-prefix="tax_" data-field="reward" data-url="">'+
        '                                                <li data-list="1">'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">选择道具：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <select class="form-control m-b border-left" name="tax_props_id_'+num+'[]">'+
        propsHtml+
        '                                                            </select>'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">道具数量：</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="text" class="form-control input input-big border-left"'+
        '                                                                   placeholder="请输入道具数量" name="tax_props_num_'+num+'[]"'+
        '                                                            />'+
        '                                                            <div class="tips"></div>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                    <div class="form-group">'+
        '                                                        <label class="col-sm-2 control-label">'+
        '                                                            <span class="text-inherit">上传道具图片:</span>'+
        '                                                        </label>'+
        '                                                        <div class="col-sm-10 col-md-10 field">'+
        '                                                            <input type="hidden" class="input default-read inside-content-name"'+
        '                                                                   value="" name="tax_image_name_'+num+'[]" data-time=""'+
        '                                                                   id="outside-content-'+num+'-1-name"/>'+
        '                                                            <input type="hidden" class="input default-read inside-content-name" value="" name="tax_image_url_'+num+'[]"'+
        '                                                                   id="outside-content-'+num+'-1-url"/>'+
        '                                                            <img src="/img/default-thumbnail.png"'+
        '                                                                 id="outside-content-'+num+'-1-image" width="135">'+
        '                                                            <br/>'+
        '                                                            <a class="btn btn-outline btn-default image-cursor" id="outside-content-'+num+'-1"'+
        '                                                               onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                                        class="fa fa-image"></i>上传图片</a>'+
        '                                                            <a class="btn btn-outline btn-primary image-cursor"'+
        '                                                               onclick="addAnotherSub(this)"><i'+
        '                                                                        class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                                            <a class="btn btn-outline btn-danger image-cursor"'+
        '                                                               onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                                               data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                                        </div>'+
        '                                                    </div>'+
        '                                                </li>'+
        '                                            </ul>'+
        '                                        </div>'+
        '                                    </div>'+
        '                                    <a class="btn btn-outline btn-warning delAward-btn"'+
        '                                       data-id="" data-num="'+num+'"    '+
        '                                       onclick="delAnotherProps(this,\'/admin/activemanage/changeajaxdel\',7,\'image_name\')"><i class="fa fa-bank"></i>删除</a>'+
        '                                    <input type="hidden" class="input" name="sub_id_'+num+'" value=""'+
        '                                    />'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>';

    $(html).appendTo($('#pannel-external'));
}

/**
 * 添加充值额度面板组
 */
function addAnotherSingleProps() {
    var size = $('#pannel-external>li').size();
    var num = size + 1;
    $("input[name='count']").val(num);
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var html = '<li>'+
        '    <div class="panel panel-default">'+
        '        <div class="panel-heading">'+
        '            充值额度面板组'+  num +
        '        </div>'+
        '        <div class="panel-body">'+
        '            <div class="panel panel-info">'+
        '                <div class="panel-heading">'+
        '                    <h3 class="panel-title">奖励'+ num +'</h3>'+
        '                </div>'+
        '                <div class="panel-body">'+
        '                    <ul id="num-content-'+ num +'">'+
        '                        <li class="li-border">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">充值额度：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入填写充值额度" name="pay_amount_'+ num +'"'+
        '                                           data-validate="required:请输入填写充值额度"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                                <div class="fw_bt pull-center">'+
        '                                    <span class="form-required">*备注</span>'+
        '                                    <span class="bt_span">单位:(人民币)</span>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">可领次数：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入填写可领次数" name="num_'+ num + '"'+
        '                                           data-validate="required:请输入填写可领次数"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                    <ul id="inside-content-'+num+'" data-inside="'+num+'" data-side="inside"'+
        '                        data-prefix="" data-field="change" data-url="">'+
        '                        <li data-list="1">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">选择道具：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <select class="form-control m-b border-left" name="props_id_'+num+'[]">'+
        propsHtml+
        '                                    </select>'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">道具数量：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入道具数量" name="props_num_'+num+'[]"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">上传道具图片:</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="hidden" class="input default-read inside-content-name"'+
        '                                           value="" name="image_name_'+num+'[]" data-time=""'+
        '                                           id="inside-content-'+num+'-1-name"/>'+
        '                                    <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+num+'[]"'+
        '                                           id="inside-content-'+num+'-1-url"/>'+
        '                                    <img src="/img/default-thumbnail.png"'+
        '                                         id="inside-content-'+num+'-1-image" width="135">'+
        '                                    <br/>'+
        '                                    <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+num+'-1"'+
        '                                       onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                class="fa fa-image"></i>上传图片</a>'+
        '                                    <a class="btn btn-outline btn-primary image-cursor"'+
        '                                       onclick="addAnotherSub(this)"><i'+
        '                                                class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                    <a class="btn btn-outline btn-danger image-cursor"'+
        '                                       onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                       data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                </div>'+
        '            </div>'+
        '            <a class="btn btn-outline btn-warning delAward-btn"'+
        '               data-id="" data-num="'+num+'"'+
        '               onclick="delAnotherProps(this,\'/admin/activemanage/singleajaxdel\',8,\'image_name\')"><i class="fa fa-bank"></i>删除</a>'+
        '            <input type="hidden" class="input" name="sub_id_'+num+'" value=""'+
        '            />'+
        '        </div>'+
        '    </div>'+
        '</li>';
    $(html).appendTo($('#pannel-external'));
}

/**
 * 添加充值额度面板组
 */
function addAnotherAccumulateProps() {
    var size = $('#pannel-external>li').size();
    var num = size + 1;
    $("input[name='count']").val(num);
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var html =  '<li>'+
        '    <div class="panel panel-default">'+
        '        <div class="panel-heading">'+
        '            充值额度面板组'+  num +
        '        </div>'+
        '        <div class="panel-body">'+
        '            <div class="panel panel-info">'+
        '                <div class="panel-heading">'+
        '                    <h3 class="panel-title">奖励'+ num +'</h3>'+
        '                </div>'+
        '                <div class="panel-body">'+
        '                    <ul id="num-content-'+ num +'">'+
        '                        <li class="li-border">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">充值额度：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入填写充值额度" name="pay_amount_'+ num +'"'+
        '                                           data-validate="required:请输入填写充值额度"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                                <div class="fw_bt pull-center">'+
        '                                    <span class="form-required">*备注</span>'+
        '                                    <span class="bt_span">单位:(人民币)</span>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                    <ul id="inside-content-'+num+'" data-inside="'+num+'" data-side="inside"'+
        '                        data-prefix="" data-field="change" data-url="">'+
        '                        <li data-list="1">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">选择道具：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <select class="form-control m-b border-left" name="props_id_'+num+'[]">'+
        propsHtml+
        '                                    </select>'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">道具数量：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入道具数量" name="props_num_'+num+'[]"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">上传道具图片:</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="hidden" class="input default-read inside-content-name"'+
        '                                           value="" name="image_name_'+num+'[]" data-time=""'+
        '                                           id="inside-content-'+num+'-1-name"/>'+
        '                                    <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+num+'[]"'+
        '                                           id="inside-content-'+num+'-1-url"/>'+
        '                                    <img src="/img/default-thumbnail.png"'+
        '                                         id="inside-content-'+num+'-1-image" width="135">'+
        '                                    <br/>'+
        '                                    <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+num+'-1"'+
        '                                       onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                class="fa fa-image"></i>上传图片</a>'+
        '                                    <a class="btn btn-outline btn-primary image-cursor"'+
        '                                       onclick="addAnotherSub(this)"><i'+
        '                                                class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                    <a class="btn btn-outline btn-danger image-cursor"'+
        '                                       onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                       data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                </div>'+
        '            </div>'+
        '            <a class="btn btn-outline btn-warning delAward-btn"'+
        '               data-id="" data-num="'+num+'"'+
        '               onclick="delAnotherProps(this,\'/admin/activemanage/singleajaxdel\',8,\'image_name\')"><i class="fa fa-bank"></i>删除</a>'+
        '            <input type="hidden" class="input" name="sub_id_'+num+'" value=""'+
        '            />'+
        '        </div>'+
        '    </div>'+
        '</li>';
    $(html).appendTo($('#pannel-external'));
}

/**
 * 添加充值额度面板组
 */
function addAnotherConsumeProps() {
    var size = $('#pannel-external>li').size();
    var num = size + 1;
    $("input[name='count']").val(num);
    var propsHtml = ajaxPropsSelect('/admin/ativemission/missionajaxprops');
    var html = '<li>'+
        '    <div class="panel panel-default">'+
        '        <div class="panel-heading">'+
        '            充值额度面板组'+  num +
        '        </div>'+
        '        <div class="panel-body">'+
        '            <div class="panel panel-info">'+
        '                <div class="panel-heading">'+
        '                    <h3 class="panel-title">奖励'+ num +'</h3>'+
        '                </div>'+
        '                <div class="panel-body">'+
        '                    <ul id="num-content-'+ num +'">'+
        '                        <li class="li-border">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">消费额度：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入填写消费额度" name="consume_diamond_'+ num +'"'+
        '                                           data-validate="required:请输入填写消费额度"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                                <div class="fw_bt pull-center">'+
        '                                    <span class="form-required">*备注</span>'+
        '                                    <span class="bt_span">单位:(人民币)</span>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                    <ul id="inside-content-'+num+'" data-inside="'+num+'" data-side="inside"'+
        '                        data-prefix="" data-field="change" data-url="">'+
        '                        <li data-list="1">'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">选择道具：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <select class="form-control m-b border-left" name="props_id_'+num+'[]">'+
        propsHtml+
        '                                    </select>'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">道具数量：</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="text" class="form-control input input-big border-left"'+
        '                                           placeholder="请输入道具数量" name="props_num_'+num+'[]"'+
        '                                    />'+
        '                                    <div class="tips"></div>'+
        '                                </div>'+
        '                            </div>'+
        '                            <div class="form-group">'+
        '                                <label class="col-sm-2 control-label">'+
        '                                    <span class="text-inherit">上传道具图片:</span>'+
        '                                </label>'+
        '                                <div class="col-sm-10 col-md-10 field">'+
        '                                    <input type="hidden" class="input default-read inside-content-name"'+
        '                                           value="" name="image_name_'+num+'[]" data-time=""'+
        '                                           id="inside-content-'+num+'-1-name"/>'+
        '                                    <input type="hidden" class="input default-read inside-content-name" value="" name="image_url_'+num+'[]"'+
        '                                           id="inside-content-'+num+'-1-url"/>'+
        '                                    <img src="/img/default-thumbnail.png"'+
        '                                         id="inside-content-'+num+'-1-image" width="135">'+
        '                                    <br/>'+
        '                                    <a class="btn btn-outline btn-default image-cursor" id="inside-content-'+num+'-1"'+
        '                                       onclick="uploadOneImage(\'道具图片上传\',this.id,2,this.id+\'-name\',this.id+\'-url\',this.id+\'-image\')"><i'+
        '                                                class="fa fa-image"></i>上传图片</a>'+
        '                                    <a class="btn btn-outline btn-primary image-cursor"'+
        '                                       onclick="addAnotherSub(this)"><i'+
        '                                                class="fa fa-plus-square-o"></i>添加道具</a>'+
        '                                    <a class="btn btn-outline btn-danger image-cursor"'+
        '                                       onclick="delAnotherSub(this,\'/admin/activemanage/changeajaxdelsub\',6,\'image_name\')"'+
        '                                       data-id><i class="fa fa-trash-o fa-lg"></i>删除道具</a>'+
        '                                </div>'+
        '                            </div>'+
        '                        </li>'+
        '                    </ul>'+
        '                </div>'+
        '            </div>'+
        '            <a class="btn btn-outline btn-warning delAward-btn"'+
        '               data-id="" data-num="'+num+'"'+
        '               onclick="delAnotherProps(this,\'/admin/activemanage/singleajaxdel\',8,\'image_name\')"><i class="fa fa-bank"></i>删除</a>'+
        '            <input type="hidden" class="input" name="sub_id_'+num+'" value=""'+
        '            />'+
        '        </div>'+
        '    </div>'+
        '</li>';
    $(html).appendTo($('#pannel-external'));
}
/**
 * 删除整个道具组
 * @param obj
 * @param ajaxUrl
 * @param type
 * @param arrName
 * @returns {boolean}
 */
function delAnotherProps(obj, ajaxUrl, type, arrName) {
    var size = $('#pannel-external>li').size();
    var thisObj = $(obj);
    var dataId = thisObj.data('num');
    var id = thisObj.data('id');
    switch (true) {
        case (dataId == 1):
            layer.msg('删除失败，税换条件1、税换奖励1不能删', {icon: 5});
            break;
        case (dataId < size && dataId != 1):
            layer.msg('删除失败，请按从下到上顺序删除', {icon: 5});
            break;
        default:
            //获取ajax状态

            var ajaxStatus = delImg(ajaxUrl, arrName + '_' + dataId + '[]', type, arrName, dataId, id, '');
            if (ajaxStatus.status == 0) {
                layer.msg(ajaxStatus.message, {icon: 5});
                return false;
            }

            $('#pannel-external>li:nth-child(' + dataId + ')').remove();
            var sizeN = $('#pannel-external>li').size();
            $("input[name='count']").val(sizeN);
    }
}

