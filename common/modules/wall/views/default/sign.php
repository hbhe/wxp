<?php
use yii\web\View;
use yii\helpers\Url;
//use yii;

use common\modules\wall\assets\SignAsset;

$bundle = SignAsset::register($this);

//$this->registerJsFile('https://res.wx.qq.com/open/js/jweixin-1.1.0.js', ['position' => View::POS_BEGIN]);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Titles</title>
  <?php $this->head() ?>
  <style>
    *{margin: 0 ;padding: 0;}
    :root{
      height: 100%;
    }
    body{
      height: 100%;
    }
    .main{
      width: 100%;
      height: 100%;
      background: url(<?php echo Url::to($bundle->baseUrl."/img/sign.jpg",true)?>);
      background-size:100% 100%;
      background-repeat: no-repeat;
      text-align: center;
    }
    .main canvas{
      display: inline-table;
      margin-top: 230px;
    }
  </style>
</head>

<body>
<?php $this->beginBody() ?>

<div class="main" >

  <canvas id="showCanvas"  >

  </canvas>
</div>
<canvas id="bgCanvas" width="450" height="450" style="display: none">

</canvas>
<script src="http://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script>

  var drawLogo={
    init:function(){
      this.canvas=document.querySelector('#bgCanvas')
      this.showCanvas=document.querySelector('#showCanvas')
      this.width = this.canvas.width;
      this.height = this.canvas.height;
      this.showCanvas.width=this.width;
      this.showCanvas.height=this.height;
      this.c=this.canvas.getContext('2d')
      this.ctx=this.showCanvas.getContext('2d')
      this.imgCount=0;
      this.bgImg=new Image();
      this.bgImg.src="<?php echo Url::to($bundle->baseUrl.'/img/logo-white.png',true)?>";
      var self=this;
      this.bgImg.onload=function(){
        self.drawLogo();
      }
      //圆点直径
      this.dis = 22;
      //上次行宽位置
      this.lw = this.width;
      this.dots = [];
    },
    drawLogo:function(){
      this.c.drawImage(this.bgImg,0,0,this.width,this.height);
      this.imgData=this.c.getImageData(0,0,this.width,this.height);
      var data=this.imgData.data;
      for(var i=0;i<data.length;i+=4*this.dis){
        var l ;
        if(i/4 >= this.lw){
          this.lw += this.dis*this.width;
          l = i/4;
          l+=(this.dis-1)*this.width;
          i = 4 *l;
        }
        if(data[i+3] > 0){
          this.dots.push({x:(i/4)%this.width,y:((i/4)/this.width),id:i});
        }
      }
      this.ctx.drawImage(this.bgImg,0,0,this.width,this.height);
      var colors=['#fc9cc6','#27b598','#fc7e84','#1841b2','#537504','#518bf7']
      //画圆
//      for(var j = 0;j< this.dots.length;j++){
////        this.ctx.fillStyle = colors[Math.floor(Math.random()*10)];
//
//        this.ctx.fillStyle = "rgba(255,255,255,.6)";
//        this.ctx.beginPath();
//        this.ctx.arc(this.dots[j].x, this.dots[j].y, this.dis/2, 0, 2 * Math.PI, true);
//        this.ctx.closePath();
//        this.ctx.fill();
//      }
      console.log(this.dots.length);
    },
    random: function (min, max) {
      return Math.floor(Math.random() * (max - min + 1) + min);
    },
    addImg:function(imgPath){
      var j=this.imgCount;
      var ctx=this.ctx,dots=this.dots,dis=this.dis;
      var j=this.random(0,dots.length-1);
      console.log(this.dots.length,j,dots[j].x, dots[j].y);
      var bgImg=new Image();
      bgImg.src=imgPath||"../img/1.jpg";
      bgImg.onload=function(){
        ctx.beginPath();
        ctx.save();
        ctx.arc(dots[j].x, dots[j].y, dis/2, 0, 2 * Math.PI);
        ctx.clip();
        //,dots[j].x-dis/2,dots[j].y-dis/2,dis,dis
        ctx.drawImage(bgImg,
            dots[j].x-dis/2,
            dots[j].y-dis/2,
            dis,
            dis)
        dots.splice(j,1)
        ctx.restore()
        ctx.closePath()
      }

//      this.imgCount+=1;
    }
  }
  drawLogo.init();

  function getNewMessage() {
	    var args = {
	        'classname': '\\common\\modules\\wall\\models\\WxWallSign',
	        'funcname': 'getNewMessageAjaxs',
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
	                //alert("handle error");
	                return;
	            }

	            for(var i in resp['data']){
	                var row = resp['data'][i];
	                //alert(row['headimgurl']);return;
	                
                    drawLogo.addImg(row['headimgurl']);
	                /* barrager.emit({
	                    img: row['headimgurl'],
	                    info: row['content']
	                }); */

	            }
	        },
	        /* error: function () {
	            alert('send error'); 
	        }*/
	    });
	  
	}
  window.onload=function(){
		setInterval("getNewMessage()",3000);
	}    
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>