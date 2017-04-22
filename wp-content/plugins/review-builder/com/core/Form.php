<?php
global $sgrb;

$sgrb->includeController('Controller');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('TemplateDesign');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeModel('Template');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('CommentForm');


class Form
{
	protected $id;
	private $autoIncrement = 0;

	public function __construct($id = false)
	{
		$this->id = $id;
	}

	public function displayField($html, $options, $singleFieldsName, $type, $commentId)
	{
		$result = '';
		if (!$html) {
			echo $result;
			return false;
		}
		$attributesWithValues = $this->getAttribute($html);
		$input = $this->getInput($type, $attributesWithValues, $singleFieldsName, $commentId);
		$result = $this->prepareWrapperHtml($type, $input, $attributesWithValues);
		return $result;
	}

	public function getAttribute ($attributes)
	{
		$array = explode(' ', $attributes);
		$options = array();

		if (strpos($attributes, 'required') !== false) {
			$options['required'] = 1;
		}
		else {
			$options['required'] = 0;
		}
		if (strpos($attributes, 'hidden') !== false) {
			$options['show'] = 0;
		}
		else {
			$options['show'] = 1;
		}

		foreach ($array as $key) {
			$key = explode('=', $key);
			if ($key[0] == 'id') {
				$options['id'] = $key[1];
			}
			if ($key[0] == 'class') {
				$options['class'] = $key[1];
			}
			if ($key[0] == 'style') {
				$options['style'] = $key[1];
			}
			if ($key[0] == 'placeholder') {
				$options['placeholder'] = $key[1];
			}
			if ($key[0] == 'label') {
				$options['label'] = $key[1];
			}
			if ($key[0] == 'as') {
				$options['as'] = $key[1];
			}	    }
		return $options;
	}

	public function getInput($type, $attributesWithValues, $singleFieldsName, $commentId)
	{
		$result = '';
		$fieldValue = '';
		$fieldId = '';
		$fieldClass = '';
		$fieldStyle = '';
		$fieldLabel = '';
		$fieldUseAs = '';
		$fieldPlaceholder = '';

		$fieldId = @$attributesWithValues['id'];
		$fieldClass = @$attributesWithValues['class'];
		$fieldStyle = @$attributesWithValues['style'];
		$fieldLabel = @$attributesWithValues['label'];
		$fieldUseAs = @$attributesWithValues['as'];
		$fieldPlaceholder = str_replace('-', ' ', @$attributesWithValues['placeholder']);

		if (!@$fieldUseAs) {
			$fieldUseAs = $fieldLabel;
		}
		if ($commentId) {
			$fieldValue = $this->getInputValues($commentId, $singleFieldsName);
		}
		if ($type == SGRB_FORM_FIELD_TYPE_TEXT) {
			$type = 'text';
		}
		if ($type == SGRB_FORM_FIELD_TYPE_EMAIL) {
			$type = 'email';
		}
		if ($type == SGRB_FORM_FIELD_TYPE_NUMBER) {
			$type = 'number';
		}
		/* <i></i> added to fill the error string */
		$result = '<input value="'.$fieldValue.'" name="'.$singleFieldsName.'" type="'.$type.'" id="'.$fieldId.'" class="'.$fieldClass.'" placeholder="'.$fieldPlaceholder.'"><i class="sgrb-user-form-error"></i>';
		if ($type == SGRB_FORM_FIELD_TYPE_TEXTAREA) {
			$result = '<textarea name="'.$singleFieldsName.'" id="'.$fieldId.'" class="'.$fieldClass.'" placeholder="'.$fieldPlaceholder.'">'.$fieldValue.'</textarea><i class="sgrb-user-form-error"></i>';
		}
		if ($fieldLabel) {
			$result = '<span class="sgrb-each-field-notice">'.$result.'</span>';
		}
		return $result;
	}

	public function getInputValues($commentId, $inputName) {
		$fieldValue = '';
		$mainComment = SGRB_CommentModel::finder()->findByPk($commentId);
		if ($mainComment) {
			$proComment = $mainComment->getComment();
			if ($proComment) {
				$proComment = json_decode($proComment, true);
				if (!empty($proComment)) {
					if (isset($proComment[$inputName])) {
						$fieldValue = $proComment[$inputName];
					}
				}
				else {
					$fieldValue = '';
				}
			}
		}
		return $fieldValue;
	}

