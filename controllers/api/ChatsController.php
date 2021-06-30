<?php
namespace app\controllers\api;

use app\models\ChatMembers;
use app\models\Chats;
use Yii;
use yii\db\Exception;
use yii\rbac\Role;

class ChatsController extends SecurityController
{

    //TODO - test everything

    /**
     * This method gets chats which have specified user as a member
     * @param user_id - requested user id
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data consists of array with ids of available chats
     */
    function actionGetAvailableChats(){
        $userId = Yii::$app->request->post('user_id');
        if ($userId){
            $availableChats = ChatMembers::find()
                ->select('chat_id')
                ->where(['user_id'=>$userId])
                ->asArray()
                ->all();

            $data = [];
            foreach ($availableChats as $chat){
                array_push($data, $chat['chat_id']);
            }
            if (!empty($data)){
                return json_encode(['status'=>true, 'data'=>$data]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"This user has no available chats"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);

        }
    }

    /**
     * This method creates a new chat
     * @param user_id - creator user id
     * @param name - new chat name
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    function actionCreateChat(){
        $userId = Yii::$app->request->post('user_id');
        $name = Yii::$app->request->post('name');

        if ($userId && $name){
            $model = new Chats();
            $model->name = $name;
            $model->created_by = $userId;

            if ($model->save()){
                return json_encode(['status'=>true, 'data'=>$model->id]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"Error saving chat in database"]);
            }

        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }

    }

    /**
     * This method creates a new chat with members
     * @param user_id - creator user id
     * @param members_list - invited users' ids
     * @param name - new chat name
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data - new chat_id
     */
    function actionCreateChatWithUsers(){
        $userId = Yii::$app->request->post('user_id');
        $membersList = Yii::$app->request->post('members_list');
        $name = Yii::$app->request->post('name');

        if ($userId && $membersList && $name){
            $model = new Chats();
            $model->name = $name;
            $model->created_by = $userId;

            if ($model->save()){
                $rows = [];

                foreach ($membersList as $key => $member){
                    $rows[] = ['chat_id' => $model->id, 'user_id' =>$member];
                }
                try {
                    Yii::$app->db->createCommand()->batchInsert('chatMembers', ['chat_id', 'user_id'], $rows)->execute();
                    return json_encode(['status'=>true, 'data'=>$model->id]);
                } catch (Exception $ex) {
                    return json_encode(['status'=>false, 'warning'=>$ex]);
                }
            } else {
                return json_encode(['status'=>false, 'warning'=>"Error saving chat in database"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }
    }

    /**
     * This method adds users to chat
     * @param members_list - array of new users' ids
     * @param chat_id - chat for adding member
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    function actionAddUsersToChat(){
        $membersList = Yii::$app->request->post('members_list');
        $chatId = Yii::$app->request->post('chat_id');

        if ($membersList && $chatId){
            $rows = [];

            foreach ($membersList as $key => $member){
                $rows[] = ['chat_id' => $chatId, 'user_id' =>$member];
            }
            try {
                Yii::$app->db->createCommand()->batchInsert('chatMembers', ['chat_id', 'user_id'], $rows)->execute();
                return json_encode(['status'=>true, 'data'=>$chatId]);
            } catch (Exception $ex) {
                return json_encode(['status'=>false, 'warning'=>$ex]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }
    }

    /**
     * This method removes users from chat
     * @param members_list - array of users' ids to be removed
     * @param chat_id - chat for removing member
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    function actionRemoveUsersFromChat(){
        $membersList = Yii::$app->request->post('members_list');
        $chatId = Yii::$app->request->post('chat_id');

        if ($membersList && $chatId){
            if(ChatMembers::deleteAll(['chat_id'=>$chatId, 'user_id'=>$membersList])){
                return json_encode(['status'=>true]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"There was a problem with deleting"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }
    }

    /**
     * This method updates user role in chat
     * @param user_id - requested user id
     * @param chat_id - chat for updating role
     * @param role - user role
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    function actionChangeUsersRole(){
        $userId = Yii::$app->request->post('user_id');
        $chatId = Yii::$app->request->post('chat_id');
        $role = Yii::$app->request->post('role');

        if ($userId && $chatId && $role){
            $chatMember = ChatMembers::find()
                ->where(['user_id'=>$userId])
                ->andWhere(['chat_id'=>$chatId])
                ->one();

            $chatMember->role = $role;
            if ($chatMember->save()){
                return json_encode(['status'=>true]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"Error saving chat in database"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }
    }

    /**
     * This method deletes chat
     * @param chat_id - chat id
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    function actionDeleteChat(){
        $chatId = Yii::$app->request->post('chat_id');
        if ($chatId){
            if (Chats::deleteAll(['id'=>$chatId])){
                return json_encode(['status'=>true]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"Data was not deleted"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }
    }

    /**
     * This method gets chat message history between all users
     * @param chat_id - requested chat id
     * @param message_limit - amount of messages to return
     * @param offset - messages offset
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data consists of objects, each containing properties sender_id, sender_name, message
     */
    function actionGetChatData(){
        $chatId = Yii::$app->request->post('chat_id');
        $messagesLimit = Yii::$app->request->post('messages_limit');
        $offset = Yii::$app->request->post('offset');

        if ($chatId && $messagesLimit && $offset){
            $chatData = Chats::find()
                ->select('users.id as sender_id, users.name as sender_name, chat_messages.message')
                ->where(['chats.id'=>$chatId])
                ->rightJoin('chat_members','chat_members.chat_id = chats.id')
                ->rightJoin('users','chat_members.user_id = users.id')
                ->rightJoin('chat_messages','chat_messages.member_id = chat_members.id')
                ->orderBy('chat_messages.created_at')
                ->limit($messagesLimit)
                ->offset($offset)
                ->asArray()
                ->all();
            if ($chatData){
                return json_encode(['status'=>true, 'data'=>$chatData]);
            } else {
                return json_encode(['status'=>false, 'warning'=>"Chat with this id does not exist"]);
            }
        } else {
            return json_encode(['status'=>false, 'warning'=>"Missing query parameters"]);
        }

    }
}