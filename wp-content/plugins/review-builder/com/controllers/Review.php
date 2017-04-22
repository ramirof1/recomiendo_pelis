<?php
global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeCore('Template');
$sgrb->includeCore('Form');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('TemplateDesign');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('CommentForm');
$sgrb->includeModel('Template');
$sgrb->includeModel('Category');
$sgrb->includeModel('Comment_Rating');
$sgrb->includeModel('Rate_Log');
$sgrb->includeModel('Page_Review');

class SGRB_ReviewController extends SGRB_Controller
{
	private $flag = 0;

	public function index()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/helpers/sgReviewHelper');
		$sgrb->includeScript('page/scripts/helpers/sgTemplateHelper');
		$sgrb->includeScript('page/scripts/helpers/sgCommentHelper');
		$sgrb->includeScript('page/scripts/helpers/sgRateSkin');
		$sgrb->includeScript('page/scripts/helpers/sgMainHelper');
		$sgrb->includeScript('page/scripts/sgReview');
		$sgrb->includeScript('page/scripts/sgComment');
		$sgrb->includeScript('page/scripts/sgTemplate');
		$sgrb->includeScript('page/scripts/sgForm');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$review = new SGRB_ReviewReviewView();
		$createNewUrl = $sgrb->adminUrl('Review/save');

