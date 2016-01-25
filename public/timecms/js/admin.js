$(function () {
    var t =$("input[name='_token']").val();
    //栏目封面图片上传
    if( $("#type_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'type_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/projectTypes/save-cover',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.fileName);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //删除栏目
    $(".type_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/projectTypes/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //项目二维码上传
    if( $("#wechat_code_upload").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'wechat_code_upload',
            container: document.getElementById('CPIC'),
            url: '/admin/projects/save-wechat-code',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.fileName);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //增加项目地址
    $('.add-address').on('click',function(){
        $('.address-add:last').before(
            '<div class="input-group address-add">'+
            '<div class="input-group-addon">机构地址</div>'+
            '<input type="text" class="form-control" name="address[]" value="">'+
            '<div class="input-group-addon">地图X坐标</div>'+
            '<input type="text" class="form-control map" name="map_x[]" value="0">'+
            '<div class="input-group-addon">地图Y坐标</div>'+
            '<input type="text" class="form-control map" name="map_y[]" value="0">'+
            '<div class="input-group-addon btn btn-danger del-address"><i class="glyphicon glyphicon-minus"></i>删除地址</div>'+
            '</div>');
    });

    //删除项目地址
    $(document).on('click','.del-address',function(){
        $(this).parent().remove();
    });

    //删除项目
    $(".project_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/projects/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //删除账户
    $(".user_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/users/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //删除单页面
    $(".page_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/pages/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //上传图库图片
    if( $("#pic_upload").length > 0 ) {
        var project_id = $("#project_id").val();
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'pic_upload',
            url: '/admin/galleries/add/'+project_id,
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        //console.log(obj);
                        $("#galleries-box .row").prepend('' +
                            '<div class="galleries col-sm-3">' +
                            '<div class="pic">' +
                            '<span data-toggle="modal" data-target="#gallerie-pic" data-src="' + obj.pic + '"><img src="' + obj.thumb + '"></span>' +
                            '</div>' +
                            '</div>' +
                            '');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //图库图片设置属性
    $('input[type="checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
        var _value = state ? 1 : 0;
        ajaxChangeAttribute($(this).parents(".galleries").attr('data-id'),$(this).attr('name'),_value,'galleries');
    });

    //图库图片设置排序
    $(document).on("blur", ".galleries .sort", function(){
        var id = $(this).parents(".galleries").attr("data-id");
        var _value = $(this).val();
        ajaxChangeAttribute(id,'sort',_value,'galleries');
    });

    //图库图片设置封面
    $(document).on("click", ".galleries .is_cover", function(){
        var id = $(this).parents(".galleries").attr("data-id");
        ajaxChangeAttribute(id,'is_cover',1,'galleries');
        alert('封面图设置成功!');
        window.location.reload();
    });

    //图库设置属性
    function ajaxChangeAttribute(id,type,_value,action){
        var flag = '';
        var project_id = $("#project_id").val();
        $.ajax({
            type: 'POST',
            url: "/admin/galleries/set-attr/" + project_id,
            data: { _token: t, type: type, value: _value, id: id},
            success: function (data) {
                flag = data;
            },
            error: function (data) {
                flag = data;
            }
        });
        return flag;
    }

    //图库删除图片
    $(".gallerie-Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/galleries/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //人员头像上传
    if( $("#head_upload").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'head_upload',
            container: document.getElementById('CPIC'),
            url: '/admin/staffs/save-head',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.fileName);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //删除人员
    $(".staff_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/staffs/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //人员头像上传
    if( $("#subject_cover_upload").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'subject_cover_upload',
            container: document.getElementById('CPIC'),
            url: '/admin/subjects/save-cover',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.cover);
                        $('#CPCP').val(obj.thumb);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //删除条目
    $(".subject_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/subjects/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //文章栏目封面上传
    if( $("#article_categorie_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'article_categorie_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/articleCategories/save-cover',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.cover);
                        $('#CPCP').val(obj.thumb);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

    //删除文章栏目
    $(".article_categorie_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/articleCategories/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //删除文章
    $(".article_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/articles/delete/" + $(this).attr("data-id"),
                data: { _token: t },
                success: function (data) {
                    alert(data.message);
                    if(data.error==0){
                        location.reload();
                    }
                },
                error: function (data) {
                    alert(data.message);
                }
            });
        }
    });

    //文章封面上传
    if( $("#article_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'article_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/articles/save-cover',
            flash_swf_url: '../js/Moxie.swf',
            silverlight_xap_url: '../js/Moxie.xap',
            multipart_params: { _token: t },
            multi_selection: true,
            chunk_size: "1024kb",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "图片文件", extensions: "jpg,png,gif"}
                ]
            },
            resize: {
                quality: 70,
                preserve_headers: true
            },
            init: {
                FilesAdded: function (up, files) {
                    uploaderPic.start();
                },
                Error: function (up, err) {
                    alert(err.message);
                },
                FileUploaded: function (uploaderPic, file, info) {
                    var obj = JSON.parse(info.response);
                    if (obj.result) {
                        $('#CPIC').val(obj.cover);
                        $('#CPCP').val(obj.thumb);
                        alert('图片上传成功.');
                    } else {
                        alert('图片上传失败.');
                    }
                }
            }
        });
        uploaderPic.init();
    }

})