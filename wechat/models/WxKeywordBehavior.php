<?php

namespace wechat\models;

use common\models\WxKeyword;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use yii\base\Behavior;


class WxKeywordBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_MSG_TEXT => 'wxkeywordWechat',
            Wechat::EVENT_MSG_EVENT_SUBSCRIBE => 'wxkeywordWechat',
            Wechat::EVENT_MSG_EVENT_CLICK => 'wxkeywordWechat',
        ];
    }

    public function wxkeywordWechat($event)
    {
        $owner = $this->owner;
        $gh = $owner->gh;
        $wxUser = $owner->wxUser;
        $message = $owner->message;
        $msgType = $message->get('MsgType');
        $content = $message->get('Content');
        $openid = $message->get('FromUserName');
        $eventkey = $message->get('EventKey');
        $gh_id = $message->get('ToUserName');
        $event = $message->get('Event');
        //Yii::info($message->all());

        $dict = [
            '{nickname}' => empty($wxUser->nickname) ? '' : $wxUser->nickname,
            '{openid}' => $openid,
            '{gh_id}' => $gh->title,
            '{gh_id,openid}' => "gh_id=" . $gh_id . "&openid=" . $openid,
        ];
        //关注
        if ($msgType == 'event' && $event == 'subscribe') {
            //关注，被动回复
            $model = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_SUBSCRIBE_', 'replyway' => '_PASSIVE_'])->orderBy('priority desc')->one();
            if ($model) {
                if ($model->type == 'text') {
                    $model->action = strtr($model->action, $dict);
                    $owner->response = new Text(['content' => $model->action]);
                } elseif ($model->type == 'news') {
                    $model->title = strtr($model->title, $dict);
                    $model->description = strtr($model->description, $dict);
                    $model->url = strtr($model->url, $dict);
                    $owner->response = new News(['title' => $model->title, 'description' => $model->description, 'url' => $model->url, 'image' => $model->picurl]);
                }
            }
            //关注，主动推送
            $models = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_SUBSCRIBE_', 'replyway' => '_ACTIVELY_'])->orderBy('priority desc')->all();
            if ($models) {
                foreach ($models as $model) {
                    if ($model->type == 'text') {
                        $model->action = strtr($model->action, $dict);
                        $news = new Text(['content' => $model->action]);
                        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                        $wxapp->staff->message($news)->to($openid)->send();
                    } elseif ($model->type == 'news') {
                        $model->title = strtr($model->title, $dict);
                        $model->description = strtr($model->description, $dict);
                        $model->url = strtr($model->url, $dict);
                        $news = new News(['title' => $model->title, 'description' => $model->description, 'url' => $model->url, 'image' => $model->picurl]);
                        $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                        $wxapp->staff->message([$news])->to($openid)->send();
                    }
                }
            }
            //关注，消息转发
            $model = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_SUBSCRIBE_', 'replyway' => '_FORWARD_'])->orderBy('priority desc')->one();
            if ($model) {
                //处理消息转发
            }
        }

        //关键词回复
        if ($msgType == 'text' || $msgType == 'event') {
            if ($msgType == 'event') {
                $eventkey = $message->get('EventKey');
                $content = $eventkey;
            }

            //关键词，被动回复
            $modelPassive = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_KEYWORD_', 'replyway' => '_PASSIVE_'])->andFilterWhere(['like', 'keyword', $content])->orderBy('priority desc')->one();//
            if ($modelPassive) {
                //精准匹配
                if ($modelPassive->match == '_ACCURATE_') {
                    if ($content == $modelPassive->keyword) {
                        if ($modelPassive->type == 'text') {
                            $modelPassive->action = strtr($modelPassive->action, $dict);
                            $owner->response = new Text(['content' => $modelPassive->action]);
                        } elseif ($modelPassive->type == 'news') {
                            $modelPassive->title = strtr($modelPassive->title, $dict);
                            $modelPassive->description = strtr($modelPassive->description, $dict);
                            $modelPassive->url = strtr($modelPassive->url, $dict);
                            $owner->response = new News(['title' => $modelPassive->title, 'description' => $modelPassive->description, 'url' => $modelPassive->url, 'image' => $modelPassive->picurl]);
                        } elseif ($modelPassive->type == \common\models\WxKeyword::WX_ACTION_TYPE_TRANSFER) {
                            //https://mpkf.weixin.qq.com/cgi-bin/kfloginpage
                            $transfer = new \EasyWeChat\Message\Transfer();
                            if ($modelPassive->hasKfAccount) {
                                if ($modelPassive->skipIfOffline) {
                                    $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                                    $staff = $wxapp->staff;
                                    $rows = $staff->onlines();
                                    $kfAccounts = $rows['kf_online_list'];
                                    foreach ($kfAccounts as $kf) {
                                        if ($kf['kf_account'] == $modelPassive->KfAccount) {
                                            $transfer->account($modelPassive->KfAccount);
                                            break;
                                        }
                                    }
                                } else {
                                    $transfer->account($modelPassive->KfAccount);
                                }
                            }
                            //Yii::error('ready to goto KF system');
                            $owner->response = $transfer;
                        }
                    }
                    //模糊匹配
                } elseif ($modelPassive->match == '_THEFUZZY_') {
                    if (stripos($modelPassive->keyword, $content) !== false) {
                        if ($modelPassive->type == 'text') {
                            $modelPassive->action = strtr($modelPassive->action, $dict);
                            $owner->response = new Text(['content' => $modelPassive->action]);
                        } elseif ($modelPassive->type == 'news') {
                            $modelPassive->title = strtr($modelPassive->title, $dict);
                            $modelPassive->description = strtr($modelPassive->description, $dict);
                            $modelPassive->url = strtr($modelPassive->url, $dict);
                            $owner->response = new News(['title' => $modelPassive->title, 'description' => $modelPassive->description, 'url' => $modelPassive->url, 'image' => $modelPassive->picurl]);
                        }
                    }
                }
            }
            //关键词，主动推送
            $modelActivelys = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_KEYWORD_', 'replyway' => '_ACTIVELY_'])->orderBy('priority desc')->all();
            if ($modelActivelys) {
                foreach ($modelActivelys as $modelActively) {
                    //精准匹配
                    if ($modelActively->match == '_ACCURATE_') {
                        if ($content == $modelActively->keyword) {
                            if ($modelActively->type == 'text') {
                                $modelActively->action = strtr($modelActively->action, $dict);
                                $news = new Text(['content' => $modelActively->action]);
                                $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                                $wxapp->staff->message($news)->to($openid)->send();
                            } elseif ($modelActively->type == 'news') {
                                $modelActively->title = strtr($modelActively->title, $dict);
                                $modelActively->description = strtr($modelActively->description, $dict);
                                $modelActively->url = strtr($modelActively->url, $dict);
                                $news = new News(['title' => $modelActively->title, 'description' => $modelActively->description, 'url' => $modelActively->url, 'image' => $modelActively->picurl]);
                                $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                                $wxapp->staff->message([$news])->to($openid)->send();
                            }
                        }
                        //模糊匹配
                    } elseif ($modelActively->match == '_THEFUZZY_') {
                        if (stripos($modelActively->keyword, $content) !== false) {
                            if ($modelActively->type == 'text') {
                                $modelActively->action = strtr($modelActively->action, $dict);
                                $news = new Text(['content' => $modelActively->action]);
                                $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                                $wxapp->staff->message($news)->to($openid)->send();
                            } elseif ($modelActively->type == 'news') {
                                $modelActively->title = strtr($modelActively->title, $dict);
                                $modelActively->description = strtr($modelActively->description, $dict);
                                $modelActively->url = strtr($modelActively->url, $dict);
                                $news = new News(['title' => $modelActively->title, 'description' => $modelActively->description, 'url' => $modelActively->url, 'image' => $modelActively->picurl]);
                                $wxapp = $gh->getWxApp('snsapi_base', false)->getApplication();
                                $wxapp->staff->message([$news])->to($openid)->send();
                            }
                        }
                    }
                }
            }

            //关键词，消息转发
            $modelForward = WxKeyword::find()->where(['gh_id' => $gh_id, 'inputEventType' => '_KEYWORD_', 'replyway' => '_FORWARD_'])->orderBy('priority desc')->one();
            if ($modelForward) {
                //精准匹配
                if ($modelForward->match == '_ACCURATE_') {
                    if ($content == $modelForward->keyword) {
                        $items = $message->all();
                        $request = $owner->wxapp->server->getRequest();
                        $rawBody = $request->getContent();
                        $response = \common\wosotech\Util::forwardWechatXML($modelForward->forward_url, $modelForward->token, $rawBody);

                    }
                    //模糊匹配
                } elseif ($modelForward->match == '_THEFUZZY_') {
                    if (stripos($modelForward->keyword, $content) !== false) {
                        //处理转发消息
                    }
                }
            }
        }
    }
}
