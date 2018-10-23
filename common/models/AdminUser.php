<?php
namespace common\models;

use common\exceptions\DatabaseException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $nickname
 * @property string $mobile
 * @property string $avatar
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property integer $is_admin
 * @property string $rigister_ip
 * @property string $login_ip
 * @property string $last_login_ip
 * @property integer $login_at
 * @property integer $last_login_at
 * @property integer $login_count
 * @property integer $status
 * @property string $token
 * @property string $openid
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdminUser extends BaseModel implements IdentityInterface
{
	const STATUS_DISABLED = 1;
	const STATUS_ACTIVE = 10;


	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%admin_user}}';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['is_admin', 'login_at', 'last_login_at', 'login_count', 'status', 'created_at', 'updated_at'], 'integer'],
//			[['token'], 'required'],
			[['username'], 'string', 'max' => 30],
			[['nickname'], 'string', 'max' => 30],
			[['mobile', 'login_ip'], 'string', 'max' => 15],
			[['avatar'], 'string', 'max' => 200],
			[['password'], 'string', 'max' => 64],
			[['password_reset_token', 'token', 'openid'], 'string', 'max' => 128],
			[['email'], 'string', 'max' => 150],
			[['rigister_ip', 'last_login_ip'], 'string', 'max' => 16],
			[['username'], 'unique', 'message' => '用户名已存在'],
//			[['email'], 'unique'],
			['status', 'default', 'value' => self::STATUS_ACTIVE],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLED]],
		];
	}

	public function scenarios()
	{
		return array_merge(
			parent::scenarios(),
			[
				self::SCENARIO_INSERT => [
					'login_at', 'last_login_at', 'rigister_ip', 'last_login_ip', 'login_count',
					'email', 'nickname', 'username', 'password',
					'mobile', 'avatar', 'status',
				],
				self::SCENARIO_UPDATE => [
					'email', 'nickname', 'password',
					'mobile', 'avatar', 'status',
				],
				'change_status' => [
					'status'
				]
			]
		);
	}

	public static function getListField()
	{
		return [
			self::tableName().'.id',
			'username', 'nickname', 'email', 'login_ip', 'login_at', 'login_count',
			self::tableName().'.status',
		];
	}

	public static function getPureListField()
	{
		return [
			'id',
			'username', 'nickname', 'email', 'login_ip', 'login_at', 'login_count',
			'status',
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		$user = static::findOne(['id' => $token]);
		if (empty($user)) {
			throw new DatabaseException(DatabaseException::UNKNOWN, '账号不存在');
		}
		if ($user->status == static::STATUS_DISABLED) {
			throw new DatabaseException(DatabaseException::UNKNOWN, '账号已被禁用');
		}

		return $user;
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status' => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return bool
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		return $timestamp + $expire >= time();
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		//        return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		//        return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 * @param $password
	 * @throws \yii\base\Exception
	 */
	public function setPassword($password)
	{
		$this->password = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
//		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}
}