	public function prepareWrapperHtml($type, $input, $attributesWithValues)
	{
		$result = '';
		$required = '';
		$fieldRequired = $attributesWithValues['required'];
		$fieldLabel = str_replace('-', ' ', @$attributesWithValues['label']);
		if ($fieldLabel) {
			$fieldLabel = '<span class="sgrb-comment-title">'.$fieldLabel.'</span>';
		}
		else {
			$fieldLabel = '';
		}
		if ($fieldRequired) {
			$required .= '<i class="sgrb-comment-form-asterisk">*</i>';
		}
		$result .= '<div class="sgrb-front-comment-rows">'
						.$fieldLabel.$required.$input.
					'</div>';
		return $result;
	}

	public function render($commentId = false)
	{
		global $sgrb;
		$html = '';
		$form = SGRB_CommentFormModel::finder()->findByPk($this->id);
		if (!$form) {
			return $html;
		}
		$options = $form->getOptions();
		$options = json_decode($options,true);
		if (empty($options)) {
			return $html;
		}

		foreach ($options as $key => $val) {
			$singleField = $val['code'];
			$singleFieldsName = $options[$key]["name"];
			@$text = preg_match_all("#(\[(sgrb_text +)(.+?)])#", $singleField, $matches);
			if ($text) {
				$type = SGRB_FORM_FIELD_TYPE_TEXT;
				foreach ($matches[3] as $match) {
					$html .= $this->displayField($match, $singleField, $singleFieldsName, $type, $commentId);
				}
			}
			@$email = preg_match_all("#(\[(sgrb_email +)(.+?)])#", $singleField, $matches);
			if ($email) {
				$type = SGRB_FORM_FIELD_TYPE_EMAIL;
				foreach ($matches[3] as $match) {
					$html .= $this->displayField($match, $singleField, $singleFieldsName, $type, $commentId);
				}
			}
			@$number = preg_match_all("#(\[(sgrb_number +)(.+?)])#", $singleField, $matches);
			if ($number) {
				$type = SGRB_FORM_FIELD_TYPE_NUMBER;
				foreach ($matches[3] as $match) {
					$html .= $this->displayField($match, $singleField, $singleFieldsName, $type, $commentId);
				}
			}
			@$textarea = preg_match_all("#(\[(sgrb_textarea +)(.+?)])#", $singleField, $matches);
			if ($textarea) {
				$type = SGRB_FORM_FIELD_TYPE_TEXTAREA;
				foreach ($matches[3] as $match) {
					$html .= $this->displayField($match, $singleField, $singleFieldsName, $type, $commentId);
				}
			}
		}

		return $html;
	}

	public function stripslashesDeep($value)
	{
		if (is_array($value)) {
			$value = array_map(array($this, 'stripslashesDeep'), $value);
		}
		else {
			$value = stripslashes($value);
		}

		return $value;
	}

	public function sanitizeHtmlText($value)
	{
		if (is_array($value)) {
			$value = array_map(array($this, 'sanitizeHtmlText'), $value);
		}
		else {
			$value = sanitize_text_field($value);
		}

		return $value;
	}

	public function save($data)
	{
		global $wpdb;
		$options = array();
		$options['images'] = $data['images'];
		$options['html'] = $data['html'];
		$options['url'] = $data['url'];
		$options['html'] = $this->stripslashesDeep($options['html']);
		$options['html'] = $this->sanitizeHtmlText($options['html']);

		$options = json_encode($options);
		$tempName = $data['name'];
		$template = null;
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		}
		if (!$template) {
			$template = new SGRB_TemplateModel();
		}
		$template->setName(sanitize_text_field($tempName));
		$template->setOptions($options);
		$res = $template->save();

		if ($template->getId()) {
			$lastTemId = $template->getId();
		}
		else {
			if (!$res) return false;
			$lastTemId = $wpdb->insert_id;
		}
		return $lastTemId;
	}

}

?>
