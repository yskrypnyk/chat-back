<?php
namespace app\sockets;
use app\models\ChatMembers;
use app\models\ChatMessages;
use app\models\Users;
use blackbes\yiisockets\BaseController;

class ChatController extends BaseController
{

    public function actionSubscribeChat() {
        $user = Users::findOne($this->client_id);
        $chatId = $this->getData('chat_id');
        $this->addToGroup($this->conn,'chat-'.$chatId);
        $this->sendToGroup('status', ['user_id' => $user->id, "status"=>"Successfully joined chat"], 'chat-' . $chatId);
    }


    public function actionUnsubscribeChat() {
        $user = Users::findOne($this->client_id);
        $chatId = $this->getData('chat_id');
        $this->removeFromGroup($this->conn,'chat-'.$chatId);
        $this->sendToGroup('status', ['user_id' => $user->id, "status"=>"Successfully left chat"], 'chat-' . $chatId);
    }

    public function actionSend() {
        $user = Users::findOne($this->client_id);
        $text = $this->getData('text');
        $chatId = $this->getData('chat_id');

        $chatMember = ChatMembers::findOne(['user_id'=>$user->id, 'chat_id'=>$chatId]);
        $model = new ChatMessages();
        $model->message = $text;
        $model->member_id = $chatMember->id;
        if ($model->save()){
            $this->sendToGroup('new-message', ['user_id' => $user->id, 'text' => $text, 'chat_id'=>$chatId], 'chat-' . $chatId);
        } else {
            $this->sendToGroup('error', ['error' => "Problem saving message"], 'chat-' . $chatId);

        }
    }

    public function actionTest() {
        $text = $this->getData('text');
        $this->send($this->conn, 'get-new-text', ['text' => $text.' Agree']);
    }


}