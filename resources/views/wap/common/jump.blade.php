@include('wap.common.header')
<body data-jump-url="{{$jump_url or '/'}}" data-title="{{$jump_title or '提示'}}" data-text="{{$jump_text or '操作成功'}}"></body>
@include('wap.common.creditBottom')
<script>
  $(function(){
    var jumpUrl    = $('body').attr('data-jump-url');
    var jumpTitle  = $('body').attr('data-title');
    var jumpText   = $('body').attr('data-text');
    if(jumpUrl.length>0){
      $.alert({
        title: jumpTitle,
        text:  jumpText,
        onOK: function () {
          location.href = jumpUrl;
        }
      });
    }
  });
</script>
</body>
</html>
