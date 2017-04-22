<?php
global $sgrb;

$sgrb->includeController('Controller');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('TemplateDesign');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeModel('Template');
$sgrb->includeModel('Review');


class Template
{
	protected $id;
	protected $name;
	private $autoIncrement = 0;

	public function __construct($name,$id = false)
	{
		$this->id = $id;
		$this->name = $name;
	}

	public function findImage($html)
	{
		global $sgrb;
		$this->autoIncrement++;
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
			return '<div class="sgrb-image-review '.$tagClass.'" style="background-image:url('.@$options['images'][($this->autoIncrement-1)].');">
						<div class="sgrb-icon-wrapper">
							<div class="sgrb-image-review-plus"><span class="sgrb-upload-btn" name="upload-btn_'.$this->autoIncrement.'"><i><img class="sgrb-plus-icon" src="'.$sgrb->app_url.'assets/page/img/add.png"></i></span>
								<input type="hidden" class="sgrb-img-num" data-auto-id="'.$this->autoIncrement.'">
								<input type="hidden" class="sgrb-current-template" value="'.$this->id.'">
								<input type="hidden" class="sgrb-images" id="sgrb_image_url_'.$this->autoIncrement.'" name="image_url[]" value="'.@$options['images'][($this->autoIncrement-1)].'">
							</div>
							<div class="sgrb-image-review-minus">
								<span class="sgrb-remove-img-btn" name="remove-btn_'.$this->autoIncrement.'">
									<i>
										<img class="sgrb-minus-icon" src="'.$sgrb->app_url.'assets/page/img/remove_image.png">
									</i>
								</span>
							</div>
						</div>
					</div>';
		}
		return '<div class="sgrb-image-review '.$tagClass.'" style="">
				<div class="sgrb-icon-wrapper">
					<div class="sgrb-image-review-plus"><span class="sgrb-upload-btn" name="upload-btn_'.$this->autoIncrement.'"><i><img class="sgrb-plus-icon" src="'.$sgrb->app_url.'assets/page/img/add.png"></i></span>
						<input type="hidden" class="sgrb-img-num" data-auto-id="'.$this->autoIncrement.'"> 
						<input type="hidden" class="sgrb-images" id="sgrb_image_url_'.$this->autoIncrement.'" name="image_url[]" value="">
					</div>
					<div class="sgrb-image-review-minus">
						<span class="sgrb-remove-img-btn" name="remove-btn_'.$this->autoIncrement.'">
							<i>
								<img class="sgrb-minus-icon" src="'.$sgrb->app_url.'assets/page/img/remove_image.png">
							</i>
						</span>
					</div>
				</div>
				</div>';
	}

	public function findHtmlTitle($html)
	{
		$this->autoIncrement++;
		$tag = 'title';
		$placeholder = 'Title';
		$styleClass = 'sgrb-title';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}

		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlBy($html)
	{
		$this->autoIncrement++;
		$tag = 'by';
		$placeholder = 'by';
		$styleClass = 'sgrb-product-by';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlPrice($html)
	{
		$this->autoIncrement++;
		$tag = 'price';
		$placeholder = 'price';
		$styleClass = 'sgrb-price';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlShipping($html)
	{
		$this->autoIncrement++;
		$tag = 'shipping';
		$placeholder = 'shipping information';
		$styleClass = 'sgrb-shipping';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlSubtitle($html)
	{
		$this->autoIncrement++;
		$tag = 'subtitle';
		$placeholder = 'Subtitle';
		$styleClass = 'sgrb-subtitle';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlShortDesc($html)
	{
		$this->autoIncrement++;
		$tag = 'shortDesc';
		$placeholder = 'Short description';
		$styleClass = 'sgrb-shortdesc';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findHtmlLongDesc($html)
	{
		$this->autoIncrement++;
		$tag = 'longDesc';
		$placeholder = 'Long description';
		$styleClass = 'sgrb-longdesc';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);
		}
		return '<textarea class="'.$styleClass.' '.$tagClass.'" placeholder="'.$placeholder.'" name="input_html['.$tag.'][]">'.@$options['html'][$tag][($this->autoIncrement-1)].'</textarea>';
	}

	public function findUrl($html)
	{
		$placeholder = 'link title';
		$placeholderLink = 'url-link';
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if ($this->id) {
			$template = SGRB_TemplateModel::finder()->findByPk($this->id);
			if (!$template) {
				$template = new SGRB_TemplateModel();
			}
			$options = $template->getOptions();
			$options = json_decode($options,true);

			static $linkCount = 0;
			
			return '<textarea class="sgrb-link '.$tagClass.'" placeholder="'.$placeholder.'" name="input_url[]">'.@$options['url'][$linkCount++].'</textarea>
					<textarea class="sgrb-link-title" placeholder="'.$placeholderLink.'" name="input_url[]">'.@$options['url'][$linkCount++].'</textarea>';
			
		}
		return '<textarea class="sgrb-link '.$tagClass.'" placeholder="'.$placeholder.'" name="input_url[]"></textarea>
				<textarea class="sgrb-link-title" placeholder="'.$placeholderLink.'" name="input_url[]"></textarea>';
	}

	public function adminRender()
	{
		global $sgrb;
		$htmlElements = '';
		if ($this->name == 'post_review') {
			return false;
		}
		$this->autoIncrement = 0;
		$template = SGRB_TemplateDesignModel::finder()->find('name = %s', $this->name);
		if (!$template) {
			$template = new SGRB_TemplateDesignModel();
		}
		$html = $template->getThtml();
		$css = $template->getTcss();
		$style = '<style>'.$css.'</style>';

		$img = preg_match_all("#(\[sgrbimg )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$html = preg_replace_callback('#\[sgrbimg]#',array($this,'findImage'),$html);
		if ($img) {
			$html = preg_replace_callback("#(\[sgrbimg )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findImage'), $html);
		}

		$this->autoIncrement = 0;
		$title = preg_match_all("#(\[sgrbtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$title1 = preg_match_all('#\[sgrbtitle]#', $html, $matches);
		if ($title) {
			$html = preg_replace_callback("#(\[sgrbtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlTitle'), $html);
		}
		if ($title1) {
			$html = preg_replace_callback('#\[sgrbtitle]#',array($this,'findHtmlTitle'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbproductby = preg_match_all("#(\[sgrbproductby )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbproductby1 = preg_match_all("#[sgrbproductby]#", $html, $matches);
		if ($sgrbproductby) {
			$html = preg_replace_callback("#(\[sgrbproductby )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlBy'), $html);
		}
		if ($sgrbproductby1) {
			$html = preg_replace_callback('#\[sgrbproductby]#',array($this,'findHtmlBy'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbprice = preg_match_all("#(\[sgrbprice )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbprice1 = preg_match_all("#[sgrbprice]#", $html, $matches);
		if ($sgrbprice) {
			$html = preg_replace_callback("#(\[sgrbprice )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlPrice'), $html);
		}
		if ($sgrbprice1) {
			$html = preg_replace_callback('#\[sgrbprice]#',array($this,'findHtmlPrice'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbshipping = preg_match_all("#(\[sgrbshipping )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbshipping1 = preg_match_all("#\[sgrbshipping]#", $html, $matches);
		if ($sgrbshipping) {
			$html = preg_replace_callback("#(\[sgrbshipping )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlShipping'), $html);
		}
		if ($sgrbshipping1) {
			$html = preg_replace_callback('#\[sgrbshipping]#',array($this,'findHtmlShipping'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbsubtitle = preg_match_all("#(\[sgrbsubtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbsubtitle1 = preg_match_all("#\[sgrbsubtitle]#", $html, $matches);
		if ($sgrbsubtitle) {
			$html = preg_replace_callback("#(\[sgrbsubtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlSubtitle'), $html);
		}
		if ($sgrbsubtitle1) {
			$html = preg_replace_callback('#\[sgrbsubtitle]#',array($this,'findHtmlSubtitle'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbshortdescription = preg_match_all("#(\[sgrbshortdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbshortdescription1 = preg_match_all("#\[sgrbshortdescription]#", $html, $matches);
		if ($sgrbshortdescription) {
			$html = preg_replace_callback("#(\[sgrbshortdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlShortDesc'), $html);
		}
		if ($sgrbshortdescription1) {
			$html = preg_replace_callback('#\[sgrbshortdescription]#',array($this,'findHtmlShortDesc'),$html);
		}

		$this->autoIncrement = 0;
		$sgrblongdescription = preg_match_all("#(\[sgrblongdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrblongdescription1 = preg_match_all("#\[sgrblongdescription]#", $html, $matches);
		if ($sgrblongdescription) {
			$html = preg_replace_callback("#(\[sgrblongdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findHtmlLongDesc'), $html);
		}
		if ($sgrblongdescription1) {
			$html = preg_replace_callback('#\[sgrblongdescription]#',array($this,'findHtmlLongDesc'),$html);
		}

		$sgrblink = preg_match_all("#(\[sgrblink )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrblink2 = preg_match_all("#\[sgrblink]#", $html, $matches);
		$arr = array('#\[sgrblink]#');
		$arr2 = array("#(\[sgrblink )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#");
		if ($sgrblink) {
			$html = preg_replace_callback($arr2,array($this,'findUrl'), $html);
		}
		if ($sgrblink2) {
			$html = preg_replace_callback($arr,array($this,'findUrl'), $html);
		}

		return '<div class="sgrb-change-template">'.$style.' '.$html.'</div>';
	}

	public function findFrontImage($html)
	{
		global $sgrb;
		$this->autoIncrement++;
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['images'][($this->autoIncrement-1)]) {
			@$options['images'][($this->autoIncrement-1)] = $sgrb->app_url.'assets/page/img/no-image.png';
		}
		foreach ($options['images'] as $option) {
			return '<div class="sgrb-image-review '.$tagClass.'" style="background-image:url('.@$options['images'][($this->autoIncrement-1)].');">
				<input type="hidden" class="sgrb-img-num" data-auto-id="'.$this->autoIncrement.'"> 
				<input type="hidden" class="sgrb-images" id="sgrb_image_url_'.$this->autoIncrement.'" name="image_url[]" value=""></div>';
		}
	}

	public function findFrontHtmlTitle($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'title';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlBy($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'by';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlPrice($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'price';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlShipping($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'shipping';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlSubtitle($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'subtitle';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlShortDesc($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'shortDesc';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span itemprop="description" class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontHtmlLongDesc($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$tag = 'longDesc';
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);
		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}
		if (!@$options['html'][$tag][($this->autoIncrement-1)]) {
			return '';
		}
		return '<span itemprop="description" class="'.$tagClass.'">'.@$options['html'][$tag][($this->autoIncrement-1)].'</span>';
		
	}

	public function findFrontUrl($html)
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/template/templateFrontStyles');
		$this->autoIncrement++;
		$template = SGRB_TemplateModel::finder()->findByPk($this->id);
		$options = $template->getOptions();
		$options = json_decode($options,true);

		$tagClass = '';
		if (@$html[3] && @$html[2] == 'class') {
			$tagClass .= $html[3];
		}

		static $linkCount = 0;

		$linkTitle = @$options['url'][$linkCount++];
		$linkUrl = @$options['url'][$linkCount++];
		if (!$linkTitle || !$linkUrl) {
			return '';
		}
		return '<a href="'.$linkUrl.'" class="'.$tagClass.'">'.$linkTitle.'</a>';
	}

	public function render()
	{
		global $sgrb;
		$html = '';
		$css = '';
		if ($this->name == 'post_review') {
			return false;
		}
		$template = SGRB_TemplateDesignModel::finder()->find('name = %s', $this->name);
		if ($template) {
			$html = $template->getThtml();
			$css = $template->getTcss();
		}
		$style = '<style>'.$css.'</style>';

		$img = preg_match_all("#(\[sgrbimg )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$html = preg_replace_callback('#\[sgrbimg]#',array($this,'findFrontImage'),$html);
		if ($img) {
			$html = preg_replace_callback("#(\[sgrbimg )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontImage'), $html);
		}

		$this->autoIncrement = 0;
		$title = preg_match_all("#(\[sgrbtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$title1 = preg_match_all('#\[sgrbtitle]#', $html, $matches);
		if ($title) {
			$html = preg_replace_callback("#(\[sgrbtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlTitle'), $html);
		}
		if ($title1) {
			$html = preg_replace_callback('#\[sgrbtitle]#',array($this,'findFrontHtmlTitle'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbproductby = preg_match_all("#(\[sgrbproductby )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbproductby1 = preg_match_all("#[sgrbproductby]#", $html, $matches);
		if ($sgrbproductby) {
			$html = preg_replace_callback("#(\[sgrbproductby )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlBy'), $html);
		}
		if ($sgrbproductby1) {
			$html = preg_replace_callback('#\[sgrbproductby]#',array($this,'findFrontHtmlBy'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbprice = preg_match_all("#(\[sgrbprice )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbprice1 = preg_match_all("#[sgrbprice]#", $html, $matches);
		if ($sgrbprice) {
			$html = preg_replace_callback("#(\[sgrbprice )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlPrice'), $html);
		}
		if ($sgrbprice1) {
			$html = preg_replace_callback('#\[sgrbprice]#',array($this,'findFrontHtmlPrice'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbshipping = preg_match_all("#(\[sgrbshipping )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbshipping1 = preg_match_all("#\[sgrbshipping]#", $html, $matches);
		if ($sgrbshipping) {
			$html = preg_replace_callback("#(\[sgrbshipping )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlShipping'), $html);
		}
		if ($sgrbshipping1) {
			$html = preg_replace_callback('#\[sgrbshipping]#',array($this,'findFrontHtmlShipping'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbsubtitle = preg_match_all("#(\[sgrbsubtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbsubtitle1 = preg_match_all("#\[sgrbsubtitle]#", $html, $matches);
		if ($sgrbsubtitle) {
			$html = preg_replace_callback("#(\[sgrbsubtitle )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlSubtitle'), $html);
		}
		if ($sgrbsubtitle1) {
			$html = preg_replace_callback('#\[sgrbsubtitle]#',array($this,'findFrontHtmlSubtitle'),$html);
		}

		$this->autoIncrement = 0;
		$sgrbshortdescription = preg_match_all("#(\[sgrbshortdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrbshortdescription1 = preg_match_all("#\[sgrbshortdescription]#", $html, $matches);
		if ($sgrbshortdescription) {
			$html = preg_replace_callback("#(\[sgrbshortdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlShortDesc'), $html);
		}
		if ($sgrbshortdescription1) {
			$html = preg_replace_callback('#\[sgrbshortdescription]#',array($this,'findFrontHtmlShortDesc'),$html);
		}

		$this->autoIncrement = 0;
		$sgrblongdescription = preg_match_all("#(\[sgrblongdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrblongdescription1 = preg_match_all("#\[sgrblongdescription]#", $html, $matches);
		if ($sgrblongdescription) {
			$html = preg_replace_callback("#(\[sgrblongdescription )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#",array($this,'findFrontHtmlLongDesc'), $html);
		}
		if ($sgrblongdescription1) {
			$html = preg_replace_callback('#\[sgrblongdescription]#',array($this,'findFrontHtmlLongDesc'),$html);
		}

		$sgrblink = preg_match_all("#(\[sgrblink )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#", $html, $matches);
		$sgrblink2 = preg_match_all("#\[sgrblink]#", $html, $matches);
		$arr = array('#\[sgrblink]#');
		$arr2 = array("#(\[sgrblink )(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']]#");
		if ($sgrblink) {
			$html = preg_replace_callback($arr2,array($this,'findFrontUrl'), $html);
		}
		if ($sgrblink2) {
			$html = preg_replace_callback($arr,array($this,'findFrontUrl'), $html);
		}

		return $style.' '.$html;
	}

	public function stripslashes_deep($value)
	{
		if (is_array($value)) {
			$value = array_map(array($this, 'stripslashes_deep'), $value);
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
		$options['html'] = $this->stripslashes_deep($options['html']);
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

	public function getTemplateOptions($options)
	{

	}	

	


}

?>