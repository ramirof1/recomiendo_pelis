<?php

global $sgrb;
$sgrb->includeModel('Model');
$sgrb->includeModel('Comment');
$sgrb->includeModel('Category');

class SGRB_Comment_RatingModel extends SGRB_Model
{
	const TABLE = 'comment_rating';
	protected $id;
	protected $comment_id;
	protected $category_id;
	protected $rate;

	public static function finder($class = __CLASS__)
	{
		return parent::finder($class);
	}

	public static function create($blogId = '')
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE, $blogId);
		$categoryTable = $sgrb->tablename(SGRB_CategoryModel::TABLE, $blogId);
		$commentTable = $sgrb->tablename(SGRB_CommentModel::TABLE, $blogId);

		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`comment_id` INT(10) unsigned NOT NULL,
					`category_id` int(10) unsigned NOT NULL,
					`rate` tinyint(4) NULL,
					 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$query2 = "ALTER TABLE $tablename ADD INDEX(`category_id`);";
		$query3 = "ALTER TABLE $tablename ADD INDEX(`comment_id`);";
		$query4 = "ALTER TABLE $tablename ADD FOREIGN KEY (`category_id`)
				REFERENCES $categoryTable (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
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
		$tablename = $sgrb->tablename(self::TABLE, $blogId);
		$query = "ALTER TABLE $tablename ADD FOREIGN KEY (`comment_id`) REFERENCES $tablename(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
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
