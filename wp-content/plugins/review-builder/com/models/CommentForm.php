<?php

global $sgrb;
$sgrb->includeModel('Model');

class SGRB_CommentFormModel extends SGRB_Model
{
	const TABLE = 'comment_form';
	protected $id;
	protected $title;
	protected $template_id;
	protected $options;

	public static function finder($class = __CLASS__)
	{
		return parent::finder($class);
	}

	public static function create($blogId = '')
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE, $blogId);

		$dropQquery = "DROP TABLE IF EXISTS $tablename";
		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) NOT NULL,
					  `options` text NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$query2 = "INSERT INTO $tablename (`id`, `title`, `options`) VALUES (1, 'Sample', '[{\"code\":\"[sgrb_text id= class=sgrb-add-fname style= placeholder=name label=Your-name as=username required ]\",\"name\":\"sgrb_addName\",\"show\":1,\"type\":1,\"label\":\"Your-name\",\"ordering\":0},{\"code\":\"[sgrb_email id= class=sgrb-add-email style= placeholder=email label=Email as=]\",\"name\":\"sgrb_email_e11c\",\"show\":1,\"type\":2,\"label\":\"Email\",\"ordering\":1},{\"code\":\"[sgrb_text id= class=sgrb-add-title style= placeholder=title label=Title as=title]\",\"name\":\"sgrb_addTitle\",\"show\":1,\"type\":1,\"label\":\"Title\",\"ordering\":2},{\"code\":\"[sgrb_textarea id= class=sgrb-add-comment style= placeholder=your-comment-here label=Comment as=comment required ]\",\"name\":\"sgrb_addComment\",\"show\":1,\"type\":5,\"label\":\"Comment\",\"ordering\":3}]')";
		$wpdb->query($dropQquery);
		$wpdb->query($query);
		$wpdb->query($query2);
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
