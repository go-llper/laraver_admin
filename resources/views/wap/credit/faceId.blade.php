<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<span system="{{ $system }}" id="touch"></span>
<script src="{{assetByCdn('/wap/js/jquery-2.1.4.js')}}"></script>
<script>
    $(function(){
        var system = $('span').attr('system');

        $("#touch").on('click',function(){
            if(system == 'android'){
                window.android.faceId();
            }else{
                faceId();
            }
        });

        setTimeout(function(){
            $("#touch").trigger('click');
        },100);

    });
</script>
</body>
</html>