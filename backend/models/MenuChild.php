<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%menu_child}}".
 *
 * @property integer $menu_id
 * @property string $name
 */
class MenuChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_child}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'name'], 'required'],
            [['menu_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => '菜单ID',
            'name'    => '角色名称',
        ];
    }

    /**
     * @param $name    -角色名称
     * @param $menus   -所有的菜单
     * @return bool
     * 设置菜单权限
     */
    public function setMenus($name,$menus)
    {
        //删除原先用户的所有用户菜单
        MenuChild::deleteAll(['name'=>$name]);
        foreach ($menus as $value)
        {
            $MenuChild = new MenuChild;
            $MenuChild->menu_id  = $value;
            $MenuChild->name     = $name;
            $MenuChild->save();
        }

        return true;
    }


    /**
     * @param $name
     * @param $new_name
     * @return bool
     * 根据角色名来修改角色名
     */
    public function upMenuChild($name,$new_name)
    {
        $this->updateAll(['name'=>$new_name],['name'=>$name]);

        return true;
    }

    /**
     * @param $name
     * 删除该角色的菜单
     */
    public function delMenuChild($name)
    {
        $this->deleteAll(['name'=>$name]);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 关联获取菜单信息
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['menu_id' => 'menu_id']);
    }

}