		SGRB_AdminView::render('Review/index', array(
			'createNewUrl' => $createNewUrl,
			'review' => $review
		));
	}

	public function sgrbShortcode($atts, $content)
	{
		global $sgrb;

		$attributes = shortcode_atts(array(
			'id' => '1',
		), $atts);
		$sgrbId = (int)$attributes['id'];
		$sgrbRev = SGRB_ReviewModel::finder()->findByPk($sgrbId);
		if(!$sgrbRev){
			return false;
		}
		$arr = array();
		$title = $sgrbRev->getTitle();
		$templateId = $sgrbRev->getTemplate_id();
		$options = $sgrbRev->getOptions();
		$template = SGRB_TemplateModel::finder()->findByPk($templateId);

		$arr['title'] = $title;
		$arr['id'] = $sgrbId;
		$arr['template-id'] = $templateId;
		$arr['options'] = json_decode($options,true);
		$arr['template'] = $template;
		$sgrbDataArray[] = $arr;

		$html = $this->createReviewHtml($sgrbDataArray);
		$this->flag++;
		return $html;
	}


	public function ajaxSave()
	{
		global $wpdb;
		global $sgrb;
		$sgrb->includeCore('Template');

		$tempOptions = array();
		$options = array();
		$tagsArray = array();
		$templateImgArr = array();
		$templateTextArr = array();
		$templateUrlArr = array();
		$currentReviewProducts = array();
		$reviewId = 0;
		$isUpdate = false;
		$rateTypeNotice = @$_POST['rate-type-notice'];
		$templateImgArr = @$_POST['image_url'];
		$templateTextArr = @$_POST['input_html'];
		$templateUrlArr = @$_POST['input_url'];
		$tempName = @$_POST['sgrb-template'];
		$title = @$_POST['sgrb-title'];
		$tagsArray = @$_POST['tagsArray'];

		$tempOptions['images'] = $templateImgArr;
		$tempOptions['html'] = $templateTextArr;
		$tempOptions['url'] = $templateUrlArr;
		$tempOptions['name'] = $tempName;

		if (count($_POST)) {

			$reviewId = (int)$_POST['sgrb-id'];

			$review = new SGRB_ReviewModel();
			$isUpdate = false;

			if ($reviewId) {

				$isUpdate = true;
				$review = SGRB_ReviewModel::finder()->findByPk($reviewId);
				if (!$review) {
					exit();
				}
				$options = $review->getOptions();
				$options = json_decode($options, true);

				$wooOptions = $options['woo-products'];
				$currentReviewProducts = json_decode($wooOptions);


			}
			////////////////////////////

			$tempId = $review->getTemplate_id();
			if ($tempId) {
				$template = new Template($tempName,$tempId);
			}
			else {
				$template = new Template($tempName);
			}

			$result = $template->save($tempOptions);
			if (!$result) {
				exit();
			}

			/////////////////////////////
			$tempateShadowColor = '';
			$shadowLeftRight = '';
			$shadowTopBottom = '';
			$shadowBlur = '';
			$fields = @$_POST['field-name'];
			$fields = $this->stripslashesDeep($fields);
			$fieldId = @$_POST['fieldId'];
			$title = stripslashes(@$_POST['sgrb-title']);
			$commentFormId = @$_POST['sgrb-add-comment-form'];
			$ratingType = @$_POST['rate-type'];
			$totalRateBackgroundColor = @$_POST['total-rate-background-color'];
			$skinColor = @$_POST['skin-color'];
			$totalRate = @$_POST['totalRate'];
			$showComments = @$_POST['showComments'];
			$captchaOn = @$_POST['captchaOn'];
			$rateTextColor = @$_POST['rate-text-color'];
			$emailNotificationCheckbox = isset($_POST['email-notification-checkbox']);
			$requiredEmailCheckbox = isset($_POST['required-email-checkbox']);
			$requiredTitleCheckbox = isset($_POST['required-title-checkbox']);
			$requiredLoginCheckbox = isset($_POST['required-login-checkbox']);
			$hideCommentForm = isset($_POST['hide-comment-form']);
			$autoApprove = isset($_POST['auto-approve-checkbox']);
			$shadowOn = isset($_POST['template-field-shadow-on']);
			$googleSearch = isset($_POST['sgrb-google-search-on']);
			$emailNotification = @$_POST['email-notification'];
			$font = @$_POST['fontSelectbox'];
			$tempateBackgroundColor = @$_POST['template-background-color'];
			$tempateTextColor = @$_POST['template-text-color'];
			$tempateShadowColor = @$_POST['template-shadow-color'];
			$shadowLeftRight = @$_POST['shadow-left-right'];
			$shadowTopBottom = @$_POST['shadow-top-bottom'];
			$shadowBlur = @$_POST['shadow-blur'];
			$postCategory = @$_POST['post-category'];
			// wooCommerce
			$wooReviewShowType = @$_POST['wooReviewShowType'];

			if ($wooReviewShowType == 'showByCategory') {
				$wooProductsCategories = @$_POST['allProductsCategories'];
				$wooProductsCategories = rtrim($wooProductsCategories, ',');
				$wooCategory = explode(',', $wooProductsCategories);
				$wooCategoryString = json_encode($wooCategory);
				$wooProducts = array();
			}
			else if ($wooReviewShowType == 'showByProduct') {
				$wooCategory = array();
				$wooProductsCategories = @$_POST['allProductsCategories'];
				$wooProductsCategories = str_replace('\"', '"', $wooProductsCategories);
				$wooProductsCategories = json_decode($wooProductsCategories, true);

				if (!empty($currentReviewProducts)) {
					foreach ($wooProductsCategories as $prod => $val) {
						if ($val == 0) {
							unset($wooProductsCategories[$prod]);
						}
						else {
							$wooProductsCategories[$prod] = 1;
						}
					}
				}

				$wooProducts = $wooProductsCategories;
				$wooProductsString = json_encode($wooProductsCategories);
			}
			$disableWooComments = @$_POST['disableWooComments'];
			// wooCommerce
			$disableWPcomments = @$_POST['disableWPcomments'];
			$commentsCount = (int)@$_POST['comments-count-to-show'];
			$commentsCountLoad = (int)@$_POST['comments-count-to-load'];
			//Localization options
			$successCommentText = stripslashes(@$_POST['success-comment-text']);
			$totalRatingText = stripslashes(@$_POST['total-rating-text']);
			$addReviewText = stripslashes(@$_POST['add-review-text']);
			$editReviewText = stripslashes(@$_POST['edit-review-text']);
			$nameText = stripslashes(@$_POST['name-text']);
			$namePlaceholderText = stripslashes(@$_POST['name-placeholder-text']);
			$emailText = stripslashes(@$_POST['email-text']);
			$emailPlaceholderText = stripslashes(@$_POST['email-placeholder-text']);
			$titleText = stripslashes(@$_POST['title-text']);
			$titlePlaceholderText = stripslashes(@$_POST['title-placeholder-text']);
			$commentText = stripslashes(@$_POST['comment-text']);
			$commentPlaceholderText = stripslashes(@$_POST['comment-placeholder-text']);
			$postButtonText = stripslashes(@$_POST['post-button-text']);
			$captchaText = stripslashes(@$_POST['captcha-text']);
			$loggedInText = stripslashes(@$_POST['logged-in-text']);
			$noCategoryText = stripslashes(@$_POST['no-category-text']);
			$noNameText = stripslashes(@$_POST['no-name-text']);
			$noEmailText = stripslashes(@$_POST['no-email-text']);
			$noTitleText = stripslashes(@$_POST['no-title-text']);
			$noCommentText = stripslashes(@$_POST['no-comment-text']);
			$noCaptchaText = stripslashes(@$_POST['no-captcha-text']);
			//////////////////////
			$options['notify'] = '';
			$options['required-title-checkbox'] = '';
			$options['required-email-checkbox'] = '';
			$options['auto-approve-checkbox'] = '';
			$options['template-field-shadow-on'] = '';
			$options['sgrb-google-search-on'] = '';
			$options['disableWPcomments'] = '';
			// wooCommerce
			$options['disableWooComments'] = '';
			// wooCommerce
			$options['comments-count-to-show'] = '';
			$options['comments-count-to-load'] = '';
			$options['required-login-checkbox'] = '';
			$options['hide-comment-form'] = '';
			$options['captcha-on'] = '';
			if (SGRB_PRO_VERSION) {
				// wooCommerce
				$options['wooReviewShowType'] = $wooReviewShowType;
				// wooCommerce
				$options['captcha-text'] = $captchaText;
				$options['logged-in-text'] = $loggedInText;
				$options['no-captcha-text'] = $noCaptchaText;
				if ($commentsCount) {
					$options['comments-count-to-show'] = $commentsCount;
				}
				if ($commentsCountLoad) {
					$options['comments-count-to-load'] = $commentsCountLoad;
				}
				if ($emailNotificationCheckbox) {
					$options['notify'] = sanitize_text_field($emailNotification);
				}
				if ($shadowOn) {
					if ($shadowLeftRight && $shadowTopBottom) {
						$options['template-field-shadow-on'] = 1;
						$options['shadow-left-right'] = $shadowLeftRight;
						$options['shadow-top-bottom'] = $shadowTopBottom;
						$options['template-shadow-color'] = $tempateShadowColor;
						$options['shadow-blur'] = $shadowBlur;
					}
				}
				if ($googleSearch) {
					$options['sgrb-google-search-on'] = 1;
				}
				if ($captchaOn) {
					$options['captcha-on'] = $captchaOn;
				}
			}
			$options['tags'] = json_encode($tagsArray);
			if ($disableWPcomments) {
				$options['disableWPcomments'] = 1;
			}
			// wooCommerce
			if ($disableWooComments) {
				$options['disableWooComments'] = 1;
			}
			if ($tempOptions['name'] == 'woo_review') {
				if ($wooReviewShowType == 'showByCategory') {
					$options['woo-category'] = $wooCategoryString;
				}
				else if ($wooReviewShowType == 'showByProduct') {
					$options['woo-products'] = $wooProductsString;
				}
			}
			// wooCommerce
			if ($postCategory && $tempOptions['name'] == 'post_review') {
				$options['post-category'] = $postCategory;
			}
			if ($requiredTitleCheckbox) {
				$options['required-title-checkbox'] = 1;
			}
			if ($requiredEmailCheckbox) {
				$options['required-email-checkbox'] = 1;
			}
			if ($requiredLoginCheckbox) {
				$options['required-login-checkbox'] = 1;
			}
			if ($hideCommentForm) {
				$options['hide-comment-form'] = 1;
			}
			if ($autoApprove) {
				$options['auto-approve-checkbox'] = 1;
			}

			//localization
			$options['success-comment-text'] = $successCommentText;
			$options['total-rating-text'] = $totalRatingText;
			$options['add-review-text'] = $addReviewText;
			$options['edit-review-text'] = $editReviewText;
			$options['name-text'] = $nameText;
			$options['name-placeholder-text'] = $namePlaceholderText;
			$options['email-text'] = $emailText;
			$options['email-placeholder-text'] = $emailPlaceholderText;
			$options['title-text'] = $titleText;
			$options['title-placeholder-text'] = $titlePlaceholderText;
			$options['comment-text'] = $commentText;
			$options['comment-placeholder-text'] = $commentPlaceholderText;
			$options['post-button-text'] = $postButtonText;
			$options['no-category-text'] = $noCategoryText;
			$options['no-name-text'] = $noNameText;
			$options['no-email-text'] = $noEmailText;
			$options['no-title-text'] = $noTitleText;
			$options['no-comment-text'] = $noCommentText;
			///////////////
			$options['sgrb-add-comment-form'] = $commentFormId;
			$options['total-rate'] = $totalRate;
			$options['show-comments'] = $showComments;
			$options['total-rate-background-color'] = $totalRateBackgroundColor;
			$options['rate-type'] = $ratingType;
			$options['template-font'] = $font;
			$options['template-background-color'] = $tempateBackgroundColor;
			$options['template-text-color'] = $tempateTextColor;
			if ($rateTypeNotice != $ratingType) {
				SGRB_CommentModel::finder()->deleteAll('review_id = %d', $reviewId);
				SGRB_Rate_LogModel::finder()->deleteAll('review_id = %d', $reviewId);
			}
			$options['skin-color'] = $skinColor;
			$options['rate-text-color'] = $rateTextColor;

			$options = json_encode($options);
			$review->setTitle(sanitize_text_field($title));
			$review->setTemplate_id(sanitize_text_field($result));
			$review->setOptions(sanitize_text_field($options));

			if (!$fields[0]) {
				exit();
			}

			if (!empty($tagsArray)) {
				foreach ($tagsArray as $tags) {
					wp_create_tag($tags);
				}
			}

			$res = $review->save();

			if ($review->getId()) {
				$lastRevId = $review->getId();
			}
			else {
				if (!$res) return false;
				$lastRevId = $wpdb->insert_id;
			}

			if ($tempOptions['name'] == 'woo_review') {
				SGRB_Page_ReviewModel::finder()->deleteAll('review_id = %d', $lastRevId);
				if (!empty($wooProducts)) {
					foreach ($wooProducts as $wooProd => $val) {
						if ($val == 1) {
							$pageReview = new SGRB_Page_ReviewModel();
							$pageReview->setProduct_id(sanitize_text_field($wooProd));
							$pageReview->setReview_id(sanitize_text_field($lastRevId));
							$pageReview->save();
						}
					}
				}
				else if (!empty($wooCategory)) {
					for ($i = 0;$i < count($wooCategory);$i++) {
						$pageReview = new SGRB_Page_ReviewModel();
						$pageReview->setCategory_id(sanitize_text_field($wooCategory[$i]));
						$pageReview->setReview_id(sanitize_text_field($lastRevId));
						$pageReview->save();
					}
				}
			}

			if (!$isUpdate) {
				for ($i=0;$i<count($fields);$i++) {
					$categories = new SGRB_CategoryModel();
					$categories->setReview_id(sanitize_text_field($lastRevId));
					$categories->setName(sanitize_text_field($fields[$i]));
					$categories->save();

				}
			}

		}
		echo $lastRevId;
		exit();
	}

	public function stripslashesDeep($value)
	{
		if (is_array($value)) {
			$value = array_map(array($this, 'stripslashesDeep'), $value);
		}
		else {
			$value = stripslashes($value);
		}

		return $value;
	}

	public function save()
	{
		global $wpdb;
		global $sgrb;

		if (SGRB_PRO_VERSION) {
			$sgrb->includeStyle('page/styles/general/bootstrap-formhelpers.min');
			$sgrb->includeScript('page/scripts/helpers/bootstrap-formhelpers.min');
		}
		$sgrb->includeScript('core/scripts/jquery.rateyo');
		$sgrb->includeScript('core/scripts/jquery.barrating');
		$sgrb->includeScript('core/scripts/jquery-ui.min');
		$sgrb->includeScript('core/scripts/jquery-ui-slider-pips.min');
		$sgrb->includeScript('page/scripts/helpers/sgReviewHelper');
		$sgrb->includeScript('page/scripts/helpers/sgTemplateHelper');
		$sgrb->includeScript('page/scripts/helpers/sgCommentHelper');
		$sgrb->includeScript('page/scripts/helpers/sgRateSkin');
		$sgrb->includeScript('page/scripts/helpers/sgMainHelper');
		$sgrb->includeScript('page/scripts/sgReview');
		$sgrb->includeScript('page/scripts/sgComment');
		$sgrb->includeScript('page/scripts/sgTemplate');
		$sgrb->includeScript('page/scripts/sgForm');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrb->includeStyle('core/styles/css/jquery.rateyo');
		$sgrb->includeStyle('core/styles/css/bars-1to10');
		$sgrb->includeStyle('core/styles/css/jquery-ui.min');
		$sgrb->includeStyle('core/styles/css/jquery-ui-slider-pips.min');
		$sgrb->includeStyle('page/styles/review/save');
		$sgrb->includeStyle('page/styles/general/sg-box-cols');

		$sgrbId = 0;
		$sgrbDataArray = array();
		$sgrbCommentForms = array();
		$tagsArray = array();
		$sgrbOptions = array();
		$fields = array();
		$ratings = array();
		$allTemplates = array();
		$termsArray = array();
		$productsArray = array();
		$categoriesArray = array();
		$allPageReviews = array();
		$matchesProducts = array();
		$matchesCategories = array();

		$allTerms = get_terms(array('get' => 'all'));
		$allProductsCount = get_posts(array(
			'post_type'		=> 'product',
			'numberposts'	=> -1
		));
		if (!empty($allProductsCount)) {
			$allProductsCount = count($allProductsCount);
		}
		if (!empty($allTerms)) {
			foreach ($allTerms as $term) {
				if (get_term_meta($term->term_id)) {
					$termsArray['id'][] = $term->term_id;
					$termsArray['name'][] = $term->name;
				}
			}
		}

		$allProducts = get_posts(array('post_type' => 'product','numberposts' => 5));
		if (!empty($allProducts)) {
			foreach ($allProducts as $product) {
				$productsArray['id'][] = $product->ID;
				$productsArray['name'][] = $product->post_title;
			}
		}

		$sgrbCommentForms = SGRB_CommentFormModel::finder()->findAll();

		$tempView = new SGRB_TemplateDesignView();
		$allTemplates = SGRB_TemplateDesignModel::finder()->findAllBySql("SELECT * from ".$tempView->getTablename()."  ORDER BY ".$tempView->getTablename().".sgrb_pro_version DESC");

		isset($_GET['id']) ? $sgrbId = (int)$_GET['id'] : 0;
		$allPageReviews = SGRB_Page_ReviewModel::finder()->findAll();
		if (!empty($allPageReviews)) {
			for ($i=0;$i<count($allPageReviews);$i++) {
				for ($j=0;$j<count($productsArray['id']);$j++) {
					$catId = $allPageReviews[$i]->getCategory_id();
					$prodId = $allPageReviews[$i]->getProduct_id();
					$revId = $allPageReviews[$i]->getReview_id();
					if ($prodId) {
						if ($prodId == $productsArray['id'][$j] && $revId != $sgrbId) {
							$matchesProducts['id'][] = $prodId;
							if ($revId) {
								$matchReview = SGRB_ReviewModel::finder()->findByPk($revId);
								if ($matchReview) {
									$matchReviewTitle = $matchReview->getTitle();
									$matchesProducts['review'][] = $matchReviewTitle;
								}
							}
						}
					}
					if ($catId) {
						if ($catId == @$termsArray['id'][$j] && $revId != $sgrbId) {
							$matchesCategories['id'][] = $catId;
							if ($revId) {
								$matchReview = SGRB_ReviewModel::finder()->findByPk($revId);
								if ($matchReview) {
									$matchReviewTitle = $matchReview->getTitle();
									$matchesCategories['review'][] = $matchReviewTitle;
								}
							}
						}
					}
				}
			}
		}
		$sgrbRev = SGRB_ReviewModel::finder()->findByPk($sgrbId);
		$allCommentsUrl = $sgrb->adminUrl('Comment/index','id='.$sgrbId);
		$sgrbSaveUrl = $sgrb->adminUrl('Review/save');
		//If edit
		if ($sgrbRev) {

			$sgrbDataArray = array();

			$fields = SGRB_CategoryModel::finder()->findAll('review_id = %d', $sgrbId);
			$sgrbOptions = $sgrbRev->getOptions();
			$sgrbOptions = json_decode($sgrbOptions, true);

			$tempId = $sgrbRev->getTemplate_id();
			if (!$tempId) {
				$template = new SGRB_TemplateModel();
			}
			else {
				$template = SGRB_TemplateModel::finder()->findByPk($tempId);
				if (!$template) {
					$template = new SGRB_TemplateModel();
					$tempName = 'full_width';
				}
				else {
					$tempName = $template->getName();
				}
			}
			$temp = new Template($tempName,$tempId);
			$res = $temp->adminRender();

			$title = $sgrbRev->getTitle();
			$template = $sgrbRev->getTemplate_id();

			$options = $sgrbRev->getOptions();
			$options = json_decode($options, true);

			$sgrbDataArray['title'] = $title;
			$sgrbDataArray['notify'] = @$options["notify"];
			$sgrbDataArray['required-title-checkbox'] = @$options["required-title-checkbox"];
			$sgrbDataArray['required-email-checkbox'] = @$options["required-email-checkbox"];
			$sgrbDataArray['required-login-checkbox'] = @$options["required-login-checkbox"];
			$sgrbDataArray['hide-comment-form'] = @$options["hide-comment-form"];
			$sgrbDataArray['auto-approve-checkbox'] = @$options["auto-approve-checkbox"];
			$sgrbDataArray['sgrb-google-search-on'] = @$options["sgrb-google-search-on"];
			$sgrbDataArray['total-rate'] = @$options["total-rate"];
			$sgrbDataArray['show-comments'] = @$options["show-comments"];
			$sgrbDataArray['captcha-on'] = @$options["captcha-on"];
			$sgrbDataArray['total-rate-background-color'] = @$options["total-rate-background-color"];
			$sgrbDataArray['rate-type'] = @$options["rate-type"];
			$sgrbDataArray['skin-color'] = @$options["skin-color"];
			$sgrbDataArray['rate-text-color'] = @$options["rate-text-color"];
			$sgrbDataArray['template-font'] = @$options["template-font"];
			$sgrbDataArray['template-background-color'] = @$options["template-background-color"];
			$sgrbDataArray['template-text-color'] = @$options["template-text-color"];
			$sgrbDataArray['post-category'] = @$options["post-category"];
			$sgrbDataArray['sgrb-add-comment-form'] = @$options["sgrb-add-comment-form"];
			// wooCommerce
			$sgrbDataArray['wooReviewShowType'] = @$options["wooReviewShowType"];

			$sgrbDataArray['woo-category'] = @$options["woo-category"];
			$sgrbDataArray['woo-products'] = @$options["woo-products"];
			if ($sgrbDataArray['wooReviewShowType']) {
				if ($sgrbDataArray['wooReviewShowType'] == 'showByCategory') {
					if ($sgrbDataArray['woo-category']) {
						$sgrbDataArray['woo-category'] = json_decode($sgrbDataArray['woo-category']);
					}
				}
			}
			$sgrbDataArray['disableWooComments'] = @$options["disableWooComments"];
			// wooCommerce
			$sgrbDataArray['disableWPcomments'] = @$options["disableWPcomments"];
			$sgrbDataArray['comments-count-to-show'] = @$options["comments-count-to-show"];
			$sgrbDataArray['comments-count-to-load'] = @$options["comments-count-to-load"];
			//localization
			$sgrbDataArray['success-comment-text'] = @$options["success-comment-text"];
			$sgrbDataArray['add-review-text'] = @$options["add-review-text"];
			$sgrbDataArray['edit-review-text'] = @$options["edit-review-text"];
			$sgrbDataArray['total-rating-text'] = @$options["total-rating-text"];
			$sgrbDataArray['name-text'] = @$options["name-text"];
			$sgrbDataArray['name-placeholder-text'] = @$options["name-placeholder-text"];
			$sgrbDataArray['email-text'] = @$options["email-text"];
			$sgrbDataArray['email-placeholder-text'] = @$options["email-placeholder-text"];
			$sgrbDataArray['title-text'] = @$options["title-text"];
			$sgrbDataArray['title-placeholder-text'] = @$options["title-placeholder-text"];
			$sgrbDataArray['comment-text'] = @$options["comment-text"];
			$sgrbDataArray['comment-placeholder-text'] = @$options["comment-placeholder-text"];
			$sgrbDataArray['post-button-text'] = @$options["post-button-text"];
			$sgrbDataArray['captcha-text'] = @$options["captcha-text"];
			$sgrbDataArray['logged-in-text'] = @$options["logged-in-text"];
			$sgrbDataArray['no-category-text'] = @$options["no-category-text"];
			$sgrbDataArray['no-name-text'] = @$options["no-name-text"];
			$sgrbDataArray['no-email-text'] = @$options["no-email-text"];
			$sgrbDataArray['no-title-text'] = @$options["no-title-text"];
			$sgrbDataArray['no-comment-text'] = @$options["no-comment-text"];
			$sgrbDataArray['no-captcha-text'] = @$options["no-captcha-text"];
			$tagsArray = json_decode(@$options['tags'], true);
			$sgrbDataArray['tags'] = @$tagsArray;
			//////////////
			if (@$options['template-field-shadow-on']) {
				$sgrbDataArray['template-field-shadow-on'] = @$options['template-field-shadow-on'];
				$sgrbDataArray['shadow-left-right'] = @$options["shadow-left-right"];
				$sgrbDataArray['shadow-top-bottom'] = @$options["shadow-top-bottom"];
				$sgrbDataArray['template-shadow-color'] = @$options["template-shadow-color"];
				$sgrbDataArray['shadow-blur'] = @$options["shadow-blur"];
			}

			$selectedTemplate = SGRB_TemplateModel::finder()->findByPk($template);
			$sgrbDataArray['template'] = $selectedTemplate->getName();
		}
		else {
			$sgrbRev = new SGRB_ReviewModel();
			$sgrbId = 0;
			$temp = new Template('full_width');
			$res = $temp->adminRender();
		}
		SGRB_AdminView::render('Review/save', array(
			'sgrbDataArray'  => $sgrbDataArray,
			'sgrbCommentForms'  => $sgrbCommentForms,
			'sgrbSaveUrl'    => $sgrbSaveUrl,
			'sgrbRevId'      => $sgrbId,
			'sgrbRev' 		 => $sgrbRev,
			'fields' 		 => $fields,
			'ratings' 		 => $ratings,
			'allCommentsUrl' => $allCommentsUrl,
			'res' 			 => $res,
			'allTemplates' 	 => $allTemplates,
			'termsArray' 	 => $termsArray,
			'allProductsCount' 	 => $allProductsCount,
			'productsArray'  => $productsArray,
			'categoriesArray'  => $categoriesArray,
			'allPageReviews' => $allPageReviews,
			'matchesProducts'=> $matchesProducts,
			'matchesCategories'=> $matchesCategories
		));
	}

	public function ajaxWooProductLoad()
	{
		global $sgrb;
		global $wpdb;
		$allProductsString = '';
		$productsArray = array();
		$allPageReviews = array();
		$selectedProducts = array();
		$start = $_POST['start'];
		$reviewId = $_POST['reviewId'];
		$perPage = $_POST['perPage'];

		$allPageReviews = SGRB_Page_ReviewModel::finder()->findAll('review_id <> %d', $reviewId);

		if ($reviewId) {
			$review = SGRB_ReviewModel::finder()->findByPk($reviewId);
			if (!$review) {
				$selectedProducts = array();
			}
		}

		$options = $review->getOptions();
		$options = json_decode($options, true);
		$selectedProducts = $options['woo-products'];
		$selectedProducts = json_decode($selectedProducts, true);

		if (!$selectedProducts) {
			$selectedProducts = array();
		}

		foreach ($selectedProducts as $product => $value) {
			if ($value == 0) {
				unset($selectedProducts[$product]);
			}
		}

		$allProducts = get_posts(array(
			'post_type'		=> 'product',
			'numberposts'	=> $perPage,
			'offset'		=> $start
		));

		$matchProdArray = array();
		if (!empty($allPageReviews)) {
			for ($k=0;$k<count($allPageReviews);$k++) {
				$revId = $allPageReviews[$k]->getReview_id();
				if ($revId) {
					$review = SGRB_ReviewModel::finder()->findByPk($revId);
					if ($review) {
						$reviewName = $review->getTitle();
					}
				}
				$prodId = $allPageReviews[$k]->getProduct_id();
				$matchProdArray[$k]['id'] = $prodId;
				$matchProdArray[$k]['name'] = $reviewName;
			}
		}

		if (!$allProducts) {
			$allProducts = array();
		}
		for ($i=0;$i<count($allProducts);$i++) {
			if (!$selectedProducts) {
				$productsArray[$i]['id'] = $allProducts[$i]->ID;
				$productsArray[$i]['name'] = $allProducts[$i]->post_title;
				$productsArray[$i]['checked'] = '';
				$productsArray[$i]['checkedClass'] = '';

				$selectedProducts = array();
				continue;
			}
			foreach ($selectedProducts as $key => $val) {
				$productsArray[$i]['id'] = $allProducts[$i]->ID;
				$productsArray[$i]['name'] = $allProducts[$i]->post_title;
				$productsArray[$i]['checked'] = '';
				$productsArray[$i]['checkedClass'] = 'sgrb-selected-products';
				if ($selectedProducts[$productsArray[$i]['id']] == 1) {
					$productsArray[$i]['checked'] = ' checked';
					break;
				}
			}

			if (!empty($matchProdArray)) {
				$productsArray[$i]['matchProdId'] = '';
				$productsArray[$i]['matchReview'] = '';

				$matchProdArray = array();
				continue;
			}
			for ($k=0;$k<count($matchProdArray);$k++) {
				$productsArray[$i]['matchProdId'] = '';
				$productsArray[$i]['matchReview'] = '';
				if ($productsArray[$i]['id'] == $matchProdArray[$k]['id']) {
					$productsArray[$i]['matchProdId'] = ' disabled';
					$productsArray[$i]['checkedClass'] = '';
					$productsArray[$i]['matchReview'] = ' - <i class="sgrb-is-used">used in </i> '.$matchProdArray[$k]['name'].'<i class="sgrb-is-used"> review</i>';
					break;
				}
			}
		}
		die(json_encode($productsArray));
	}

	public function morePlugins()
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/review/save');
		$sgrb->includeStyle('page/styles/general/sg-box-cols');
		SGRB_AdminView::render('Review/morePlugins');
	}

	public function reviewSetting()
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/review/save');
		$sgrb->includeStyle('page/styles/general/sg-box-cols');
		$sgrb->includeScript('page/scripts/sgReview');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');

		SGRB_AdminView::render('Review/reviewSetting');
	}

	public function ajaxSaveFreeTables()
	{
		global $sgrb;
		global $wpdb;
		$deleteTables = (int)@$_POST['saveFreeTables'];
		$result = false;
		if ($deleteTables) {
			update_option('SGRB_SAVE_TABLES', 'SGRB_SAVE_TABLES');
		}
		else {
			delete_option('SGRB_SAVE_TABLES');
		}
		die(1);
	}

	public function ajaxCloseBanner()
	{
		$result = delete_option(SG_REVIEW_BANNER);
		die($result);
	}

	// delete review
	public function ajaxDelete()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		$deletedReview = SGRB_ReviewModel::finder()->findByPk($id);
		SGRB_CategoryModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_TemplateModel::finder()->deleteByPk($deletedReview->getTemplate_id());
		SGRB_CategoryModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_CommentModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_Rate_LogModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_Page_ReviewModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_ReviewModel::finder()->deleteByPk($id);
		exit();
	}

	// delete review field
	public function ajaxDeleteField()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		SGRB_CategoryModel::finder()->deleteByPk($id);
		SGRB_Comment_RatingModel::finder()->deleteAll('category_id = %d', $id);
		exit();
	}

	public function ajaxCloneReview()
	{
		global $sgrb;
		global $wpdb;
		$lastRevId = 0;
		$categoriesToClone = array();
		$id = (int)$_POST['id'];
		$reviewToClone = SGRB_ReviewModel::finder()->findByPk($id);
		if (!$reviewToClone) {
			exit($lastRevId);
		}
		$reviewTitle = $reviewToClone->getTitle();
		$reviewTemplateId = $reviewToClone->getTemplate_id();
		$reviewOptions = $reviewToClone->getOptions();

		$templateToClone = SGRB_TemplateModel::finder()->findByPk($reviewTemplateId);
		if (!$templateToClone) {
			exit($lastRevId);
		}
		$templateName = $templateToClone->getName();
		$templateOptions = $templateToClone->getOptions();

		$newTemplate = new SGRB_TemplateModel();
		$newTemplate->setName($templateName);
		$newTemplate->setOptions($templateOptions);
		$newTemplate->save();

		$newTemplateId = $wpdb->insert_id;

		$newReview = new SGRB_ReviewModel();
		$newReview->setTitle($reviewTitle);
		$newReview->setTemplate_id($newTemplateId);
		$newReview->setOptions($reviewOptions);
		$newReview->save();

		$lastRevId = $wpdb->insert_id;

		$categoriesToClone = SGRB_CategoryModel::finder()->findAll('review_id = %d', $id);
		if (empty($categoriesToClone)) {
			exit();
		}

		foreach ($categoriesToClone as $category) {
			$categoriesToCloneName = $category->getName();
			$newCategory = new SGRB_CategoryModel();
			$newCategory->setReview_id($lastRevId);
			$newCategory->setName($categoriesToCloneName);
			$newCategory->save();
		}

		exit($lastRevId);

	}

	public function createWidgetReviewHtml ($review)
	{
		$arr = array();
		$html = '';
		if ($review) {
			$title = $review->getTitle();
			$templateId = $review->getTemplate_id();
			$options = $review->getOptions();
			$template = SGRB_TemplateModel::finder()->findByPk($templateId);
			$templateOptions = $template->getOptions();
			$templateOptions = json_decode($templateOptions, true);

			$arr['title'] = $title;
			$arr['id'] = $review->getId();
			$arr['template-id'] = $templateId;
			$arr['options'] = json_decode($options,true);
			$arr['template'] = $template;
			$arr['widget-image'] = $templateOptions['images'][0];
			$sgrbDataArray[] = $arr;
			$html .= $this->createReviewHtml($sgrbDataArray, true);
		}

		return $html;
	}

	public function createPostReviewHtml ($review)
	{
		global $post;

		$currentPost = get_post();
		if (!is_object($currentPost)) {
			return false;
		}
		$postMetaValue = get_post_meta($currentPost->ID);

		if (empty($postMetaValue)) {
			return false;
		}
		if (!@$postMetaValue['post_review'] || @empty($postMetaValue['post_review'])) {
			return false;
		}
		else {
			$postReviewId = $postMetaValue['post_review'][0];

			if ($postReviewId != $review->getId()) {
				return false;
			}
		}
		$arr = array();
		$title = $review->getTitle();
		$templateId = $review->getTemplate_id();
		$options = $review->getOptions();
		$template = SGRB_TemplateModel::finder()->findByPk($templateId);

		$arr['title'] = $title;
		$arr['id'] = $review->getId();
		$arr['template-id'] = $templateId;
		$arr['options'] = json_decode($options,true);
		$arr['template'] = $template;
		$sgrbDataArray[] = $arr;

		$html = $this->createReviewHtml($sgrbDataArray);
		return $html;
	}

	// wooCommerce
	public function createWooReviewHtml ($review)
	{
		$arr = array();
		$title = $review->getTitle();
		$templateId = $review->getTemplate_id();
		$options = $review->getOptions();
		$template = SGRB_TemplateModel::finder()->findByPk($templateId);
		$arr['title'] = $title;
		$arr['id'] = $review->getId();
		$arr['template-id'] = $templateId;
		$arr['options'] = json_decode($options,true);
		$arr['template'] = $template;
		$sgrbDataArray[] = $arr;
		$html = $this->createReviewHtml($sgrbDataArray);
		return $html;
	}

	// create all review front
	private function createReviewHtml($review, $isWidget=false)
	{
		global $sgrb;
		$userLoggedIn = false;
		if (SGRB_PRO_VERSION) {
			$sgrb->includeStyle('page/styles/general/bootstrap-formhelpers.min');
			$sgrb->includeScript('page/scripts/helpers/bootstrap-formhelpers.min');
		}
		$sgrb->includeStyle('core/styles/css/main-front');
		$sgrb->includeStyle('page/styles/general/sg-box-cols');
		// SGRB.php 431 and 448 line
		//$sgrb->includeStyle('page/styles/review/save');
		$sgrb->includeScript('page/scripts/helpers/sgRateSkin');
		$sgrb->includeScript('page/scripts/helpers/sgReviewHelper');
		$sgrb->includeScript('page/scripts/helpers/sgTemplateHelper');
		$sgrb->includeScript('page/scripts/helpers/sgCommentHelper');
		$sgrb->includeScript('page/scripts/helpers/sgMainHelper');
		$sgrb->includeScript('page/scripts/sgReview');
		$sgrb->includeScript('page/scripts/sgComment');
		$sgrb->includeScript('page/scripts/sgTemplate');
		$sgrb->includeScript('page/scripts/sgForm');

		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/jquery.cookie');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');

		if (!$review) {
			return false;
		}

		$commentRatingModel = new SGRB_Comment_RatingModel();
		$commentTablename = $sgrb->tablename($commentRatingModel::TABLE);

		$html = '';
		$commentForm = '';
		$categoriesToRate = '';
		$ratedHtml = false;
		$customForm = false;

		$mainTemplate = $review[0]['template'];
		if (!$mainTemplate) {
			return false;
		}

		$template = new Template($mainTemplate->getName(),$mainTemplate->getId());
		$result = $template->render();
		$result = '<div class="sgrb-template-part-wrapper">'.$result.'</div>';

		$categories = SGRB_CategoryModel::finder()->findAll('review_id = %d', $review[0]['id']);
		$ratesArray = array();
		$eachRatesArray = array();
		if (!$review[0]['options']['total-rate-background-color']) {
			$review[0]['options']['total-rate-background-color'] = '#fbfbfb';
		}

		if (!$review[0]['options']['rate-text-color']) {
			$review[0]['options']['rate-text-color'] = '#4c4c4c';
		}

		$postId = '';
		$sgrbWidgetWrapperStyles = '';
		$sgrbWidgetWrapper = 'sgrb-common-wrapper';
		$closeHtml = '';
		$eachCategoryHide = '';
		$isPostReview = false;
		$currentPost = get_post();
		$currentPostId = $currentPost->ID;
		$currentPostType = $currentPost->post_type;
		if (is_singular('post') && !is_page() && SGRB_PRO_VERSION && ($mainTemplate->getName() == 'post_review') || ($mainTemplate->getName() == 'woo_review') || $currentPostType == 'product') {
			$currentPost = get_post();
			$postId = get_post()->ID;
			$isPostReview = true;
			$result = '<div class="sg-template-wrapper"></div>';
			$closeHtml = '</div></div></div>';
		}
		else if ($isWidget) {
			$result = '<div class="sg-template-wrapper"><img src="'.$review[0]["widget-image"].'" width="280" height="210"></div>';
			$eachCategoryHide = 'style="display:none;"';
			$sgrbWidgetWrapper = 'sgrb-widget-wrapper';
			$sgrbWidgetWrapperStyles = 'background-color:'.esc_attr(@$review[0]['options']['total-rate-background-color']).';color: '.@$review[0]['options']['rate-text-color'].';';
		}

		if ($review[0]['options']['total-rate'] == 1 || SGRB_PRO_VERSION) {
			$countRates = 0;
			foreach ($categories as $category) {
				if ($isPostReview) {
					$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d && post_id = %d', array($review[0]['id'], 1, $postId));
				}
				else {
					$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d', array($review[0]['id'], 1));
				}
				$sgrbIndex = 0;
				foreach ($approvedComments as $approvedComment) {
					$sgrbIndex++;
					$rates = SGRB_Comment_RatingModel::finder()->findAll('category_id = %d && comment_id = %d', array($category->getId(), $approvedComment->getId()));
					$eachRates = SGRB_Comment_RatingModel::finder()->findBySql('SELECT AVG(rate) AS average, category_id FROM '.$commentTablename.' WHERE category_id='.$category->getId().' GROUP BY category_id');
					$ratesArray[] = $rates;
					$eachRatesArray[$category->getId()][] = $eachRates;
				}
			}
			$countRates = 0;
			$rating = 0;

			foreach ($ratesArray as $rate) {
				$countRates += 1;
				if (!empty($rate)) {
					$rating += $rate[0]->getRate();
				}
			}
			if (!$countRates) {
				$totalRate = 0;
			}
			else {
				$totalRate = round($rating / $countRates);
			}

		}
		$commentsArray = array();
		if ($isPostReview) {
			$userIps = SGRB_Rate_LogModel::finder()->findAll('review_id = %d && post_id = %d', array($review[0]['id'], $postId));
		}
		else {
			$userIps = SGRB_Rate_LogModel::finder()->findAll('review_id = %d', $review[0]['id']);
		}
		foreach ($categories as $category) {
			if ($isPostReview) {
				$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d && post_id = %d', array($review[0]['id'], 1, $postId));
			}
			else {
				$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d', array($review[0]['id'], 1));
			}

			$commentsArray = $approvedComments;
		}

		$allApprovedComments = '<div class="sgrb-approved-comments-to-show">';
		$allApprovedComments .= '</div>';
		if (!@$review[0]['options']['comments-count-to-show']) {
			@$review[0]['options']['comments-count-to-show'] = SGRB_COMMENTS_PER_PAGE;
		}
		if (!@$review[0]['options']['comments-count-to-load']) {
			!@$review[0]['options']['comments-count-to-load'] = 3;
		}
		if (!@$review[0]['options']['show-comments']) {
			$allApprovedComments = '';
		}

		if ($commentsArray && @$review[0]['options']['show-comments'] == 1) {
			$allApprovedComments .= '<div class="sgrb-pagination" style="background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';">';
			$allApprovedComments .= '<input class="sgrb-comments-per-page" type="hidden" value="'.@$review[0]['options']['comments-count-to-show'].'">';
			$perPage = @$review[0]['options']['comments-count-to-show'];
			$tmp = ceil(count($commentsArray)/SGRB_COMMENTS_PER_PAGE);
			$allApprovedComments .= '<input class="sgrb-page-count" type="hidden" value="'.$tmp.'">';
			$allApprovedComments .= '<input class="sgrb-comments-count" type="hidden" value="'.@$review[0]['options']['comments-count-to-show'].'">';
			$allApprovedComments .= '<input class="sgrb-comments-count-load" type="hidden" value="'.@$review[0]['options']['comments-count-to-load'].'">';
			$allApprovedComments .= '<input class="sgrb-post-id" type="hidden" value="'.$postId.'">';
			$allApprovedComments .= '<i class="sgrb-loading-spinner"><img src='.$sgrb->app_url.'/assets/page/img/comment-loader.gif></i>';
			$allApprovedComments .= '<a class="sgrb-comment-load" href="javascript:void(0)">Load more</a>';
			$allApprovedComments .= '</div>';
		}

		$commentFieldAsterisk = '<i class="sgrb-comment-form-asterisk">*</i>';
		$titleRequiredAsterisk = '';
		$emailRequiredAsterisk = '';
		if (@$review[0]['options']['required-title-checkbox']) {
			$titleRequiredAsterisk = $commentFieldAsterisk;
		}
		if (@$review[0]['options']['required-email-checkbox']) {
			$emailRequiredAsterisk = $commentFieldAsterisk;
		}

		$user = wp_get_current_user();
		if (@$review[0]['options']['required-login-checkbox']) {
			if ($user->exists()) {
				$userLoggedIn = true;
			}
			else {
				$userLoggedIn = false;
			}
		}
		else {
			$userLoggedIn = true;
		}
		//localization
		$thankText = 'Thank You for Your Comment!';
		$totalText = 'Total rating';
		$addReviewText = 'Add your own review';
		$editReviewText = 'Add your own review';
		$nameText = 'Your name';
		$namePlaceholderText = 'name';
		$emailText = 'Email';
		$emailPlaceholderText = 'email';
		$titleText = 'Title';
		$titlePlaceholderText = 'title';
		$commentText = 'Comment';
		$commentPlaceholderText = 'Your Comment here';
		$postCommentText = 'Post Comment';
		$noRateText = 'Please, rate all suggested categories';
		$noNameText = 'Name is required';
		$noEmailText = 'Invalid email address';
		$noTitleText = 'Title is required';
		$noCommentText = 'Comment text is required';
		$noCaptchaText = 'Invalid captcha text';
		$notLoggedInText = 'Sorry, to leave a review you need to log in';
		if (@$review[0]['options']['success-comment-text']) {
			$thankText = @$review[0]['options']['success-comment-text'];
		}
		if (@$review[0]['options']['total-rating-text']) {
			$totalText = @$review[0]['options']['total-rating-text'];
		}
		if (@$review[0]['options']['add-review-text']) {
			$addReviewText = @$review[0]['options']['add-review-text'];
		}
		if (@$review[0]['options']['edit-review-text']) {
			$editReviewText = @$review[0]['options']['edit-review-text'];
		}
		if (@$review[0]['options']['name-text']) {
			$nameText = @$review[0]['options']['name-text'];
		}
		if (@$review[0]['options']['name-placeholder-text']) {
			$namePlaceholderText = @$review[0]['options']['name-placeholder-text'];
		}
		if (@$review[0]['options']['email-text']) {
			$emailText = @$review[0]['options']['email-text'];
		}
		if (@$review[0]['options']['email-placeholder-text']) {
			$emailPlaceholderText = @$review[0]['options']['email-placeholder-text'];
		}
		if (@$review[0]['options']['title-text']) {
			$titleText = @$review[0]['options']['title-text'];
		}
		if (@$review[0]['options']['title-placeholder-text']) {
			$titlePlaceholderText = @$review[0]['options']['title-placeholder-text'];
		}
		if (@$review[0]['options']['comment-text']) {
			$commentText = @$review[0]['options']['comment-text'];
		}
		if (@$review[0]['options']['comment-placeholder-text']) {
			$commentPlaceholderText = @$review[0]['options']['comment-placeholder-text'];
		}
		if (@$review[0]['options']['post-button-text']) {
			$postCommentText = @$review[0]['options']['post-button-text'];
		}
		if (@$review[0]['options']['no-category-text']) {
			$noRateText = @$review[0]['options']['no-category-text'];
		}
		if (@$review[0]['options']['no-name-text']) {
			$noNameText = @$review[0]['options']['no-name-text'];
		}
		if (@$review[0]['options']['no-email-text']) {
			$noEmailText = @$review[0]['options']['no-email-text'];
		}
		if (@$review[0]['options']['no-title-text']) {
			$noTitleText = @$review[0]['options']['no-title-text'];
		}
		if (@$review[0]['options']['no-comment-text']) {
			$noCommentText = @$review[0]['options']['no-comment-text'];
		}
		if (@$review[0]['options']['no-captcha-text']) {
			$noCaptchaText = @$review[0]['options']['no-captcha-text'];
		}
		if (@$review[0]['options']['logged-in-text']) {
			$notLoggedInText = @$review[0]['options']['logged-in-text'];
		}

		$eachCategoryRate = false;
		$currentCommentId = 0;
		$currentComment = new SGRB_CommentModel();
		$UserComTitle = '';
		$UserComName = '';
		$UserComEmail = '';
		$UserComComment = '';
		$hideForm = false;
		foreach ($userIps as $userIp) {
			if (self::getClientIpAddress() == $userIp->getIp()) {
				//  || isset($_COOKIE['rater'])

				$currentCommentId = $userIp->getComment_id();
				if (!$currentCommentId) {
					$hideForm = true;
				}
				$eachCategoryRate = true;
				if ($isPostReview) {
					if ($userIp->getPost_id() == $postId) {
						//$commentForm = '';
						$ratedHtml = true;
					}
					else {
						$currentCommentId = 0;
					}
				}
				else {
					//$commentForm = '';
					$ratedHtml = true;
				}
			}
			else {
				$eachCategoryRate = true;
			}
			if (!$currentCommentId) {
				$currentCommentId = 0;
			}
		}
		$sgrbEachEditableRate = array();

		if ($currentCommentId) {
			$hideForm = false;
			$currentComment = SGRB_CommentModel::finder()->findByPk($currentCommentId);
			if (!$currentComment) {
				$currentComment = new SGRB_CommentModel();
				$sgrbEachEditableRate = array();
			}
			else {
				$UserComTitle = $currentComment->getTitle();
				$UserComName = $currentComment->getName();
				$UserComEmail = $currentComment->getEmail();
				$UserComComment = $currentComment->getComment();
				$sgrbEachEditableRate = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $currentCommentId);
				$addReviewText = $editReviewText;
			}
		}

		$mainCommentsCount = count($commentsArray);
		$sgrbWidgetTooltip = '';
		if ($isWidget) {
			$sgrbWidgetTooltip = '-widget';
		}
		$sgrbSearchCommentsCount = '';
		if (empty($commentsArray) || !$commentsArray) {
			$allApprovedComments = '';
			$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">no rates</span></div>';
		}
		else {
			if ($mainCommentsCount == 1) {
				$sgrbSearchCommentsCount = $mainCommentsCount;
				$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">'.$mainCommentsCount.' rate</span></div>';
			}
			else {
				$sgrbSearchCommentsCount = $mainCommentsCount;
				$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">'.$mainCommentsCount.' rates</span></div>';
			}
			//$allApprovedComments = '';
		}
