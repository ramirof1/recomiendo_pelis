<?php
global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeCore('Template');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeModel('Review');

class SgrbWidget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct("SgrbWidget", "Review Builder",
			array("description" => "Show Your reviews on page sidebar or content bottom."));
	}

	public function form($instance)
	{
		$html = '';
		$selected = '';
		$title = '';
		$id = '';
		if (!empty($instance)) {
			$id = $instance["id"];

		}
		$reviewId = $this->get_field_id("id");
		$reviewTitle = $this->get_field_name("id");
		$html .= '<div style="padding: 10px 0;"><p>Select review to show:</p><select id="'.$reviewId.'" name="'.$reviewTitle.'">';
		$allReviews = SGRB_ReviewModel::finder()->findAll('template_id');

		foreach ($allReviews as $singleReview) {
			$reviewOptions = $singleReview->getOptions();
			$options = json_decode($reviewOptions, true);
			if (!@$options['post-category']) {
				$selected = '';
				$elementId = @$singleReview->getId();
				$title = $singleReview->getTitle();
				if ($id == @$singleReview->getId()) {
					$selected = ' selected';
				}
				$html .= '<option value="'.$elementId.'"'.$selected.'>'.$title.'</option>';
			}
		}
					
		$html .= '</select></div>';
		echo $html;
	}

	public function update($new_instance, $old_Instance)
	{
		$instance = array();
		$instance['id'] = (!empty($new_instance['id'])) ? strip_tags($new_instance['id']) : '';

		return $instance;
	}

	public function widget($args, $instance)
	{
		global $sgrb;
		$review = SGRB_ReviewModel::finder()->findByPk($instance['id']);
		$rev = new SGRB_ReviewController();
		$html = $rev->createWidgetReviewHtml($review);
		echo $html;
	}
}


?>