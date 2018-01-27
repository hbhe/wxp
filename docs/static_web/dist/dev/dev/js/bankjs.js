/**
 * Created by pengwei on 2016/12/13.
 */
//  var canvas=document.querySelector('#bgCanvas')
//  var showCanvas=document.querySelector('#showCanvas')
//  var width = canvas.width
//  var height = canvas.height
//  var c=canvas.getContext('2d')
//  var ctx=showCanvas.getContext('2d')
//  var bgImg=new Image();
//  bgImg.src="../img/logo.png";
//  //圆点直径
//  var dis = 30;
//  //上次行宽位置
//  var lw = width;
//  var dots = [];
//  bgImg.onload=function(){
//    c.drawImage(bgImg,0,0,width,height);
//    var imgData=c.getImageData(0,0,width,height);
//    var data=imgData.data;
//
//
//
//    for(var i=0;i<data.length;i+=4*dis){
//      var l ;
//      if(i/4 >= lw){
//        lw += dis*width;
//        l = i/4;
//        l+=(dis-1)*width;
//        i = 4 *l;
//      }
//      if(data[i+3] > 0){
//        dots.push({x:(i/4)%width,y:((i/4)/width),id:i});
//      }
//    }
//    console.log(dots.length);
//
//    var colors=['#fc9cc6','#27b598','#fc7e84','#1841b2','#537504','#518bf7']
//    //画圆
//    for(var j = 0;j< dots.length;j++){
//      ctx.fillStyle = colors[Math.floor(Math.random()*10)];
//      ctx.beginPath();
//      ctx.arc(dots[j].x, dots[j].y, dis/2, 0, 2 * Math.PI, true);
//      ctx.closePath();
//      ctx.fill();
//    }
//  }
  //调用次数

var addImgCount=0;
function addImg(){
  var j=addImgCount;
  var bgImg=new Image();
  var imgs=["../img/1.jpg","../img/2.jpg"]
  bgImg.src="../img/1.jpg";
  
  bgImg.onload=function(){
    console.log(1);
    ctx.beginPath();
    ctx.save();
    ctx.arc(dots[j].x, dots[j].y, dis/2, 0, 2 * Math.PI);
    ctx.clip();
    //,dots[j].x-dis/2,dots[j].y-dis/2,dis,dis
    ctx.drawImage(bgImg,
      dots[j].x-dis/2,
      dots[j].y-dis/2,
      dis+dis/2,
      dis+dis/2)
    ctx.restore()
    ctx.closePath()
  }
  
  addImgCount+=1;
}