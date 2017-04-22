<?php

global $SGRB_AUTOLOAD;
$SGRB_AUTOLOAD = array();

$SGRB_AUTOLOAD['menu_items'] = array(
	array(
		'id' => 'showAll',
		'page_title' => 'All Reviews',
		'menu_title' => 'Review Builder',
		'capability' => 'manage_options',
		'icon' => 'dashicons-testimonial',
		'controller' => 'Review',
		'action' => 'index',
		'submenu_items' => array(
			array(
				'id' => 'showAll',
				'page_title' => 'All Reviews',
				'menu_title' => 'All Reviews',
				'capability' => 'manage_options',
				'controller' => 'Review',
				'action' => 'index',
			),
			array(
				'id' => 'add',
				'page_title' => 'Add/Edit Review',
				'menu_title' => 'Add Review',
				'capability' => 'manage_options',
				'controller' => 'Review',
				'action' => 'save',
			),
			array(
				'id' => 'allComms',

				'page_title' => 'All Comments',
				'menu_title' => 'All Comments',
				'capability' => 'manage_options',
				'controller' => 'Comment',
				'action' => 'index'
			),
			array(
				'id' => 'addComment',
				'page_title' => 'Add/Edit Comment',
				'menu_title' => 'Add Comment',
				'capability' => 'manage_options',
				'controller' => 'Comment',
				'action' => 'save',
			),
			array(
				'id' => 'allTemplates',
				'page_title' => 'All Templates',
				'menu_title' => 'All Templates <i class="sgrb-required-asterisk"> PRO</i>',
				'capability' => 'manage_options',
				'controller' => 'TemplateDesign',
				'action' => 'index',
			),
			array(
				'id' => 'addTemplate',
				'page_title' => 'Add/Edit Template',
				'menu_title' => 'Add Template <i class="sgrb-required-asterisk"> PRO</i>',
				'capability' => 'manage_options',
				'controller' => 'TemplateDesign',
				'action' => 'save',
			),
			array(
				'id' => 'allForms',
				'page_title' => 'All Forms',
				'menu_title' => 'All Forms <i class="sgrb-required-asterisk"> PRO</i>',
				'capability' => 'manage_options',
				'controller' => 'CommentForm',
				'action' => 'index',
			),
			array(
				'id' => 'addForm',
				'page_title' => 'Add/Edit Form',
				'menu_title' => 'Add Form <i class="sgrb-required-asterisk"> PRO</i>',
				'capability' => 'manage_options',
				'controller' => 'CommentForm',
				'action' => 'save',
			),
			array(
				'id' => 'sgSettings',
				'page_title' => 'Settings',
				'menu_title' => 'Settings',
				'capability' => 'manage_options',
				'controller' => 'Review',
				'action' => 'reviewSetting',
			),
			array(
				'id' => 'sgPlugins',
				'page_title' => 'Add/Edit Comment',
				'menu_title' => 'More Plugins',
				'capability' => 'manage_options',
				'controller' => 'Review',
				'action' => 'morePlugins',
			)
		),
	),
);

$SGRB_AUTOLOAD['network_admin_menu_items'] = array();

$SGRB_AUTOLOAD['shortcodes'] = array(
	array(
		'shortcode' => 'sgrb_review',
		'controller' => 'Review',
		'action' => 'sgrbShortcode',
	),
);

$SGRB_AUTOLOAD['front_ajax'] = array(
	array(
		'controller' =>'Review',
		'action' => 'ajaxLazyLoading',
	),
	array(
		'controller' =>'Review',
		'action' => 'ajaxUserRate'
	)
);

$SGRB_AUTOLOAD['admin_ajax'] = array(
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxSave',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxDelete',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxWooProductLoad',
	),
	array(
		'controller' => 'Comment',
		'action'	 => 'ajaxSave',
	),
	array(
		'controller' => 'Comment',
		'action'	 => 'ajaxDelete',
	),
	array(
		'controller' => 'Comment',
		'action'	 => 'ajaxApproveComment',
	),
	array(
		'controller' => 'Comment',
		'action'	 => 'ajaxSelectReview',
	),
	array(
		'controller' => 'Comment',
		'action'	 => 'ajaxSelectPosts',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxDeleteField',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxPostComment',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxSelectTemplate',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxPagination',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxUserRate',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxSaveFreeTables',
	),
	array(
		'controller' => 'TemplateDesign',
		'action'	 => 'ajaxSave',
	),
	array(
		'controller' => 'TemplateDesign',
		'action'	 => 'ajaxDeleteTemplate',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxCloseBanner',
	),
	array(
		'controller' => 'Review',
		'action'	 => 'ajaxCloneReview',
	)
);

$SGRB_AUTOLOAD['admin_post'] = array(
	array(
		'controller' => 'Review',
		'action'	 => 'delete',
	)
);

//use wp_ajax_library to include ajax for the frontend
$SGRB_AUTOLOAD['front_scripts'] = array();

//use wp_enqueue_media to enqueue media
$SGRB_AUTOLOAD['admin_scripts'] = array();

$SGRB_AUTOLOAD['front_styles'] = array();

$SGRB_AUTOLOAD['admin_styles'] = array();
