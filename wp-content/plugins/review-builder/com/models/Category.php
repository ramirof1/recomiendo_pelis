<?php

global $sgrb;
$sgrb->includeModel('Model');
$sgrb->includeModel('Review');

class SGRB_CategoryModel extends SGRB_Model
{
	const TABLE = 'category';
	protected $id;
	protected $review_id;
	protected $name;


	public static function finder($class = __CLASS__)
	{
		return parent::finder($class);
	}

	public static function create($blogId = '')
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE, $blogId);
		$reviewTable = $sgrb->tablename(SGRB_ReviewModel::TABLE, $blogId);

		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`review_id` int(10) unsigned NOT NULL,
					`name` varchar(255) NOT NULL,
					 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$query2 = "ALTER TABLE $tablename ADD INDEX (`review_id`);";
		$query3 = "ALTER TABLE $tablename ADD FOREIGN KEY (`review_id`)
				REFERENCES $reviewTable (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
		$wpdb->query($query);
		$wpdb->query($query2);
		$wpdb->query($query3);
	}

	public static function drop($blogId = '')
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE, $blogId);
		$query = "DROP TABLE $tablename";
		$wpdb->query($query);
	}

	public static function toArray($sgrbField)
	{
		$dataArray = array();
		$dataArray['id'] = $sgrbField->getId();
		$dataArray['review_id'] = $sgrbField->getReview_id();
		$dataArray['name'] = $sgrbField->getName();
		return $dataArray;
	}
}
