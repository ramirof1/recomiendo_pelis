<?php

global $sgrb;
$sgrb->includeLib('Review');
$sgrb->includeModel('Review');

class SGRB_ReviewReviewView extends SGRB_Review
{
	public function __construct()
	{
		parent::__construct('sgrb');

		$this->setRowsPerPage(10);
		$this->setTablename(SGRB_ReviewModel::TABLE);
		$this->setColumns(array(
			'id',
			'title'
		));
		$this->setDisplayColumns(array(
			'id' => 'ID',
			'title' => 'Title',
			'comment' => '<i class="vers comment-grey-bubble"></i>Comments',
			'shortcode' => 'Shortcode',
			'options' => 'Options'

		));
		$this->setSortableColumns(array(
			'id' => array('id', false),
			'title' => array('title', true)
		));
		$this->setInitialSort(array(
			'id' => 'DESC'
		));
	}

	public function customizeRow(&$row)
	{
		global $sgrb;
		$id = $row[0];
		$commentUrl = $sgrb->adminUrl('Comment/index','id='.$id);
		$comments = SGRB_CommentModel::finder()->findAll('review_id = %d', $id);
		$commentsCount = '';
		foreach ($comments as $val) {
			$commentsCount = count($comments);
			if ($commentsCount) {
				$commentsCount = '::'.$commentsCount;
			}
		}
		$row[2] = '<a href="'.$commentUrl.'">'.$commentsCount.'</a>';
		$editUrl = $sgrb->adminUrl('Review/save','id='.$id);
		$row[3] = "<input type='text' onfocus='this.select();' style='font-size:12px;' readonly value='[sgrb_review id=".$id."]' class='sgrb-large-text code'>";
		$row[4] = '<a href="'.$editUrl.'">'.__('Edit', 'sgrb').'</a>&nbsp;&nbsp;
					<a href="#" onclick="SGReview.ajaxDelete('.$id.')">'.__('Delete', 'sgrb').'</a>&nbsp;&nbsp;
					<a href="#" onclick="SGReview.prototype.ajaxCloneReview('.$id.')">'.__('Clone', 'sgrb').'</a>';
	}

	public function customizeQuery(&$query)
	{
		//$query .= ' LEFT JOIN wp_sgrb_comment ON wp_sgrb_comment.review_id='.$this->tablename.'.id';
	}
}
