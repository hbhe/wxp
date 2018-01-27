<?php
return [
    'redpack.enable' => false,  // 控制红包账户的记账
    'redpack.test' => true, // true表示并不真正发送红包, 只是把发红包行为改成记录日志
    'redpack.area.check' => true,
    'redpack.area.check.frontend' => false,
    'redpack.area.check.backend' => true,
    'redpack.company.check' => false,
    'redpack.company.check.value' => '中国电信',
    
    'redpack.title' => '邀您抢红包',
    'redpack.button.signup.title' => '我要抢红包',
    'redpack.button.share.title' => '分享赚红包',
    'redpack.button.show.title' => '查看我的红包',
    'redpack.toplist.count' => 15,
    'redpack.promotionlist.count' => 10,
        
    'redpack.img.header' => '@web/images/redpack/hongbao-head.jpg',
    'redpack.img.defaulticon' => '@web/images/redpack/unicom.jpg',
    'redpack.img.sharetips' => '@web/images/redpack/hongbao-sharetips.jpg',
    
    'redpack.amount.top' => 5000,    
    'redpack.amount.subscribe.cost' => 150,
    'redpack.amount.subscribe.random.range' => 10, 
    'redpack.amount.subscribe.scope' => '{"5000":20,"2000":10,"1000":10}',
    'redpack.amount.share.percent' => 100,
    'redpack.amount.share.cost' => 30,    
    'redpack.amount.share.random.range' => 10,

    'card.img.header' => '@web/images/redpack/card-head.jpg',
    'card.img.footer' => '@web/images/redpack/card-footer.jpg',
    'card.img.sharetips' => '@web/images/redpack/share-v4.jpg',

    'question.img.header' => '@web/images/redpack/question-head.jpg',

    'redpack.amount.recommend.cost' => 50, // 推荐每粉丝送x分(以分为单位)
    'redpack.amount.recommend.consume.start' => 100, // x分起才能提现
    'redpack.amount.recommend.consume.step' => 200, // 每次提现必须是x分的整数倍
    'redpack.wishing.recommend' => '感谢参与推荐有礼活动!', // 红包发放时的祝福语
    'redpack.aftersend.prompt.custom.text' => '', // 红包发放后通过客户接口推送的信息, 为空表示不推送, 支持{gh_title}, {nickname}, {amount}模板变量
    'redpack.recommend.fan.province' => false,      
    'redpack.recommend.fan.clickcount' => 1, //发展的粉丝至少必须点击n下，才能计酬    

];



