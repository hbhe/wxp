<?php
use yii\web\View;
use yii\helpers\Url;

use common\modules\wall\assets\WallAsset;
common\assets\EmojiAsset::register($this);
require_once Yii::getAlias('@common/3rdlibs/php-emoji/emoji.php');

$bundle = WallAsset::register($this);

//$this->registerJsFile('https://res.wx.qq.com/open/js/jweixin-1.1.0.js', ['position' => View::POS_BEGIN]);
$this->beginPage()
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">



  <title>Title</title>
  <style type="text/css">
	html,body {
		height: 768px;
		width: 1024px;
	}
	body{background: url(./images/bg.jpeg) no-repeat  ;background-size:100%; }
	input,button{outline:none;}
	.wenwen-footer{width:100%;position:fixed;bottom:-5px;left:0;background:#fff;padding:3%;border-top:solid 1px #ddd;box-sizing:border-box;}
	.wenwen_btn,.wenwen_help{width:15%;text-align:center;}
	.wenwen_btn img,.wenwen_help img{height:40px;}
	.wenwen_text{height:40px;border-radius:5px;border:solid 1px #636162;box-sizing:border-box;width:66%;text-align:center;overflow:hidden;margin-left:2%;}
	.circle-button{padding:0 5px;}
	.wenwen_text .circle-button{font-size:14px;color:#666;line-height:38px;}
	.write_box{background:#fff;width:100%;height:40px;line-height:40px;}
	.write_box input{height:40px;padding:0 5px;line-height:40px;width:100%;box-sizing:border-box;border:0;}
	.wenwen_help button{width:95%;background:#15b110;color:#fff;border-radius:5px;border:0;height:40px;}
	#wenwen{height:100%;}
	.speak_window{overflow-y:hidden;height: 345px;
		width: 585px;;position:fixed;top:246px;left:105px;}
	.speak_box{margin-bottom:70px;padding:10px;}
	.question,.answer{margin-bottom: 25px;}
	.question{text-align:right;}
	.question>div{display:inline-block;}
	.left{float:left;}
	.right{float:right;}
	.clear{clear:both;}
	.heard_img{height:60px;width:60px;border-radius:5px;overflow:hidden;background:#ddd;}
	.heard_img img{width:100%;height:100%}
	.question_text,.answer_text{box-sizing:border-box;position:relative;}
	.question_text{padding-right:20px;}
	.answer_text{padding-left:20px;}
	.question_text p,.answer_text p{border-radius:10px;padding:.5rem;margin:0;font-size:14px;line-height:28px;box-sizing:border-box;vertical-align:middle;word-wrap:break-word;}
	.answer_text p{background:#fff;}
	.question_text p{background:#9eea6a;color:#000;text-align:left;}
	.question_text i,.answer_text i{width:0;height:0;border-top:5px solid transparent;border-bottom:5px solid transparent;position:absolute;top:23px;}
	.answer_text i{border-right:10px solid #000;left:10px;}
	.question_text i{border-left:10px solid #9eea6a;right:10px;}
	.answer_text p a{color:#42929d;display:inline-block;}
	audio{display:none;}
	.saying{position:fixed;bottom:30%;left:50%;width:120px;margin-left:-60px;display:none;}
	.saying img{width:100%;}
	.write_list{position:absolute;left:0;width:100%;background:#fff;border-top:solid 1px #ddd;padding:5px;line-height:30px;}
</style>
    <?php $this->head() ?>

</head>
<body style="background: url(<?php echo Url::to($bundle->baseUrl.'/img/xyyd.jpg',true)?>) no-repeat;">

<?php $this->beginBody() ?>
<div class="speak_window">
	<div class="speak_box">
		<div class="answer">
		</div>
	</div>
</div>

<script src="http://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>

<script type="text/javascript">
function getNewMessage() {
    var args = {
        'classname': '\\common\\modules\\wall\\models\\WxWall',
        'funcname': 'getNewMessageAjax',
        'params': {
            'gh_id' : '<?php echo $gh->gh_id; ?>'
        }
    };
    $.ajax({
        url: "<?= Url::to(['ajax-broker']); ?>",
        type: "GET",
        cache: false,
        dataType: "json",
        data: "args=" + JSON.stringify(args),
        success: function (resp) {
            if (resp['code'] != 0) {
                alert("handle error");
                return;
            }

            for(var i in resp['data']){
                var row = resp['data'][i];
                //alert(row['openid']);
               $('.answer').append('<div class="question" style="opacity: 0"><div class="heard_img right"><img src="'+row['headimgurl']+'"></div><div class="question_text clear" style="max-width: 295px;"><p>'+ row['content']+'</p><i></i></div></div>')
        		$('.answer .question:eq(-1)').animate({
              opacity: 1
        		}, 300)
            for_bottom()     
                
            }
        },
    });
  
}
	setInterval(getNewMessage, 3000)
  function for_bottom(){
		var speak_height = $('.speak_box').height();
		var window_height = $('.speak_window').height();
		$('.speak_box,.speak_window').animate({scrollTop:speak_height},500 , function () {
		  let h = 0
			let docs = document.querySelectorAll('.question')
			for (var i = docs.length;i > 0; i--){
		    let e =docs[i]
        h += $(e).height()
        if(h > window_height) {
          $(e).remove()
        }
			}
    });
	}
	
	function autoWidth(){
		$('.question_text').css('max-width',$('.question').width()-60);
	}
	autoWidth();
</script>



<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>