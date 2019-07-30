<?php
/**
 * Desc: 文件上传
 * Created by PhpStorm.
 * User: xstnet
 * Date: 18-10-23
 * Time: 下午8:00
 */
namespace common\models\form;

use common\exceptions\ParameterException;
use common\models\UploadFiles;

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
	
	/**
	 * 上传图片
	 * @return bool|string
	 */
	public function uploadImageFile()
	{
		if ($this->validate()) {
			try {
				$fileMd5 = md5_file($this->imageFile->tempName);
				// 查询文件是否存在
				$result = UploadFiles::findOne(['md5' => $fileMd5]);
				if (!empty($result)) {
					return $result->path;
				}
				
				// 上传文件
				$baseDir = '/uploads/images/' . date('Y-m') . '/';
				
				$saveDir = \Yii::getAlias('@uploads') . '/images/' . date('Y-m') . '/';
				
				if (!is_dir($saveDir)) {
					mkdir($saveDir, 0777, true);
				}
				
				$filepath = $baseDir . $fileMd5 . '.' . $this->imageFile->extension;
				
				$savePath = $saveDir . $fileMd5 . '.' . $this->imageFile->extension;
				

				// 添加文件记录
				if ($this->imageFile->saveAs($savePath)) {
					$fileModel = new UploadFiles();
					$fileModel->name = $fileMd5;
					$fileModel->md5 = $fileMd5;
					$fileModel->path = $filepath;
					$fileModel->size = $this->imageFile->size;
					$mimeType = \yii\helpers\FileHelper::getMimeType($savePath);
					$fileModel->mime_type = $mimeType ? : $this->imageFile->type;
					$fileModel->extend = $this->imageFile->extension;
					$fileModel->saveModel();
					
					return $filepath;
				}
				
				throw new ParameterException(ParameterException::INVALID, '上传失败');
			} catch (\Exception $e) {
				$this->addError('imageFile', $e->getMessage());
				return false;
			}
		}
		return false;
	}

	/**
	 * 通过文件内容上传
	 */
	public function uploadImageData()
	{
		if ($this->validate()) {
			try {
				$imageData = str_replace(' ', '+', $this->imageData);
				
				$fileContent = base64_decode($imageData);
				$fileMd5 = md5($fileContent);
				
				// 查询文件是否存在
				$result = UploadFiles::findOne(['md5' => $fileMd5]);
				if (!empty($result)) {
					return $result->path;
				}
				
				$fileExt = 'jpg';
				
				$baseDir = 'uploads/images/' . date('Y-m') . '/';
				if (!is_dir($baseDir)) {
					mkdir($baseDir, 0777, true);
				}
				$filepath = $baseDir . $fileMd5 . '.' . $fileExt;
				
				$ret = file_put_contents($filepath, $fileContent);
				if ($ret) {
					$fileModel = new UploadFiles();
					$fileModel->name = $fileMd5;
					$fileModel->md5 = $fileMd5;
					$fileModel->path = $filepath;
					$fileModel->size = $this->imageFile->size;
					$mimeType = \yii\helpers\FileHelper::getMimeType($filepath);
					$fileModel->mime_type = $mimeType ? : 'image/jpeg';
					$fileModel->extend = $fileExt;
					$fileModel->saveModel();
					
					return $filepath;
				}
				throw new ParameterException(ParameterException::INVALID, '上传失败');
			} catch (\Exception $e) {
				$this->addError('imageData', $e->getMessage());
				return false;
			}
		}
	}
}