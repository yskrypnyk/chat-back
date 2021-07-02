<?php
namespace app\sockets;
use app\models\Users;
use blackbes\yiisockets\BaseController;

class ChatController extends BaseController
{
    public function actionStartChat() {
        $user = Users::findOne($this->client_id);
        $chatId = $this->getData('chat_id');
        $this->sendToGroup('chats/start', ['user_id' => $user->id], 'chat-' . $chatId);
    }

    public function actionStopChat() {
        $user = Users::findOne($this->client_id);
        $chatId = $this->getData('chat_id');
        $this->sendToGroup('chats/stop', ['user_id' => $user->id], 'chat-' . $chatId);
    }

    public function actionTest() {
        $text = $this->getData('text');
        $this->send($this->conn, 'get-new-text', ['text' => $text.' Agree']);
    }


}