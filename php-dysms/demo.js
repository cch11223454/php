// JavaScript Document<script type="text/javascript">
    //发送验证码
        function test(){
            var i=60;
            var timer=setInterval(function(){
                i--;
                $("#miao .times").text(i);
                if(i==0){
                    $("#gain").css("display","block");
                    $("#miao").css("display","none");
                    clearInterval(timer);
                    $("#miao .times").text(60);
                }
            },1000);
        };

        $('#gain').click(function(){
                    var phone = $('.phone').val();
                    //var evaluation_id = $("#evaluation").val();
                    $.ajax({
                        url: "__CONTROLLER__/send_message",
                        data: {phone:phone},
                        dataType: "TEXT",
                        type: "POST",
                        success: function(data) {
                            if (data == 2) {
                                $('.phone_hint').html('手机号已注册！');
                            }else{
                                $("#gain").css("display","none");
                                $("#miao").css("display","block");
                               test();
                            }
                            // alert(data);
                        }
                    });

                });

                //判断验证码是否正确
                $('.verify').keyup(function(){
                    var verify = $('.verify').val();
                    var phone = $('.phone').val();
                    $.ajax({
                        url: "__CONTROLLER__/checkSMSCode",
                        data: {verify:verify,phone:phone},
                        dataType: "TEXT",
                        type: "POST",
                        success: function(data) {
                                    if (data == 'ok') {
                                        $('#button').removeAttr('disabled');
                                    }
                        }
                    });

                });
