<?php

global $sgrb;
$sgrb->includeModel('Model');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');

class SGRB_Rate_LogModel extends SGRB_Model
{
	const TABLE = 'rate_log';
	protected $id;
	protected $review_id;
	protected $comment_id;
	protected $post_id;
	protected $ip;

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
		$commentTable = $sgrb->tablename(SGRB_CommentModel::TABLE, $blogId);

		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `review_id` int(10) unsigned NOT NULL,
					  `comment_id` INT(10) unsigned NULL,
					  `post_id` INT(10) NULL,
					  `ip` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$query2 = "ALTER TABLE $tablename ADD INDEX(`review_id`);";
		$query3 = "ALTER TABLE $tablename ADD INDEX(`comment_id`);";
		$query4 = "ALTER TABLE $tablename ADD FOREIGN KEY (`review_id`)
					REFERENCES $reviewTable (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
		$query5 = "ALTER TABLE $tablename ADD FOREIGN KEY (`comment_id`)
					REFERENCES $commentTable (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
		$wpdb->query($query);
		$wpdb->query($query2);
		$wpdb->query($query3);
		$wpdb->query($query4);
		$wpdb->query($query5);
	}

	public static function alterTable()
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE);

		$query = "ALTER TABLE $tablename ADD `comment_id` INT(10) unsigned NULL DEFAULT NULL AFTER `review_id`;";
		$wpdb->query($query);
	}

	public static function drop($blogId = '')
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE, $blogId);
		$query = "DROP TABLE $tablename";
		$wpdb->query($query);
	}
}
