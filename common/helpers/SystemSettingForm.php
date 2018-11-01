<?php
/**
 * Desc: 系统设置，生成form item 助手
 * Created by PhpStorm.
 * User: shantong
 * Date: 2018/6/27
 * Time: 11:00
 */

namespace common\helpers;

class SystemSettingForm
{
	public static function text($item)
	{
		$tips = '';
		if (!empty($item['description'])) {
			$tips = "<br/> <div class='layui-form-mid layui-word-aux'>{$item['description']}</div>";
		}
		$html = "
			<label class='layui-form-label'>{$item['name']}</label>
			<div class='layui-input-inline setting-item'>
				<input type='text' name=\"setting[{$item['id']}][value]\" value=\"{$item['value']}\" placeholder=\"{$item['name']}\" autocomplete='off' class='layui-input'>
				$tips
			</div>
		";
		$html .= self::getCallCode($item);

		return $html;
	}

	public static function fileImage($item)
	{
		$tips = '';
		if (!empty($item['description'])) {
			$tips = "<br/> <div class='layui-form-mid layui-word-aux'>{$item['description']}</div>";
		}
		$html = "
			<label class='layui-form-label'>{$item['name']}</label>
			<div class='layui-input-inline setting-item'>
				<div class='layui-upload'>
					<div class='layui-upload-list'>
						<img class='layui-upload-img upload-img' title=\"{$item['name']}\" alt=\"{$item['name']}\" src=\"/{$item['value']}\">
						<p class='upload-message'></p>
						<input type='hidden' name=\"setting[{$item['id']}][value]\"  value=\"{$item['value']}\">
					</div>
					<button type='button' class='layui-btn layui-btn-warm layui-btn-sm select-image-file'>选择图片</button>
					$tips
				</div>
			</div>
		";
		$html .= self::getCallCode($item);

		return $html;
	}

	public static function textarea($item)
	{
		$tips = '';
		if (!empty($item['description'])) {
			$tips = "<br/> <div class='layui-form-mid layui-word-aux'>{$item['description']}</div>";
		}
		$html = "
			<label class='layui-form-label'>{$item['name']}</label>
			<div class='layui-input-inline setting-item'>
				<textarea class='layui-textarea' name=\"setting[{$item['id']}][value]\" placeholder=\"{$item['name']}\">{$item['value']}</textarea>
				$tips
			</div>
		";
		$html .= self::getCallCode($item);

		return $html;
	}

	public static function checkbox($item)
	{
		$tips = '';
		if (!empty($item['description'])) {
			$tips = "<br/> <div class='layui-form-mid layui-word-aux'>{$item['description']}</div>";
		}
		$html = "
			<label class='layui-form-label'>{$item['name']}</label>
			<div class='layui-input-inline setting-item'>";
		$checked = explode(',', $item['value']);
		$attributes = json_decode($item['attribute']);
		foreach ($attributes as $attribute) {
			// <input type="checkbox" name="like[write]" title="写作">
			$html .= "<input lay-skin='primary' type='checkbox' name=\"setting[{$item['id']}][value][]\" ". (in_array($attribute->value, $checked) ? 'checked' : '') ." value='{$attribute->value}' title='{$attribute->name}'>";
		}
		$html .= $tips . "</div>";
		$html .= self::getCallCode($item);

		return $html;
	}

	public static function radio($item)
	{
		$tips = '';
		if (!empty($item['description'])) {
			$tips = "<br/> <div class='layui-form-mid layui-word-aux'>{$item['description']}</div>";
		}
		$html = "
			<label class='layui-form-label'>{$item['name']}</label>
			<div class='layui-input-inline setting-item'>";
		$attributes = json_decode($item['attribute']);
		foreach ($attributes as $attribute) {
			$html .= "<input type='radio' name=\"setting[{$item['id']}][value]\" ". ($item['value'] == $attribute->value ? 'checked' : '') ." value='{$attribute->value}' title='{$attribute->name}'>";
		}
		$html .= $tips . "</div>";
		$html .= self::getCallCode($item);

		return $html;
	}

	public static function getCallCode($item)
	{
		return "
			<div class='layui-form-mid'>-</div>
			<div class='layui-input-inline setting-code'>
				<input type='text' name=\"setting[{$item['id']}][code]\" value=\"{$item['code']}\" lay-verify=\"required\" placeholder='调用代码' autocomplete='off' class='layui-input'>
			</div>
		";
	}
}