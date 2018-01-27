"use strict";

/**
 * Created by pengwei on 2016/12/23.
 */
$(document).ready(function () {
  $(".VerticalMenu>div>div:first-child").click(function () {
    var $xz = $(".VerticalMenu>div>div:first-child");
    $($xz).not(this).children("i.fa-angle-right").css({ "transform": "rotate(0deg)", "color": "#787878" }).attr("leng", "");
    //, "color": "#000000"
    if ($(this).children("i.fa-angle-right").attr("leng") != "s") {
      $(this).children("i.fa-angle-right").attr("leng", "s");
      $(this).children("i.fa-angle-right").css({ "transform": "rotate(90deg)", "color": "#787878" });
      //, "color": "#f9579e"
    } else {
      $(this).children("i.fa-angle-right").attr("leng", "");
      $(this).children("i.fa-angle-right").css({ "transform": "rotate(0deg)", "color": "#787878" });
    }
    $($xz).not($(this)).siblings("[name='xz']").stop().slideUp(400);
    $(this).siblings("[name='xz']").slideToggle(400);
  });
  $(".VerticalMenu>div>div>i[leng='s']").css({ "transform": "rotate(90deg)" }).parent().next().show();
  var $VerticalMenu_scdj = null;
  $(".VerticalMenu>div>div:last-child>div").click(function () {
    $($VerticalMenu_scdj).css("background-color", "white");
    $(this).css("background-color", "#fbceac");
    $VerticalMenu_scdj = $(this);
  });
});