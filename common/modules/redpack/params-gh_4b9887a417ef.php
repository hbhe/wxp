<?php
return [
    'redpack.enable' => true,
    'redpack.test' => true,
    'redpack.area.check' => true,
    'redpack.area.check.frontend' => false,
    'redpack.area.check.backend' => true,
    'redpack.company.check' => false,
    'redpack.company.check.value' => '荆门电信',
    
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
    'redpack.amount.recommend.consume.start' => 500, // x分起才能提现
    'redpack.amount.recommend.consume.step' => 500, // 每次提现必须是x分的整数倍
    'redpack.wishing.recommend' => '感谢参与推荐有礼活动!', // 红包发放时的祝福语
    'redpack.aftersend.prompt.custom.text' => '{nickname}, 感谢参与推荐有礼活动, 您的红包已派发，请于24小时内领取。', // 红包发放后通过客户接口推送的信息, ''表示无, 支持{gh_title}, {nickname}, {amount}模板变量
    'redpack.recommend.fan.province' => '湖北', // 检查粉丝所在省份, 为'',false表示不检查
    'redpack.recommend.fan.clickcount' => 1, //发展的粉丝至少必须点击n下，才能计酬  // 0 (无限制),1, 2, ...,   
];



