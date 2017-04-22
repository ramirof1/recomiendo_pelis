<?php

global $sgrb;
$sgrb->includeModel('Review');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeView('Review');

class SGRB_TemplateDesignView extends SGRB_Review
{
	public function __construct()
	{
		$proOptions = '';
		if (!SGRB_PRO_VERSION) {
			$proOptions = '<i class="sgrb-templates-pro-options"> PRO </i>';
		}
		parent::__construct('sgrb');
		$this->setRowsPerPage(10);
		$this->setTablename('template_design');
		$this->setColumns(array(
			'id',
			'name'
		));
		$this->setDisplayColumns(array(
			'id' => 'ID',
			'name' => 'Title',
			'options' => 'Options '.$proOptions,
			'preview' => 'Preview'

		));
		$this->setSortableColumns(array(
			'id' => array('id', false),
			'name' => array('name', true)
		));
		if (!SGRB_PRO_VERSION) {
			$this->setInitialSort(array(
				'sgrb_pro_version' => 'DESC'
			));
		}
		else {
			$this->setInitialSort(array(
				'id' => 'DESC'
			));
		}
	}

	public function customizeRow(&$row)
	{
		global $sgrb;
		$id = $row[0];
		$notActive = '';
		$proSticker = '';
		$template = SGRB_TemplateDesignModel::finder()->findByPk($id);
		if (!SGRB_PRO_VERSION) {
			$notActive = 'sgrb-not-active';
			$proSticker = '<i class="sgrb-templates-pro-options"> PRO </i>';
			if (!$template->getSgrb_pro_version()) {
				$row[1] .= ' '.$proSticker;
			}
		}
		$editUrl = $sgrb->adminUrl('TemplateDesign/save','id='.$id);
		$row[2] = '<a class="'.$notActive.'" href="'.$editUrl.'">'.__('Edit', 'sgrb').'</a>&nbsp;&nbsp;<a class="'.$notActive.'" href="#" onclick="SGTemplate.ajaxDeleteTemplate('.$id.')">'.__('Delete', 'sgrb').'</a>';
		if ($row[1] == 'post_review' || $row[1] == 'woo_review') {
			$row[2] = 'not editable';
		}
		$row[3] = '<i class="sgrb-preview-eye"><img width="30px" height="30px" src="'.$sgrb->app_url.'assets/page/img/preview.png'.'">';
		if ($template->getImg_url()) {
			$tempImage = $template->getImg_url();
		}
		else {
			$tempImage = $sgrb->app_url.'assets/page/img/custom_template.jpeg';
		}
		$row[3] .= '<div class="sgrb-template-icon-preview" style="background-image:url('.$tempImage.')"></div>';
		$row[3] .= '</i>';
	}

	public function customizeQuery(&$query)
	{
		//$query .= ' LEFT JOIN wp_sgrb_comment ON wp_sgrb_comment.review_id='.$this->tablename.'.id';
	}
}
