<?php

global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeController('Review');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('Comment');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('Comment_Rating');
$sgrb->includeModel('Template');
$sgrb->includeModel('Category');
$sgrb->includeModel('CommentForm');
$sgrb->includeCore('Form');

class SGRB_CommentController extends SGRB_Controller
{
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
		$sgrb->includeStyle('page/styles/comment/save');
		$comment = new SGRB_CommentView();
		$createNewUrl = $sgrb->adminUrl('Comment/save');

		SGRB_AdminView::render('Comment/index', array(
			'createNewUrl' => $createNewUrl,
			'comment' => $comment
		));
	}

	public function ajaxSave()
	{
		global $wpdb;
		$proComment = array();

		$ip = SGRB_ReviewController::getClientIpAddress();

		if (count($_POST)) {
			$sgrbId = (int)$_POST['sgrb-id'];
			$sgrbComId = (int)$_POST['sgrb-com-id'];

			$title = stripslashes(@$_POST['title']);
			if ($_POST['customForm'] && SGRB_PRO_VERSION) {
				foreach($_POST as $key => $value) {
					if (strpos($key, 'sgrb_') === 0) {
						$proComment[$key] = $value;
					}
				}
				$comment = json_encode($proComment);
				$email = '';
				$name = '';
			}
			else {
				$email = @$_POST['email'];
				$comment = stripslashes(@$_POST['comment']);
				$name = stripslashes(@$_POST['name']);
			}

			$review = @$_POST['review'];
			$rates = @$_POST['rates'];
			$categories = @$_POST['categories'];
			$post = @$_POST['post'];
			$postCategory = @$_POST['post-category'];
			$addPostId = @$_POST['addPostId'];

			$isApproved = isset($_POST['isApproved']) ? (int)@$_POST['isApproved'] : 0;

			$sgrbComment = SGRB_CommentModel::finder()->findByPk($sgrbId);

			if (!$sgrbComId) {
				$sgrbComment = new SGRB_CommentModel();
			}
			else {
				$sgrbComment = SGRB_CommentModel::finder()->findByPk($sgrbComId);
			}

			$sgrbComment->setReview_id(sanitize_text_field($review));
			$sgrbComment->setCategory_id(sanitize_text_field($postCategory));
			$sgrbComment->setPost_id(sanitize_text_field($post));
			$sgrbComment->setTitle(sanitize_text_field($title));
			$sgrbComment->setEmail(sanitize_text_field($email));
			$sgrbComment->setComment(sanitize_text_field($comment));
			$sgrbComment->setName(sanitize_text_field($name));
			$time = current_time('mysql');
			if (!$time) {
				@date_default_timezone_set(get_option('timezone_string'));
				$time = date('Y-m-d-h-m-s');
			}
			$sgrbComment->setCdate(sanitize_text_field($time));
			$sgrbComment->setApproved(sanitize_text_field($isApproved));
			$sgrbCommentRes = $sgrbComment->save();

			if ($sgrbComment->getId()) {
				$lastCommentId = $sgrbComment->getId();
			}
			else {
				if (!$sgrbCommentRes) return false;
				$lastCommentId = $wpdb->insert_id;
			}

			for ($i=0;$i<count($rates);$i++) {
				if (!$sgrbComId) {
					$commentRates = new SGRB_Comment_RatingModel();
					$commentRates->setComment_id(sanitize_text_field($lastCommentId));
					$commentRates->setCategory_id(sanitize_text_field($categories[$i]));
					$commentRates->setRate(sanitize_text_field($rates[$i]));
					$commentRates->save();
				}
				else {
					$commentRates = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $lastCommentId);
					$commentRates[$i]->setComment_id(sanitize_text_field($lastCommentId));
					$commentRates[$i]->setCategory_id(sanitize_text_field($categories[$i]));
					$commentRates[$i]->setRate(sanitize_text_field($rates[$i]));
					$commentRates[$i]->save();
				}
			}

			$newUser = new SGRB_Rate_LogModel();
			$allRateLogs = SGRB_Rate_LogModel::finder()->findAll();
			foreach ($allRateLogs as $singleRateLog) {
				if ($singleRateLog->getReview_id() == $review) {
					if ($singleRateLog->getComment_id() == $lastCommentId) {
						$rateLogId = $singleRateLog->getId();
						$newUser = SGRB_Rate_LogModel::finder()->findByPk($rateLogId);
					}
				}
			}
			$newUser->setReview_id(sanitize_text_field($review));
			if ($addPostId) {
				$newUser->setPost_id(sanitize_text_field($post));
			}
			$newUser->setComment_id(sanitize_text_field($lastCommentId));
			$newUser->setIp(sanitize_text_field($ip));
			$newUser->save();
		}
		echo $lastCommentId;
		exit();
	}

	public function save()
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
		$sgrb->includeStyle('page/styles/comment/save');
		$sgrb->includeStyle('page/styles/general/sg-box-cols');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrbId = 0;
		$sgrbDataArray = array();
		$createNewUrl = $sgrb->adminUrl('Comment/save');

		$sgrbId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

		$sgrbSaveUrl = $sgrb->adminUrl('Comment/save');
		$sgrbDataArray = array();
		$customFormComment = array();
		$attributes = array();
		$ratingType = '';
		$formId = 0;

		if ($sgrbId) {
			$sgrbComment = SGRB_CommentModel::finder()->findByPk($sgrbId);

			$title = $sgrbComment->getTitle();
			$email = $sgrbComment->getEmail();
			$comment = $sgrbComment->getComment();
			$name = $sgrbComment->getName();

			$isApproved = $sgrbComment->getApproved();
			$reviewId = $sgrbComment->getReview_id();
			$postCategoryId = $sgrbComment->getCategory_id();
			$postId = $sgrbComment->getPost_id();

			if (SGRB_PRO_VERSION && !$email && !$name) {
				if ($reviewId) {
					$review = SGRB_ReviewModel::finder()->findByPk($reviewId);
					$revOptions = $review->getOptions();
					$revOptions = json_decode($revOptions, true);
					$formId = $revOptions['sgrb-add-comment-form'];
					if ($formId) {
						$commentForm = SGRB_CommentFormModel::finder()->findByPk($formId);
						$commentFormOptions = $commentForm->getOptions();
						$commentFormOptions = json_decode($commentFormOptions, true);
						$i = 0;
						$customFormComment = json_decode($comment, true);
						foreach ($commentFormOptions as $key => $formOptions) {

							$shortcode = $formOptions['code'];
							$attributes[] = $this->getAttributes($shortcode);
							$attributes[$i]['name'] = $formOptions['name'];
							$index = $attributes[$i]['name'];
							if (isset($customFormComment[$index])) {
								$attributes[$i]['value'] = $customFormComment[$index];
							}
							$i++;
						}
					}
				}

			}

			$category = new SGRB_CategoryModel();
			$sgrbReview = new SGRB_ReviewModel();
			if ($reviewId) {
				$category = SGRB_CategoryModel::finder()->findAll('review_id = %d', $reviewId);
				$ratings = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $sgrbId);
				$sgrbReview = SGRB_ReviewModel::finder()->findByPk($reviewId);
				$sgrbReviewTitle = $sgrbReview->getTitle();
				$sgrbOptions = $sgrbReview->getOptions();
				$sgrbOptions = json_decode($sgrbOptions, true);
				$ratingType = $sgrbOptions['rate-type'];
			}

			if ($ratingType == SGRB_RATE_TYPE_STAR) {
				$ratingType = 'star';
			}
			else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
				$ratingType = 'percent';
			}
			else if ($ratingType == SGRB_RATE_TYPE_POINT) {
				$ratingType = 'point';
			}

			$sgrbDataArray['review_id'] = $reviewId;
			$sgrbDataArray['review-title'] = $sgrbReviewTitle;
			$sgrbDataArray['ratingType'] = $ratingType;
			$sgrbDataArray['isApproved'] = $isApproved;
			$sgrbDataArray['title'] = $title;
			$sgrbDataArray['email'] = $email;
			$sgrbDataArray['comment'] = $comment;
			$sgrbDataArray['name'] = $name;

			// if it is post type review
			if ($postId) {
				$postIdFirst = get_the_category($postId);
				$postIdFirst = $postIdFirst[0];
				$sgrbDataArray['post-category-title'] = $postIdFirst->name;
				$sgrbDataArray['post-title'] = get_post($postId)->post_title;
				$sgrbDataArray['post-category-id'] = $postCategoryId;
				$sgrbDataArray['post-id'] = $postId;
			}

			$sgrbDataArray['category'] = $category;
			$sgrbDataArray['ratings'] = $ratings;
		}
		else {
			$sgrbComment = new SGRB_CommentModel();
			$sgrbDataArray['category'] = array();
			$sgrbDataArray['ratings'] = array();

		}

		$allReviews = SGRB_ReviewModel::finder()->findAll();

		if ($ratingType == SGRB_RATE_TYPE_STAR) {
			$ratingType = 'star';
		}
		else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
			$ratingType = 'percent';
		}
		else if ($ratingType == SGRB_RATE_TYPE_POINT) {
			$ratingType = 'point';
		}

		SGRB_AdminView::render('Comment/save', array(
			'sgrbDataArray' => $sgrbDataArray,
			'sgrbCommentId' => $sgrbId,
			'allReviews' => $allReviews,
			'sgrbSaveUrl' => $sgrbSaveUrl,
			'attributes' => $attributes,
			'formId' => $formId,
			'createNewUrl' => $createNewUrl
		));
	}

	public function ajaxDelete()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		SGRB_CommentModel::finder()->deleteByPk($id);
		SGRB_Comment_RatingModel::finder()->deleteAll('comment_id = %d', $id);
		SGRB_Rate_LogModel::finder()->deleteAll('comment_id = %d', $id);
		exit();
	}

	public function ajaxApproveComment()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		$currentComment = SGRB_CommentModel::finder()->findByPk($id);
		$isApproved = $currentComment->getApproved();
		if ($isApproved == 1) {
			$currentComment->setApproved(0);
		}
		else if ($isApproved == 0) {
			$currentComment->setApproved(1);
		}
		$currentComment->save();
		exit();
	}

	public function ajaxSelectReview()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/sgComment');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');

		$sgrbDataArray = array();
		$attributes = array();
		$id = (int)$_POST['id'];
		$review = SGRB_ReviewModel::finder()->findByPk($id);

		$categories = SGRB_CategoryModel::finder()->findAll('review_id = %d', $id);

		$sgrbOptions = $review->getOptions();
		$sgrbOptions = json_decode($sgrbOptions, true);
		$ratingType = @$sgrbOptions['rate-type'];
		if (SGRB_PRO_VERSION) {
			$commentFormId = @$sgrbOptions['sgrb-add-comment-form'];
			if ($commentFormId) {
				$commentForm = SGRB_CommentFormModel::finder()->findByPk($commentFormId);
				$commentFormOptions = $commentForm->getOptions();
				$commentFormOptions = json_decode($commentFormOptions, true);
				$j = 0;
				foreach ($commentFormOptions as $formOptions) {
					$shortcode = $formOptions['code'];
					$attributes[] = $this->getAttributes($shortcode);
					$attributes[$j]['name'] = $formOptions['name'];
					$j++;
				}
			}
		}
		$count = 0;

		$isPostReview = @$sgrbOptions['post-category'];

		if ($ratingType == SGRB_RATE_TYPE_STAR) {
			$ratingType = 'star';
			$count = 5;
		}
		else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
			$ratingType = 'percent';
			$count = 100;
		}
		else if ($ratingType == SGRB_RATE_TYPE_POINT) {
			$ratingType = 'point';
			$count = 10;
		}

		$sgrbDataArray['category'] = $categories;

		$html = '';

		$i = 0;
		$arr = array();

		if ($isPostReview) {
			$allPosts = get_posts();
			$allCategories = get_terms(array('get'=>'all'));
			foreach ($allCategories as $category) {
				$i++;
				$arr['postCategoies'][$i]['postCategoryId'] = esc_attr($category->term_id);
				$arr['postCategoies'][$i]['postCategoryTitle'] = esc_attr($category->name);
			}
			foreach ($allPosts as $singlePost) {
				$arrPost = wp_get_post_categories($singlePost->ID);
				$arrPost = $arrPost[0];
				if ($arrPost == $isPostReview) {
					$i++;
					$arr['posts'][$i]['postTitle'] = esc_attr($singlePost->post_title);
					$arr['posts'][$i]['postId'] = esc_attr($singlePost->ID);
				}
			}
		}

		foreach ($sgrbDataArray['category'] as $category) {
			$i++;
			$arr[$i]['categoryId'] = esc_attr($category->getId());
			$arr[$i]['name'] = esc_attr($category->getName());
			$arr[$i]['ratingType'] = esc_attr($ratingType);
			$arr[$i]['count'] = esc_attr($count);
		}
		if (SGRB_PRO_VERSION && !empty($attributes)) {
			$arr['fields'] = $attributes;
		}
		$html = json_encode($arr);

		echo $html;
		exit();
	}

	public function ajaxSelectPosts()
	{
		$categoryId = $_POST['categoryId'];
		$allPosts = get_posts(array('category' => $categoryId));
		$html = '';
		$i = 0;
		$arr = array();
		foreach ($allPosts as $post) {
			$i++;
			$arr[$i]['postId'] = esc_attr($post->ID);
			$arr[$i]['postTitle'] = esc_attr($post->post_title);
		}

		$html = json_encode($arr);

		echo $html;
		exit();
	}

	public function getAttributes ($shortcode)
	{
		$array = explode(' ', $shortcode);
		$options = array();

		foreach ($array as $key) {
			$key = explode('=', $key);
			if ($key[0] == 'label') {
				$options['label'] = $key[1];
			}
			if ($key[0] == 'placeholder') {
				$options['placeholder'] = $key[1];
			}
		}
		return $options;
	}
}
