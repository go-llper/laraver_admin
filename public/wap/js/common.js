/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2017-04-18 10:38:05
 * @version $Id$
 */

// 手机验证

(function($){
    $.extend({
        // 验证码验证
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
            }else{
                 return true;
            }
           
        },

        //身份证验证
        identity_check: function (identity_obj){

            var identity = $.trim(identity_obj.val());
            var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; 

            if(identity.length == 0) {
                $.alert("身份证不能为空", "提示");
                return false;
            }else if(!identity.match(pattern)) {
               $.alert("请输入正确的身份证号", "提示");
               return false;
            }else {
                return true;
            } 
           
        },

        // 验证中文名称
        china_check:function (china_obj){

            var china = $.trim(china_obj.val());
            var pattern = /^[\u4E00-\u9FA5]{2,6}$/;

            if(china.length == 0) {
                $.alert("姓名不能为空", "提示");
                return false;
            }else if(!china.match(pattern)) {
               $.alert("请输入正确的姓名", "提示");
               return false;
            }else{
                return true;
            } 
           
        },

        //验证数字
        num_check:function (num_obj){

            var num = $.trim(num_obj.val());
            var pattern =/^(0|[1-9][0-9]*)$/;

            if(num.length == 0) {
                $.alert("数字不能为空", "提示");
                return false;
            }else if(!num.match(pattern)) {
               $.alert("请输入正确的数字", "提示");
               return false;
            }else{
                return true;
            } 
            
        },

         //验证数字   验证零和非零开头的数字
        num_check:function (num_obj,text,text1){

            var num = $.trim(num_obj.val());
            var pattern =/^(0|[1-9][0-9]*)$/;

            if(num.length == 0) {
                $.alert(text, "提示");
                return false;
            }else if(!num.match(pattern)) {
               $.alert(text1, "提示");
               return false;
            }else{
               return true;
            } 
        },

        //判断不value为空
        value_check:function (value_obj){
            var vue = $.trim(value_obj.val());
            if(vue.length == 0) {
                return false;
            }else{
               return true;
            } 
        },

        // 银行卡验证
        bank_check:function (bank_obj){
            var bank = $.trim(bank_obj.val());
            var pattern =/^([0-9]{16}|[0-9]{19})$/;
            if(bank.length == 0) {
                $.alert("卡号不能为空", "提示");
                return false;
            }else if(!bank.match(pattern)) {
               $.alert("请输入正确的卡号", "提示");
               return false;
            }else{
                return true;
            } 
        },

        // 100的倍数借款金额
        n100_check:function (num100,text,text1){
            var num100 = $.trim(num100.val());
            var re = /^[0-9]*[0-9]$/i;
            if(num100.length ==0) {
                $.alert(text, "提示");
                return false;
            }else if(re.test(num100) && num100%100!==0||num100==0) {
                $.alert(text1, "提示");
                return false;
            }else{
                return true;
            }
        }


     });  


    
})(jQuery);