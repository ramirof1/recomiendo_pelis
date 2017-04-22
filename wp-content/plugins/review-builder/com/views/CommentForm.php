<?php

global $sgrb;
$sgrb->includeModel('Review');
$sgrb->includeView('Review');

class SGRB_CommentFormView extends SGRB_Review
{
	public function __construct()
	{
		parent::__construct('sgrb');
		$this->setRowsPerPage(10);
		$this->setTablename('comment_form');
		$this->setColumns(array(
			'id',
			'title'
		));
		$this->setDisplayColumns(array(
			'id' => 'ID',
			'title' => 'Title',
			'options' => 'Options',

		));
		$this->setSortableColumns(array(
			'id' => array('id', false)
		));
		$this->setInitialSort(array(
			'id' => 'DESC'
		));
	}

	public function customizeRow(&$row)
	{
		global $sgrb;
		$id = $row[0];
		$editUrl = $sgrb->adminUrl('CommentForm/save','id='.$id);
		if ($row[0] == 1) {
			$row[2] = '<a href="'.$editUrl.'">'.__('Edit', 'sgrb').'</a>';
		}
		else {
			$row[2] = '<a href="'.$editUrl.'">'.__('Edit', 'sgrb').'</a>&nbsp;&nbsp;<a href="#" onclick="SGForm.prototype.ajaxDelete('.$id.')">'.__('Delete', 'sgrb').'</a>';
		}
	}

	public function customizeQuery(&$query)
	{
		//$query .= ' LEFT JOIN wp_sgrb_comment ON wp_sgrb_comment.review_id='.$this->tablename.'.id';
	}
}
