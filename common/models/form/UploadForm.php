<?php
/**
 * Desc: 文件上传
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-23
 * Time: 下午8:00
 */
namespace common\models\form;

class UploadForm extends \yii\base\Model
{
	/**
	 * @var \yii\web\UploadedFile
	 */
	public $imageFile;

	public $imageData;

	const SCENARIO_IMAGE_FILE = 'image_file'; // 上传图片文件场景
	const SCENARIO_IMAGE_DATA = 'image_data'; // 上传图片内容场景

	public function formName()
	{
		return '';
	}

	public function rules()
	{
		return [
			[
				'imageFile',
				'file',
				// mime不需要和后缀一致
				'checkExtensionByMimeType' => false,
				// 不能为空
				'skipOnEmpty' => false,
				'uploadRequired' => '请上传正确的文件',
				// 文件格式
				'extensions' => ['jpg', 'png', 'jpeg', 'gif'],
				'wrongExtension' => '请上传JPG、PNG文件、GIF格式图片',
				// 上限8M
				'maxSize' => 20 * 1024 * 1024,
				'tooBig' => '文件大小上限20M',
				'on' => self::SCENARIO_IMAGE_FILE,
			],
			[
				'imageData',
				'required',
				'message' => '请上传正确的文件',
				'on' => self::SCENARIO_IMAGE_DATA,
			],
		];
	}

	public function uploadImageFile()
	{
		if ($this->validate()) {
			$filename = md5(time() . rand(1, 9999) . rand(1, 999));
			$baseDir = 'uploads/images/' . date('Y-m') . '/';
			if (!is_dir($baseDir)) {
				mkdir($baseDir, 0777, true);
			}
			$filepath = $baseDir . $filename . '.' . $this->imageFile->extension;
			if ($this->imageFile->saveAs($filepath)) {
				return $filepath;
			}
			$this->addError('imageFile', '上传失败');
		}
		return false;
	}

	/**
	 * 通过文件内容上传
	 */
	public function uploadImageData()
	{
		if ($this->validate()) {
			$imageData = str_replace(' ', '+', $this->imageData);
			$fileContent = base64_decode($imageData);
			$filename = md5(time() . rand(1, 9999) . rand(1, 999));
			$fileExt = 'jpg';
			$baseDir = 'uploads/images/' . date('Y-m') . '/';
			if (!is_dir($baseDir)) {
				mkdir($baseDir, 0777, true);
			}
			$filepath = $baseDir . $filename . '.' . $fileExt;

			$ret = file_put_contents($filepath, $fileContent);
			if ($ret) {
				return $filepath;
			}
			$this->addError('imageData', '上传失败');
		}
		return false;
	}
}