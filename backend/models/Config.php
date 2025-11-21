<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $type
 * @property string $title
 * @property integer $group
 * @property string $extra
 * @property string $remark
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $value
 * @property integer $sort
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'unique','message'=>'标识已经占用'],
            [['title','name','group','type'],'required'],
            [['type', 'group', 'append', 'updated', 'status', 'sort'], 'integer'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 50],
            [['extra'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['sort'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'name'      => '配置标识',
            'type'      => '配置类型',
            'title'     => '配置标题',
            'group'     => '分组',
            'extra'     => '配置项',
            'remark'    => '说明',
            'status'    => '状态',
            'value'     => '配置值',
            'sort'      => '排序',
            'append'    => '创建时间',
            'updated'   => '修改时间',
        ];
    }


    /**
     * @param $name
     * @return array|mixed
     * 根据name返回信息
     * 如果name为空返回全部信息
     */
    public function info($name=NULL)
    {
        //获取缓存信息
        $config = Config::find()->where(['name'=>$name])->one();
        if($config){
            return $config->value;
        }else{
            return '';
        }
    }




    public function info2($name)

    {


        $config = Config::find()->where(['name'=>$name])->one();
        if($config){
            return $config->value;
        }else{
            return '';
        }

    }

    /**
     * @param $type_id
     * 根据类型ID返回类型名称
     */
    static public function getType($type_id)
    {
        $configTypeList = Yii::$app->params['configTypeList'];
        foreach ($configTypeList as $vo)
        {
            if($vo['id'] == $type_id)
            {
                return $vo['title'];
            }
        }
    }

    /**
     * @param $type_id
     * 根据分组ID返回分组名称
     */
    static public function getGroup($group_id)
    {
        $configGroupList = Yii::$app->params['configGroupList'];
        foreach ($configGroupList as $vo)
        {
            if($vo['id'] == $group_id)
            {
                return $vo['title'];
            }
        }
    }

    /**
     * @param $string
     * @return array
     * 分析枚举类型配置值 格式 a:名称1,b:名称2
     */
    static public function parseConfigAttr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
        if(strpos($string,':'))
        {
            $value = [];
            foreach ($array as $val)
            {
                list($k, $v) = explode(':', $val);
                $value[$k]   = $v;
            }
        }
        else
        {
            $value  =   $array;
        }

        return $value;
    }

    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
            $this->append = time();
        }
        else
        {
            $this->updated = time();
        }
        return parent::beforeSave($insert);
    }
}
