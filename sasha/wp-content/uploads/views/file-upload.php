<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Sweetpotato_Knowledge
 * @since Sweetpotato Knowledge 1.0
 */
//require login to view page
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/login/?redirect_to=' . site_url(FILE_UPLOAD_PAGE));
}

get_header();
?>

<?php tie_breadcrumbs() ?>

<div class="content file-upload-content" ng-controller="parentCtrl">
	 <div class="post-listing tabs-content">
        <div class="post-inner tabs-view-custom">
				<?php require_once 'parts/choose-action.php'; ?>
				<?php require_once 'parts/create-post.php'; ?>
				<?php require_once 'parts/upload-document.php'; ?>
				<?php require_once 'parts/upload-image-video.php'; ?>
				<?php require_once 'parts/create-project.php'; ?>
				<?php require_once 'parts/create-event.php'; ?>
				<?php require_once 'parts/create-link.php'; ?>
		  </div>
    </div>
</div>

<aside class="sidebar green-title-box ">
	<div class="theiaStickySidebars ">
		<?php dynamic_sidebar('Add New');?>
    </div><!-- .theiaStickySidebar /-->
</aside>

<?php get_footer(); ?>