// template options first column
		$templateStyles = '';

		if (@$review[0]['options']['template-background-color']) {
			$templateStyles .= 'background-color: '.@$review[0]["options"]["template-background-color"].';';
		}
		if (@$review[0]['options']['template-text-color']) {
			$templateStyles .= 'color: '.@$review[0]["options"]["template-text-color"].';';
		}
		if (@$review[0]['options']['template-font']) {
			$templateStyles .= 'font-family: '.@$review[0]["options"]["template-font"].';';
		}

		if ($templateStyles) $templateStyles = 'style="'.$templateStyles.'"';

		// template shadow options
		$templateShadow = '';

		if (@$review[0]['options']['template-field-shadow-on']) {
			if (@$review[0]["options"]["shadow-left-right"] && @$review[0]["options"]["shadow-top-bottom"]) {
				$templateShadow .= 'box-shadow: '.@$review[0]["options"]["shadow-left-right"].'px '.@$review[0]["options"]["shadow-top-bottom"].'px ';
				if (@$review[0]['options']['shadow-blur']) {
					$templateShadow .= @$review[0]['options']['shadow-blur'].'px ';
				}
				if (@$review[0]['options']['template-shadow-color']) {
					$templateShadow .= @$review[0]['options']['template-shadow-color'];
				}
			}
		}

		$html .= '<input class="sgrb-template-shadow-style" type="hidden" value="'.$templateShadow.'">';
		$html .= '<div class="sgrb-template-custom-style" '.$templateStyles.'>';

		$totalRateSymbol = '';
		if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_STAR) {
			$totalRateSymbol = '&#9733';
			$bestRating = 5;
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_PERCENT) {
			$totalRateSymbol = '%';
			$bestRating = 100;
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_POINT) {
			$totalRateSymbol = '&#8226';
			$bestRating = 10;
		}

		// show google search
		$googleSearchResult = '';
		if (SGRB_PRO_VERSION && @$review[0]['options']['sgrb-google-search-on']) {
			if ($totalRate) {
				$googleSearchResult .= '<div itemscope="" itemtype="http://schema.org/AggregateRating">
											<span style="display:none;" itemprop="itemreviewed">'.@$review[0]['title'].'</span>
											<meta content="'.@$totalRate.'" itemprop="ratingValue">
											<meta content="'.@$sgrbSearchCommentsCount.'" itemprop="ratingCount">
											<meta content="'.@$sgrbSearchCommentsCount.'" itemprop="reviewCount">
											<meta content="'.@$bestRating.'" itemprop="bestRating">
											<meta content="1" itemprop="worstRating"></div>';
			}
			else {
				$googleSearchResult .= '<div style="display:none;" itemscope="" itemtype="http://schema.org/AggregateRating">
											<span itemprop="itemreviewed">'.@$review[0]['title'].'</span>
											<meta content="0" itemprop="ratingValue">
											<meta content="0" itemprop="ratingCount">
											<meta content="0" itemprop="reviewCount">
											<meta content="'.@$bestRating.'" itemprop="bestRating">
											<meta content="1" itemprop="worstRating"></div>';
			}
		}

		/////////
		$sgrbFakeId = mt_rand();
		$html .= $googleSearchResult;
		$html .= $result;

		$html .= '<input value="'.$sgrbFakeId.'" type="hidden" class="sgrb-reviewFakeId" name="reviewFakeId">';
		$html .= '<input value="'.esc_attr(@$review[0]['id']).'" type="hidden" class="sgrb-reviewId" name="reviewId">';

		$html .= '<input value="'.esc_attr(self::getClientIpAddress()).'" type="hidden" class="sgrb-cookie">';
		$html .= '<input value="'.$review[0]['options']['required-title-checkbox'].'" type="hidden" class="sgrb-requiredTitle">';
		$html .= '<input value="'.$review[0]['options']['required-email-checkbox'].'" type="hidden" class="sgrb-requiredEmail">';

		$html .= '<input class="sgrb-skin-color" type="hidden" value="'.esc_attr(@$review[0]['options']['skin-color']).'">';
		$html .= '<input class="sgrb-current-font" type="hidden" value="'.esc_attr(@$review[0]['options']['template-font']).'">';
		$html .= '<input class="sgrb-rating-type" type="hidden" value="'.esc_attr(@$review[0]['options']['rate-type']).'">';
		$html .= '<input class="sgrb-rate-text-color" type="hidden" value="'.esc_attr(@$review[0]['options']['rate-text-color']).'">';
		$html .= '<input class="sgrb-rate-background-color" type="hidden" value="'.esc_attr(@$review[0]['options']['total-rate-background-color']).'">';

		$totalRateHtml = '<div class="sgrb-total-rate-wrapper" style="background-color:'.esc_attr(@$review[0]['options']['total-rate-background-color']).';color: '.@$review[0]['options']['rate-text-color'].';">
					<div class="sgrb-total-rate-title">
					<div class="sgrb-total-rate-title-text"><span>'.@$totalText.'</span></div></div>
					<div class="sgrb-total-rate-count">'.@$mainCommentsCount.'
					<div class="sgrb-total-rate-count-text sgrb-show-tooltip'.@$sgrbWidgetTooltip.'" title=""><span>'.esc_attr(@$totalRate).$totalRateSymbol.'</span></div></div>';

		$mainStyles = '';

		if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_STAR) {
			$sgrb->includeScript('core/scripts/jquery.rateyo');
			$sgrb->includeStyle('core/styles/css/jquery.rateyo');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr(@$review[0]['options']['total-rate-background-color']).';color: '.@$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				$index = 0;
				$sgRate = 0;
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}
					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					$html .= '<div class="sgrb-rate-each-skin-wrapper"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><div class="rateYoTotal"></div><div class="sgrb-counter"></div></div></div>';

					$categoriesToRate .= '<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">';
					$categoriesToRate .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="">
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					if (!@empty($sgrbEachEditableRate)) {
						if ($currentCommentId && (@$sgrbEachEditableRate[$index]->getCategory_id() == $category->getId())) {
							$sgRate = $sgrbEachEditableRate[$index]->getRate();
						}
					}

					$categoriesToRate .= '<div class="sgrb-rate-each-skin-wrapper sgrb-rate-clicked-count"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><input name="sgRate" type="hidden" value="'.$sgRate.'"><div class="rateYo"></div><div class="sgrb-counter"></div></div></div>';
					$index++;
				}
			}
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_PERCENT) {
			$sgrb->includeScript('core/scripts/jquery-ui.min');
			$sgrb->includeScript('core/scripts/jquery-ui-slider-pips.min');
			$sgrb->includeStyle('core/styles/css/jquery-ui.min');
			$sgrb->includeStyle('core/styles/css/jquery-ui-slider-pips.min');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				$index = 0;
				$sgRate = 0;
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}

					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					$mainStyles = '';
					if ($review[0]['options']['skin-color']) {
						$mainStyles .= 'background-color:'.esc_attr($review[0]['options']['skin-color']).';';
					}
					if ($mainStyles) $mainStyles = ' style="'.$mainStyles.'"';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					$html .= '<div class="sgrb-each-percent-skin-wrapper"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><div '.$mainStyles.' class="circles-slider"></div></div></div>';

					$categoriesToRate .= '<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">';
					$categoriesToRate .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="'.@$eachCategoryRate.'">
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					$mainStyles = '';
					if ($review[0]['options']['skin-color']) {
						$mainStyles .= 'background-color:'.esc_attr($review[0]['options']['skin-color']).';';
					}
					if ($mainStyles) $mainStyles = ' style="'.$mainStyles.'"';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					if (!@empty($sgrbEachEditableRate)) {
						if ($currentCommentId && ($sgrbEachEditableRate[$index]->getCategory_id() == $category->getId())) {
							$sgRate = $sgrbEachEditableRate[$index]->getRate();
						}
					}
					$categoriesToRate .= '<div class="sgrb-each-percent-skin-wrapper sgrb-rate-clicked-count"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><div '.$mainStyles.' class="circles-slider sgrb-circle-total"></div><input name="sgRate" type="hidden" value="'.$sgRate.'"></div></div>';
					$index++;
				}
			}
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_POINT) {
			$sgrb->includeScript('core/scripts/jquery.barrating');
			$sgrb->includeStyle('core/styles/css/bars-1to10');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				$index = 0;
				$sgRate = 0;
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}
					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
								<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					$html .= '<div class="sgrb-rate-each-skin-wrapper">
							<input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'">
							<select class="sgrb-point">
								  <option value="1">1</option>
								  <option value="2">2</option>
								  <option value="3">3</option>
								  <option value="4">4</option>
								  <option value="5">5</option>
								  <option value="6">6</option>
								  <option value="7">7</option>
								  <option value="8">8</option>
								  <option value="9">9</option>
								  <option value="10">10</option>
							</select></div></div>';

					$categoriesToRate .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="">
							<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">
								<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray)) {
						$eachCategoryRate = $totalRate;
						$eCatId = $category->getId();
						$eCatId = $eachRatesArray[$eCatId];
						$eCatId = $eCatId[0];
						if (!empty($eCatId)) {
							if (count($categories)>1) {
								$eachCategoryRate = round($eCatId->getAverage(), 1);
							}
						}
					}
					if (!@empty($sgrbEachEditableRate)) {
						if ($currentCommentId && ($sgrbEachEditableRate[$index]->getCategory_id() == $category->getId())) {
							$sgRate = $sgrbEachEditableRate[$index]->getRate();
						}
					}
					$categoriesToRate .= '<div class="sgrb-rate-each-skin-wrapper sgrb-rate-clicked-count">
						<input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'">
						<input name="sgRate" type="hidden" value="'.$sgRate.'">
						<select class="sgrb-point-user-edit">
							  <option value="1">1</option>
							  <option value="2">2</option>
							  <option value="3">3</option>
							  <option value="4">4</option>
							  <option value="5">5</option>
							  <option value="6">6</option>
							  <option value="7">7</option>
							  <option value="8">8</option>
							  <option value="9">9</option>
							  <option value="10">10</option>
						</select></div></div>';
					$index++;
				}
			}
		}

		$hideCommentForm = @$review[0]['options']['hide-comment-form'];
		if ($hideCommentForm) {
			$hideCommentForm = ' sgrb-hide';
		}
		$commentForm = '<div class="sgrb-user-comment-wrapper'.$hideCommentForm.'" style="background-color: '.@$review[0]['options']['total-rate-background-color'].';color: '.$review[0]['options']['rate-text-color'].';">
							<div class="sgrb-hide-show-wrapper">
								<div id="sgrb-review-form-title" class="sgrb-front-comment-rows">
									<span class="sgrb-comment-title">'.$addReviewText.'</span>
								</div>
								<div class="sgrb-notice-rates"><span class="sgrb-notice-rates-text"></span></div>';
		if (!$userLoggedIn) {
			$categoriesToRate = $notLoggedInText;
		}
		$commentForm .= '<div class="sgrb-show-hide-comment-form">'.$categoriesToRate;
		$captchaHtml = '';
		if (SGRB_PRO_VERSION && @$review[0]['options']['captcha-on'] && !$isWidget) {
			$sgrb->includeScript('core/scripts/jquery.plugin');
			$sgrb->includeScript('core/scripts/jquery.realperson');
			$sgrb->includeStyle('core/styles/css/jquery.realperson');
			$captchaText = 'Change image';
			if (@$review[0]['options']['captcha-text']) {
				$captchaText = @$review[0]['options']['captcha-text'];
			}
			$captchaHtml = '<div class="sgrb-captcha-wrapper">
							<div class="sgrb-captcha-notice"><span class="sgrb-captcha-notice-text"></span></div>
								<input id="sgrb-captcha-'.@$review[0]['id'].'" type="text" name="addCaptcha-'.@$review[0]['id'].'">
								<input type="hidden" class="sgrb-captcha-text" value="'.$captchaText.'">
							</div>';
		}
		// is not approved
		$sgrvNotApprovedMessage = '';
		if (@empty($sgrbEachEditableRate)) {
			$sgrvNotApprovedMessage = '';
		}
		else {
			if (!$sgRate && $currentCommentId) {
				$sgrvNotApprovedMessage = '<span class="sgrb-not-approved-message">Your comment has not been approved yet</span>';
			}
		}

		if (!$userLoggedIn) {
			$commentForm .= '<div class="sgrb-show-hide-comment-form" style="display:block"></div>';
		}
		else {
			$commentForm .= @$sgrvNotApprovedMessage;
			// if custom form selected
			if (@$review[0]['options']['sgrb-add-comment-form'] && SGRB_PRO_VERSION) {
				$formId = @$review[0]['options']['sgrb-add-comment-form'];
				$form = new Form($formId);
				if ($form) {
					$customForm = true;
					$commentForm .= $form->render(@$currentCommentId);
					$commentForm .= @$captchaHtml;
				}
			}
			else {
				$commentForm .= '<div class="sgrb-front-comment-rows">
						<span class="sgrb-comment-title">'.@$nameText.' </span>'.@$commentFieldAsterisk.'
						<span class="sgrb-each-field-notice">
							<input class="sgrb-add-fname" name="addName" type="text" value="'.@$UserComName.'" placeholder="'.@$namePlaceholderText.'">
							<i></i>
						</span>
					</div>
					<div class="sgrb-front-comment-rows">
						<span class="sgrb-comment-title">'.@$emailText.' </span>'.@$emailRequiredAsterisk.'
						<span class="sgrb-each-field-notice">
							<input class="sgrb-add-email" name="addEmail" type="email" value="'.@$UserComEmail.'" placeholder="'.@$emailPlaceholderText.'">
							<i></i>
						</span>
					</div>
					<div class="sgrb-front-comment-rows">
						<span class="sgrb-comment-title">'.@$titleText.' </span>'.@$titleRequiredAsterisk.'
						<span class="sgrb-each-field-notice">
							<input class="sgrb-add-title" name="addTitle" type="text" value="'.@$UserComTitle.'" placeholder="'.@$titlePlaceholderText.'">
							<i></i>
						</span>
					</div>
					<div class="sgrb-front-comment-rows">
						<span class="sgrb-comment-title">'.@$commentText.' </span>'.$commentFieldAsterisk.'
						<textarea class="sgrb-add-comment" name="addComment" placeholder="'.@$commentPlaceholderText.'">'.@$UserComComment.'</textarea><i></i>'.@$captchaHtml.'
					</div>';
			}

			$commentForm .= '<input name="addPostId" type="hidden" value="'.@$postId.'">
					<div class="sgrb-post-comment-button">
						<input type="hidden" class="sgrb-captchaOn" name="captchaOn" value="'.@$review[0]['options']['captcha-on'].'">
						<input type="hidden" name="currentUserCommentId" value="'.@$currentCommentId.'">
						<input type="hidden" name="customForm" value="'.$customForm.'">
						<input type="hidden" class="sgrb-thank-text" value="'.@$thankText.'">
						<input type="hidden" class="sgrb-no-rate-text" value="'.@$noRateText.'">
						<input type="hidden" class="sgrb-no-name-text" value="'.@$noNameText.'">
						<input type="hidden" class="sgrb-no-email-text" value="'.@$noEmailText.'">
						<input type="hidden" class="sgrb-no-title-text" value="'.@$noTitleText.'">
						<input type="hidden" class="sgrb-no-comment-text" value="'.@$noCommentText.'">
						<input type="hidden" class="sgrb-no-captcha-text" value="'.@$noCaptchaText.'">
						<input data-sgrb-id="'.$sgrbFakeId.'" type="button" value="'.@$postCommentText.'" onclick="SGReview.prototype.ajaxUserRate('.@$review[0]['id'].','.'0'.','.$sgrbFakeId.')" class="sgrb-user-comment-submit" style="background-color: '.$review[0]['options']['total-rate-background-color'].';color: '.$review[0]['options']['rate-text-color'].';">
					</div>';
			$commentForm .= '</div>
						</div>
					</div>';
		}

		$widgetTotalRatings = '';
		if ($isWidget) {
			$commentForm = '<input type="hidden" name="customForm" value="'.$customForm.'">';
			$ratedHtml = true;
		}
		if ($hideForm && !$isWidget) {
			$commentForm = '';
		}
		$allTerms = get_terms();
		$sgrbTags = array();
		$sgrbTags = json_decode(@$review[0]['options']['tags']);
		if (!empty($sgrbTags)) {
			$commentForm .= '<div id="sgrb-review-tags-'.@$review[0]['id'].'" class="sgrb-tags-wrapper"><strong>Tags: </strong>';
			$tagIndex = 0;
			$sgrbComma = '';
			if (count($sgrbTags)>1) {
				$sgrbComma = ', ';
			}
			foreach ($sgrbTags as $tag) {
				foreach ($allTerms as $term) {
					if ($term->name == $tag) {
						$termId = $term->term_id;
						$commentForm .= '<a href="'.get_tag_link($termId).'" rel="tag">'.$tag.'</a>'.$sgrbComma;
					}
				}
			}
			$commentForm .= '</div>';
		}
		if (($mainTemplate->getName() == 'woo_review') || $currentPostType == 'product') {
			$closeHtml = '';
		}
		$mainWrapperReviewId = '';
		if ($isWidget) {
			$mainWrapperReviewId = 'sgrb-widget-review-'.@$review[0]['id'];
		}
		else {
			$mainWrapperReviewId = 'sgrb-review-'.@$review[0]['id'];
		}
		if ($isWidget) {
			$widgetTotalRatings = '<div class="sgrb-widget-review-ratings-wrapper" style="background-color:'.esc_attr(@$review[0]['options']['total-rate-background-color']).';color: '.@$review[0]['options']['rate-text-color'].';"></div>';
		}
		else {
			$widgetTotalRatings = '';
		}
		$html .= '';
		return '<form class="sgrb-user-rate-js-form"><div id="'.$sgrbFakeId.'" data-sgrb-id="'.$sgrbFakeId.'" class="'.$sgrbWidgetWrapper.'">'.$html.'</div></div>'.$allApprovedComments.$commentForm.'</div></form>';
	}

	// create new comment and rate, calls in front
	public function ajaxUserRate()
	{
		global $sgrb;
		global $wpdb;
		$ratedFields = array();
		$proComment = array();
		$cookieValue = '';
		$title = '';
		$comment = '';
		$ratedFields['fields'] = @$_POST['field'];
		$ratedFields['rates'] = @$_POST['rate'];
		$reviewId = (int)$_POST['reviewId'];
		$salt = 'SGRB';
		if ($_POST['captchaOn']) {
			$captcha = $_POST['addCaptcha-'.$reviewId];
			if ((!$captcha) || (strtoupper($captcha.$salt) != ($_POST['captchaCode'].$salt))) {
				echo false;
				exit();
			}
		}
		$post = $_POST['addPostId'];
		// User edit his comment
		$currentUserCommentId = $_POST['currentUserCommentId'];
		////////////////////////
		$currentReview = SGRB_ReviewModel::finder()->findByPk($reviewId);
		$reviewOptions = $currentReview->getOptions();
		$options = json_decode($reviewOptions,true);

		$adminEmail = $options['notify'];
		$isRequiredTitle = $options['required-title-checkbox'];
		$isRequiredEmail = $options['required-email-checkbox'];
		$autoApprove = $options['auto-approve-checkbox'];
		if (!$autoApprove) {
			$autoApprove = 0;
		}
		$reviewTitle = $currentReview->getTitle();
		if ($_POST['customForm'] && SGRB_PRO_VERSION) {
			foreach($_POST as $key => $value) {
				if (strpos($key, 'sgrb_') === 0) {
					$proComment[$key] = $value;
				}
			}
			$proComment = json_encode($proComment);
		}
		else {
			$name = stripslashes(@$_POST['addName']);
			$mainEmail = @$_POST['addEmail'];
			if ($mainEmail) {
				$email = filter_var($mainEmail, FILTER_VALIDATE_EMAIL);
			}
			$title = stripslashes(@$_POST['addTitle']);
			$comment = stripslashes(@$_POST['addComment']);
		}

		if (count($_POST)) {
			$cookieValue = self::getClientIpAddress();
			for ($i=0;$i<count($ratedFields['fields']);$i++) {
				if (!$ratedFields['rates'][$i]) {
					$commonRate = false;
					echo $commonRate;
					return;
				}
			}
			$mainComment = new SGRB_CommentModel();
			// if user edit his review don't create new CommentModel
			if ($currentUserCommentId) {
				$mainComment = SGRB_CommentModel::finder()->findByPk($currentUserCommentId);
				$autoApprove = $mainComment->getApproved();
			}
			/////////////////////////
			$time = current_time('mysql');
			if (!$time) {
				@date_default_timezone_set(get_option('timezone_string'));
				$time = date('Y-m-d-h-m-s');
			}
			$mainComment->setCdate(sanitize_text_field($time));
			$mainComment->setReview_id(sanitize_text_field($reviewId));
			$mainComment->setPost_id(sanitize_text_field($post));
			$mainComment->setApproved($autoApprove);
			if ($_POST['customForm'] && SGRB_PRO_VERSION) {
				$mainComment->setComment($proComment);
			}
			else {
				$mainComment->setName(sanitize_text_field($name));
				$mainComment->setEmail(sanitize_text_field($email));
				$mainComment->setTitle(sanitize_text_field($title));
				$mainComment->setComment(sanitize_text_field($comment));
			}
			$commentRes = $mainComment->save();

			if ($mainComment->getId()) {
				$lastComId = $mainComment->getId();
			}
			else {
				if (!$commentRes) return false;
				$lastComId = $wpdb->insert_id;
			}
			// if admin selects to notify about new comment
			if ($adminEmail) {
				$currentUser = wp_get_current_user();
				$currentUserName = $currentUser->user_nicename;
				$subject = 'Review Builder Wordpress plugin.';
				$blogName = get_option('blogname');
				$editUrl = $sgrb->adminUrl('Comment/index').'sgrb_allComms&id='.$reviewId;
				$content = 'Hi '.ucfirst($currentUserName).'! Your '.ucfirst($reviewTitle).' review created in Wordpress,  '.$blogName.' blog, has been commented.';
				$headers = 'From: Review Builder' . "\r\n" .$content;
				$message = 'Follow this link '.$editUrl.' to edit it.';

				mail($adminEmail, $subject, $message, $headers);
			}

			$rate = 0;

			// ($ratedFields['fields']) & ($ratedFields['rates']) have equal count;
			if ($currentUserCommentId) {
				$i = 0;
				$mainRates = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $currentUserCommentId);
				foreach ($mainRates as $mainRate) {
					$mainRate->setComment_id(sanitize_text_field($currentUserCommentId));
					$mainRate->setRate(sanitize_text_field($ratedFields['rates'][$i]));
					$mainRate->setCategory_id(sanitize_text_field($ratedFields['fields'][$i]));
					$mainRate->save();
					$rate += $ratedFields['rates'][$i];
					$commonRate = $rate / count($ratedFields['rates']);
					if ($commonRate !== 10) {
						$commonRate = str_replace('0','',$commonRate);
					}
					$i++;
				}
			}
			else {
				for ($i=0;$i<count($ratedFields['fields']);$i++) {
					$mainRate = new SGRB_Comment_RatingModel();
					$mainRate->setComment_id(sanitize_text_field($lastComId));
					$mainRate->setRate(sanitize_text_field($ratedFields['rates'][$i]));
					$mainRate->setCategory_id(sanitize_text_field($ratedFields['fields'][$i]));
					$mainRate->save();
					$rate += $ratedFields['rates'][$i];
					$commonRate = $rate / count($ratedFields['rates']);
					if ($commonRate !== 10) {
						$commonRate = str_replace('0','',$commonRate);
					}
				}
			}

			// if new insert, save the rater
			$newUser = new SGRB_Rate_LogModel();
			$allRateLogs = SGRB_Rate_LogModel::finder()->findAll();
			foreach ($allRateLogs as $singleRateLog) {
				if ($singleRateLog->getReview_id() == $reviewId) {
					if ($singleRateLog->getComment_id() == $lastComId) {
						$rateLogId = $singleRateLog->getId();
						$newUser = SGRB_Rate_LogModel::finder()->findByPk($rateLogId);
					}
				}
			}
			$newUser->setReview_id(sanitize_text_field($reviewId));
			if ($post) {
				$newUser->setPost_id(sanitize_text_field($post));
			}
			$newUser->setComment_id(sanitize_text_field($lastComId));
			$newUser->setIp(sanitize_text_field($cookieValue));
			$newUser->save();

			echo $lastComId;
			exit();
		}
	}

	public function ajaxSelectTemplate ()
	{
		global $sgrb;
		$tempName = $_POST['template'];
		$mainTemplate = new Template($tempName);
		$res = $mainTemplate->adminRender();
		echo $res;
		exit();
	}

	public function ajaxLazyLoading ()
	{
		global $sgrb;
		global $wpdb;

		$commentFormId = 0;
		$commentFormOptions = array();

		$approvedComments = SGRB_CommentModel::getAllComments(1);
		$allComments = SGRB_CommentModel::getAllComments();

		$review = SGRB_ReviewModel::finder()->findByPk($_POST['review']);
		if (!$review) {
			return false;
		}
		$reviewOptions = $review->getOptions();
		$reviewOptions = json_decode($reviewOptions, true);
		if (!empty($reviewOptions)) {
			$commentFormId = $reviewOptions['sgrb-add-comment-form'];
		}
		if ($commentFormId) {
			$commentForm = SGRB_CommentFormModel::finder()->findByPk($commentFormId);
			if ($commentForm) {
				$commentFormOptions = $commentForm->getOptions();
				$commentFormOptions = json_decode($commentFormOptions, true);
			}
		}

		$comments = array();
		foreach ($approvedComments as $appComment) {
			$comment = array();
			$commentId = $appComment->getId();
			$rates = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', array($commentId));
			foreach ($rates as $rate) {
				$comment['rates'][] = $rate->getRate();
			}
			$customFormComment = $appComment->getComment();
			$customFormComment = json_decode($customFormComment, true);
			if (empty($customFormComment)) {
				$comment['title'] = esc_attr($appComment->getTitle());
				$comment['comment'] = esc_attr($appComment->getComment());
				$comment['name'] = esc_attr($appComment->getName());
				$comment['date'] = esc_attr($appComment->getCdate());
				$comment['id'] = esc_attr($appComment->getId());
				$comment['count'] = esc_attr(count($allComments));

				$comments[] = $comment;
				continue;
			}

			foreach ($commentFormOptions as $optionList) {
				$fieldName = $optionList['name'];
				if (!isset($customFormComment[$fieldName])) {
					$val = '';
					continue;
				}
				$val = $customFormComment[$fieldName];
				$allowToShow = $optionList['show'];

				if (strpos($fieldName, 'sgrb_text') !== false
					|| strpos($fieldName, 'sgrb_email') !== false
					|| strpos($fieldName, 'sgrb_number') !== false
					|| strpos($fieldName, 'sgrb_textarea') !== false) {
					$comment['show'][] = $allowToShow;
					$comment['key'][] = esc_attr($optionList['label']);
					$comment['additional']['val'][] = esc_attr($val);
				}
				if (strpos($fieldName, 'sgrb_textarea') !== false) {
					/* if long text,ellipse condition */
					if ($allowToShow) {
						$comment['additional']['isTextarea'][] = 1;
					}
				}
				if (strpos($fieldName, 'sgrb_addTitle') !== false) {
					$comment['title'] = esc_attr($val);
					if (!$allowToShow) {
						$comment['title'] = '';
					}
				}

				if (strpos($fieldName, 'sgrb_addComment') !== false) {
					$comment['comment'] = esc_attr($val);
					if (!$allowToShow) {
						$comment['comment'] = '';
					}
				}

				if (strpos($fieldName, 'sgrb_addName') !== false) {
					$comment['name'] = esc_attr($val);
					if (!$allowToShow) {
						$comment['name'] = '';
					}
				}
			}
			$comment['date'] = esc_attr($appComment->getCdate());
			$comment['id'] = esc_attr($appComment->getId());
			$comment['count'] = esc_attr(count($allComments));

			$comments[] = $comment;
		}
		die(json_encode($comments));
	}

	public function getPostReview ($post,$review)
	{
		global $wpdb;
		$sql = $wpdb->prepare("SELECT meta_value FROM ". $wpdb->prefix ."postmeta WHERE post_id = %d AND meta_key = %s",$post,$review);
		$row = $wpdb->get_row($sql);
		$id = 0;
		if($row) {
			$id =  (int)@$row->meta_value;
		}
		return $id;
	}

	public static function getClientIpAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return sanitize_text_field($ip);
	}
}