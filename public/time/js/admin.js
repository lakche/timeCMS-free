$(function () {
    var t =$("input[name='_token']").val();

    //栏目封面上传
    if( $("#categorie_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'categorie_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/categories/save-cover',
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

    //删除栏目
    $(".categorie_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/categories/delete/" + $(this).attr("data-id"),
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

    //单页封面上传
    if( $("#page_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'page_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/pages/save-cover',
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

    //删除单页
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

    //设置管理员
    $(".user_IsAdmin").on("click", function(){
        if(confirm("是否设置为管理员")){
            $.ajax({
                type: 'POST',
                url: "/admin/users/admin/" + $(this).attr("data-id"),
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

    //取消管理员
    $(".user_NoAdmin").on("click", function(){
        if(confirm("是否取消管理员")){
            $.ajax({
                type: 'POST',
                url: "/admin/users/admin/" + $(this).attr("data-id"),
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

    //人物头像上传
    if( $("#person_head").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'person_head',
            container: document.getElementById('CPIC'),
            url: '/admin/persons/save-head',
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

    //删除人物
    $(".person_Del").on("click", function(){
        if(confirm("是否删除")){
            $.ajax({
                type: 'POST',
                url: "/admin/persons/delete/" + $(this).attr("data-id"),
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

    //项目选择参与人员
    $("#choose_person li").on("click", function(){
        var name = $(this).text();
        var id = $(this).attr('data-id')
        var inputId = $("input[name='person_id']");
        var inputName = $("input[name='person_name']");
        if(inputId.val()==''){
            inputId.val(id);
        } else {
            inputId.val(inputId.val()+','+id);
        }
        if(inputName.val()==''){
            inputName.val(name);
        } else {
            inputName.val(inputName.val()+','+name);
        }
        return false;
    });
    $("#person_clear").on("click",function(){
        if(confirm("是否清空")) {
            var inputId = $("input[name='person_id']");
            var inputName = $("input[name='person_name']");
            inputId.val('');
            inputName.val('');
        }
    })

    //项目封面上传
    if( $("#project_cover").length > 0 ) {
        var uploaderPic = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'project_cover',
            container: document.getElementById('CPIC'),
            url: '/admin/projects/save-cover',
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

    //增加项目进度
    $('.add-speed').on('click',function(){
        $('.speed-add:last').before(
            '<div class="input-group address-add">'+
            '<div class="input-group-addon">时间</div>'+
            '<input type="text" class="form-control" name="time[]" value="">'+
            '<div class="input-group-addon">事件</div>'+
            '<input type="text" class="form-control map" name="event[]" value="">'+
            '<div class="input-group-addon btn btn-danger del-speed"><i class="glyphicon glyphicon-minus"></i>删除进度</div>'+
            '</div>');
    });

    //删除项目进度
    $(document).on('click','.del-speed',function(){
        $(this).parent().remove();
    });


})