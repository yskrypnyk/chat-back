<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat_messages".
 *
 * @property int $id
 * @property int $member_id
 * @property string $message
 * @property int $is_delete
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ChatMembers $member
 */
class ChatMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'message'], 'required'],
            [['member_id', 'is_delete'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChatMembers::className(), 'targetAttribute' => ['member_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'message' => 'Message',
            'is_delete' => 'Is Delete',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(ChatMembers::className(), ['id' => 'member_id']);
    }
}
