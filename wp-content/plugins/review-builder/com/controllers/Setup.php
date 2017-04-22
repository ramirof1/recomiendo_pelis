<?php

global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('Category');
$sgrb->includeModel('Template');
$sgrb->includeModel('Comment_Rating');
$sgrb->includeModel('Rate_Log');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeModel('Page_Review');
$sgrb->includeModel('CommentForm');

class SGRB_SetupController extends SGRB_Controller
{
	public static function activate()
	{
		add_option(SG_REVIEW_BANNER, SG_REVIEW_BANNER);
		SGRB_ReviewModel::create();
		SGRB_CommentModel::create();
		SGRB_TemplateModel::create();
		SGRB_CategoryModel::create();
		SGRB_Comment_RatingModel::create();
		SGRB_Rate_LogModel::create();
		SGRB_Page_ReviewModel::create();
		SGRB_CommentFormModel::create();
		if (!get_option('saveTemplates')) {
			SGRB_CommentFormModel::create();
			SGRB_TemplateDesignModel::create();
		}

		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;

			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				if($blogId != 1) {
					SGRB_ReviewModel::create($blogId);
					SGRB_CommentModel::create($blogId);
					SGRB_TemplateModel::create($blogId);
					SGRB_CategoryModel::create($blogId);
					SGRB_Comment_RatingModel::create($blogId);
					SGRB_Rate_LogModel::create($blogId);
					SGRB_Page_ReviewModel::create($blogId);
					if (!get_option('saveTemplates')) {
						SGRB_CommentFormModel::create($blogId);
						SGRB_TemplateDesignModel::create($blogId);
					}
				}
			}
		}
	}

	public static function deactivate()
	{
		if (SGRB_PRO_VERSION) {
			add_option('saveTemplates', 'saveTemplates');
		}
	}

	public static function uninstall()
	{
		global $sgrb;
		global $wpdb;

		$sgrb->includeLib('SgrbWidget');
		unregister_widget("SgrbWidget");
		delete_option('widget_sgrbwidget');
		delete_option('SGRB_VERSION');
		delete_option('saveTemplates');
		if (get_option('SGRB_SAVE_TABLES')) {
			SGRB_TemplateDesignModel::drop();
			delete_option('SGRB_SAVE_TABLES');
		}
		else {
			SGRB_TemplateModel::drop();
			SGRB_TemplateDesignModel::drop();
			SGRB_Rate_LogModel::drop();
			SGRB_Page_ReviewModel::drop();
			SGRB_Comment_RatingModel::drop();
			SGRB_CommentModel::drop();
			SGRB_CategoryModel::drop();
			SGRB_ReviewModel::drop();
			SGRB_CommentFormModel::drop();
		}

		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;

			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				if($blogId != 1) {
					if (get_option('SGRB_SAVE_TABLES')) {
						SGRB_TemplateDesignModel::drop($blogId);
						delete_option('SGRB_SAVE_TABLES');
					}
					else {
						SGRB_TemplateModel::drop($blogId);
						SGRB_TemplateDesignModel::drop($blogId);
						SGRB_Rate_LogModel::drop($blogId);
						SGRB_Page_ReviewModel::drop($blogId);
						SGRB_Comment_RatingModel::drop($blogId);
						SGRB_CommentModel::drop($blogId);
						SGRB_CategoryModel::drop($blogId);
						SGRB_ReviewModel::drop($blogId);
						SGRB_CommentFormModel::drop($blogId);
					}
				}
			}
		}
	}

	public static function createBlog()
	{

	}

}
