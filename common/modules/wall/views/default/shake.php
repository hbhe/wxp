<?php
use yii\web\View;
use yii\helpers\Url;
//use yii;

use common\modules\wall\assets\ShakeAsset;

$bundle = ShakeAsset::register($this);

//$this->registerJsFile('https://res.wx.qq.com/open/js/jweixin-1.1.0.js', ['position' => View::POS_BEGIN]);
$this->beginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
  <?php $this->head() ?>
 <!--  <link rel="stylesheet" href="../css/style-flex.css">
  <link rel="stylesheet" href="../css/style-index.css"> -->
  <link href='//cdn.webfont.youziku.com/webfonts/nomal/25528/46969/5865f6c4f629da1b8443ca1a.css' rel='stylesheet' type='text/css' />
  <script src="http://cdn.bootcss.com/vue/2.1.6/vue.min.js"></script>
  <script src="http://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
</head>
<body >
<img src="<?php echo Url::to($bundle->baseUrl.'/img/cjbg.jpg',true)?>" style="width: 100%;height: 100%;position: absolute; z-index: -1" alt="">
<?php $this->beginBody() ?>
<div class="box" id="process" flex="dir:top  cross:center" >
  <div style="height: 212px">&nbsp;</div>
  <div class="box__process" v-show="!isOver">
    <div class="box__process_items">
      <div class="box__process_item" flex="cross:center" v-for="(item,index) of winList.data">
        <div class="item-label" v-text="item.nickname"></div>
        <div class="item-media">
          <img :src="item.headimgurl" width="100%" height="100%" alt="">
        </div>
        <div class="item-process" :style="{'width': item.number*6+'px'}" @click="add(index)">
        </div>
      </div>
      <div class="box__process_clock">
        {{clock_text}}
      </div>
    </div>
  </div>
  <div class="box__prize " :class="{
  four:winList.awardsnumber==20,
  three:winList.awardsnumber==5,
  two:winList.awardsnumber==3,
  one:winList.awardsnumber==1}" v-show="isOver" >
    <header flex="main:center cross:center">
      <div class="title_t" v-text="winList.activityname">

      </div>
    </header>
    <div style="" flex="main:center cross:center" class="list">
      <ul flex="dir:top cross:center" style="overflow: visible">
        <li flex="cross:center" v-for="item of overList">
          <img :src="item.headimgurl"  alt="">
          <span v-text="item.nickname" style="text-overflow: ellipsis;overflow: hidden"></span></li>
      </ul>
    </div>
  </div>
</div>
<script>
  var vm = new Vue({
    data: {
      isOver: false,
      var_clock: undefined,
      clock_text: "",
      overList: [],
      winList: {
      },
    },
    mounted: function () {
      console.log(this);
      this.getData()
//      this.add(1);
    },
    destoryed: function () {
//      clearInterval(this.var_clock)
    }
    ,
    methods: {
      add: function (index) {
        this.winList.data[index].number += 1;
      }
      ,
      getData: function () {
          var serf = this;
         setInterval(function () {
        	  var args = {
           	        'classname': '\\common\\modules\\wall\\models\\WxWallShake',
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
              success: function (data) {
                //是否开始
                //不能开始
                console.log(data);
               if (data.code == 0) {
             	  serf.winList = data;
               } else {
                 console.log('error')
               }
                if (data.activitstart === 'enabled') {
                	serf.isOver=false;
                    serf.overList=[];
                  //活动开始
                  if (data.code == 0) {
//                     if (serf.winList.code == 0) {
//                       serf.winList.data = data.data;
//                     } else {
//                       serf.winList = data;
//                     }
                	  serf.winList = data;
                  } else {
                    console.log('error')
                  }
                }
                else{
                  serf.isOver = true;
                  if(serf.overList.length==0){
                    serf.overList=serf.winList.data.splice(0,serf.winList.awardsnumber)
                  }
//                  for (var i = 0; i < serf.winList.awardsnumber; i++) {
//                    if (serf.winList.data[i]) {
//                      serf.overList.push(serf.winList.data[i])
//                    }
//                  }

                }
//                if (!serf.var_clock) {
//                    serf.clock()
//                  }
              }
            })
          }, 1000)
        },
        
      formatClock: function (m, s) {
        var result = ''
        if (m >= 10) {
          result = m;
        }
        else {
          result = '0' + m;
        }
        if (s >= 10) {
          result += ':' + s;
        }
        else {
          result += ':0' + s
        }
        return result
      }
      ,
      clock: function () {
        var serf = this;
        this.var_clock = setInterval(function () {
          if (serf.winList.activitduration > 0) {
            serf.winList.activitduration--;
       
            if (serf.winList.activitduration < 60) {
              serf.clock_text = serf.formatClock(0, serf.winList.activitduration);
            }
            else {
              if (serf.winList.activitduration / 60 > 10) {
                serf.clock_text = serf.formatClock(Math.floor(serf.winList.activitduration / 60), serf.winList.activitduration - serf.winList.activitduration * 60)
              }
              else {
                serf.clock_text = serf.formatClock(Math.floor(serf.winList.activitduration / 60), (serf.winList.activitduration - Math.floor(serf.winList.activitduration / 60) * 60).toString())
              }
            }
          }
          else {
            console.log('over');
            serf.isOver = true;
            for (var i = 0; i < serf.winList.awardsnumber; i++) {
              if (serf.winList.data[i]) {
                serf.overList.push(serf.winList.data[i])
              }
            }
            clearInterval(serf.var_clock)
          }
        }, 1000)
      }
    }
  }).$mount('#process')
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
