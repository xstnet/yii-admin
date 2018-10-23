<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_buttons}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $menu_id
 * @property integer $status
 * @property string $position
 * @property string $url
 * @property string $attr_id
 * @property string $attr_class
 * @property integer $type
 * @property integer $is_refresh
 * @property string $confirm_tip
 */
class AdminButtons extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_buttons}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'status', 'type', 'is_refresh'], 'integer'],
            [['position'], 'string'],
            [['name', 'attr_id', 'attr_class'], 'string', 'max' => 30],
            [['url', 'confirm_tip'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'menu_id' => 'Menu ID',
            'status' => 'Status',
            'position' => 'Position',
            'url' => 'Url',
            'attr_id' => 'Attr ID',
            'attr_class' => 'Attr Class',
            'type' => 'Type',
            'is_refresh' => 'Is Refresh',
            'confirm_tip' => 'Confirm Tip',
        ];
    }
}
