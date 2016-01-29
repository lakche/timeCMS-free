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

})