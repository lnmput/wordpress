/**
 * Created by Administrator on 2016/3/17 0017.
 */
jQuery(document).ready( function($) {
   // alert(1);
    $(".yz_div_btn button").click( function() {
        data="yoyo";
        $.ajax({
            type: "POST",
            data: "color=" + data + "&action=yz_register_site",
            url: ajax_object.ajax_url,
            beforeSend: function() {
                $(".yz_div_btn button").html('注册中...');
            },
            success: function( data ) {
                //alert(data);
                var messageinfo=JSON.parse(data);
                if(messageinfo['status']==200){
                    $(".yz_div_btn button").html('注册成功');
                    $(".yz_div_btn").remove();
                    alert("注册成功");
                }else{
                    alert("注册失败,失败原因:"+messageinfo['message']);
                    $(".yz_div_btn button").html('请检查后重新注册');
                }
            }
        });
    });

    //清除缓存
    $("#clean-cache").click(function(){
        data="";
        $.ajax({
            type: "POST",
            data: "color=" + data + "&action=yz_clear_cache",
            url: ajax_object.ajax_url,
            beforeSend: function() {
                $("#clean-cache").html('清除中...');
            },
            success: function( data ) {
                if(data==200){
                    alert("缓存已清除");
                }else{
                    alert("缓存清除失败");
                }
            },
            complete:function(){
                $("#clean-cache").html('清除缓存');
            }
        });




    });


















    //后台修改链接
    $("#modify_btn").click(function () {
        register_url=$("input[name=register_url]").val();
        modify_url=$("input[name=modify_url]").val();
        requestad_url=$("input[name=requestad_url]").val();
        //alert(register_url);
        $.ajax({
            type: "POST",
            //data: "color=" + data + "&action=yz_register_site",
            data: "register_url="+register_url+"&modify_url="+modify_url+"&requestad_url="+requestad_url+"&action=yz_modify_url",
            url: ajax_object.ajax_url,
            beforeSend: function() {
                $("#modify_btn").html('更改中...');
            },
            success: function( data ) {
                 if(data==200){
                     $("#modify_btn").html('提交更改');
                     alert("修改成功");
                 }
            }
        });




        return false;

    });

});