<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_relation2}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $level
 * @property string $parent_id
 * @property string $parent_id2
 */
class UserRelation2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_relation2}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'level', 'parent_id', 'parent_id2'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'level' => 'Level',
            'parent_id' => '上级',
            'parent_id2' => '上上级',
        ];
    }


    //出局
    public static function is_leader($user_id){

        $user=User::findOne($user_id);
        if($user->is_leader==0){
            $children=User::find()->where(['parent_id'=>$user_id])->andWhere(['>=','level_id',2])->count()*1;
            if($children>=2){
                //直推2个合伙人，出局
                $user->is_leader=1;
                $user->save();
                $children_model=UserRelation2::find()->where(['parent_id'=>$user_id])->all();
                foreach ($children_model as $k=>$v){
                    if($v['parent_id2']>0){
                        $v->parent_id=$v->parent_id2;
                        $v->level=2;
                        $v->parent_id2=0;
                        $v->save();
                    }else{
                        $v->parent_id=0;
                        $v->level=1;
                        $v->parent_id2=0;
                        $v->save();
                    }
                }
                $relation=UserRelation2::find()->where(['user_id'=>$user_id])->limit(1)->one();
                $relation->parent_id=0;
                $relation->level=1;
                $relation->parent_id2=0;
                $relation->save();
            }
        }
    }
}
