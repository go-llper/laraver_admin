/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2017-04-18 10:38:05
 * @version $Id$
 */

// 手机验证

(function($){
    $.extend({
		empty_check:function(cap_obj){
			var code = $.trim(cap_obj.val());
			if(code.length == 0) {
				$.alert({
					title: '提示',
					text: cap_obj.attr('placeholder'),
					onOK: function () {
						cap_obj.focus();
					}
				});
				return false;
			} else {
				return code;
			}
		},


        captcha_check: function (cap_obj){
            var code = $.trim(cap_obj.val());
            var pattern =/^[0-9]*$/;
            if(code.length == 0) {
            	$.alert("验证码不能为空", "提示");
                return false;
            }else if(!code.match(pattern)) {
               $.alert("请输入正确的验证码", "提示");
               return false;
            }else {
            	$.pwd_check($("#pwd"),$("#pwd1"));
                return true;
            }
             
        },
           
        //手机验证
        phone_check: function (phone_obj){
            var phone = $.trim(phone_obj.val());
            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
            if(phone.length == 0) {
                $.alert("手机号码不能为空", "提示");
                return false;
            }else if(!phone.match(pattern)) {
               $.alert("请输入正确的手机号码", "提示");
                return false;
            } else {
			    return true;
            }

        },
        pwd_check:function(pwd_obj,pwd1_obj){
        	var pwd=$.trim(pwd_obj.val());
        	if(pwd1_obj){
        		var pwd1=$.trim(pwd1_obj.val());
        	}
        	var reg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        	if(pwd.length == 0) {
                $.alert("密码不能为空", "提示");
                return false;
            }else if(!pwd.match(reg)){
			   $.alert("请输入6-16位字母数字组合");
			    return false;
			} else{
				if(pwd1_obj){
					if(pwd1.length==0){
						 $.alert("再次输入密码不能为空", "提示");
	                	 return false;
					}else if(pwd!=pwd1){
						$.alert("登录密码与再次输入确认密码不一致！", "提示");
						return false;
					}else{
						return true;
					}

				}else{

					return true;
				}
				

			}

        }
        
        /**
         * 唯一性检测
         * */
        // phone_unique_check: function (phone_obj) {
        //     if(!$.phone_check(phone_obj)) {
        //         return false;
        //     }
        //     var phone       = $.trim(phone_obj.val());
        //     var phoneflag   = true;
        //     $.ajax({
        //         url:'/user/register/checkUnique',
        //         type:'POST',
        //         data:{phone:phone,type:'phone'},
        //         dataType:'json',
        //         async: false,  //同步发送请求
        //         success:function(result){
        //             if(result.status) {
        //                 $.showTips($('#'+tips), '', 'success');
        //                 phoneflag = true;
        //            } else {
        //                 $.showTips($('#'+tips), '手机号已注册', 'error');
        //                 phoneflag = false;
        //            }
        //         },
        //         error:function(msg){
        //             console.log(msg);
        //             phoneflag = false;
        //         }
        //     });
        //     return phoneflag;
        // },
        
     });  

	 //$("#showTooltips").click(function(){
	 //	 $.phone_check($("#phone"));
     //
	 //});
      
    
})(jQuery);