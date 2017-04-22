<?php
defined('ABSPATH') || die();

if (isset($_REQUEST['dismiss']) && check_admin_referer()) {
    $dismissed[$_REQUEST['dismiss']] = 1;
    update_option('thumbnails_dismissed', $dismissed, false);
    wp_redirect('?page=thumbnails%2Foptions.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer('thumbnails-save')) {
    if (isset($_POST['save'])) {
        update_option('thumbnails', stripslashes_deep($_POST['options']));
    }
}
$options = get_option('thumbnails', array());
?>

<link href="<?php echo plugins_url('thumbnails')?>/admin.css" type="text/css" rel="stylesheet">

<div class="wrap">
    <!-- Do not translate the name, please -->
    <h2>Thumbnails</h2>
    <?php if (!isset($dismissed['newsletter'])) { ?>
        <div class="notice notice-success"><p>
                If you want to be informed of important updated of this plugin, you may want to subscribe to my (rare) newsletter<br>
            <form action="http://www.satollo.net/?na=s" target="_blank" method="post">
                <input type="hidden" value="header-footer" name="nr">
                <input type="hidden" value="4" name="nl[]">
                <input type="email" name="ne" value="<?php echo esc_attr(get_option('admin_email'))?>" size="30">
                <input type="submit" value="<?php echo esc_attr_e('Subscribe', 'thumbnails')?>">
            </form>
            <a class="thumbnails-dismiss" href="<?php echo wp_nonce_url($_SERVER['REQUEST_URI'] . '&dismiss=newsletter&noheader=1') ?>">&times;</a>
            </p>   
        </div>
    <?php } ?>   

    <p>
        <?php printf(__('Please take <strong>few seconds</strong> to read the <a href="%s" target="_blank">Thumbnails official page</a>.', 'thumbnails'), 
                'http://www.satollo.net/plugins/thumbnails') ?> 
    </p>
    <p>
        Consider a small <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5PHGDGNHAYLJ8" target="_blank">donation</a> and 
        <a href="http://www.satollo.net/donations" target="_blank">discover why it is doubly important</a>.
    </p>
    
    <p>
        <?php _e('Other useful plugins', 'thumbnails') ?>:
        <a href="http://www.satollo.net/plugins/comment-plus?utm_source=thumbnails&utm_medium=link&utm_campaign=comment-plus" target="_blank">Comment Plus</a>,
        <a href="http://www.satollo.net/plugins/hyper-cache?utm_source=thumbnails&utm_medium=link&utm_campaign=hyper-cache" target="_blank">Hyper Cache</a>,
        <a href="http://www.thenewsletterplugin.com/?utm_source=thumbnails&utm_medium=link&utm_campaign=newsletter" target="_blank">Newsletter</a>,
        <a href="http://www.satollo.net/plugins/header-footer?utm_source=thumbnails&utm_medium=link&utm_campaign=header-footer" target="_blank">Header and footer</a>,
        <a href="http://www.satollo.net/plugins/include-me?utm_source=thumbnails&utm_medium=link&utm_campaign=include-me" target="_blank">Include Me</a>,
        <a href="http://www.satollo.net/plugins/ads-bbpress?utm_source=thumbnails&utm_medium=link&utm_campaign=ads-bbpress" target="_blank">Ads for bbPress</a>.
    </p>     
   
    <h3><?php _e('Configuration', 'thumbnails') ?></h3>

    <form action="" method="post">
        <?php wp_nonce_field('thumbnails-save'); ?>
        <table class="form-table">
            <tr>
                <th><?php _e('Featured image auto selection', 'thumbnails') ?></th>
                <td>
                    <label>
                        <input name="options[enable_autowire]" type="checkbox" <?php echo isset($options['enable_autowire']) ? 'checked' : ''; ?>> 
                    </label>

                </td>
            </tr>
            <tr>
                <th><?php _e('Auto feature image persistence', 'thumbnails') ?></th>
                <td>
                    <label>
                        <input name="options[enable_persistence]" type="checkbox" <?php echo isset($options['enable_persistence']) ? 'checked' : ''; ?>> 
                    </label>
                    <p class="description">
                        <?php _e('When a featured image is extracted by this plugin, make it persistent to improve performances', 'thumbnails') ?>.
                        <a href="http://www.satollo.net/plugins/thumbnails" target="_blank"><?php _e('Read more', 'thumbnails') ?></a>.
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Enable on the fly thumbnail generation', 'thumbnails') ?></th>
                <td>
                    <label>
                        <input name="options[enable_downsize]" type="checkbox" <?php echo isset($options['enable_downsize']) ? 'checked' : ''; ?>> 
                    </label>
                    <p class="description">
                        <?php _e('Cache folder:', 'thumbnails') ?> <code><?php echo WP_CONTENT_DIR ?>/cache/thumbnails</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Process even the core sizes', 'thumbnails') ?></th>
                <td>
                    <label>
                        <input name="options[enable_core]" type="checkbox" <?php echo isset($options['enable_core']) ? 'checked' : ''; ?>> 
                    </label>
                    <p class="description">
                        <a href="http://www.satollo.net/plugins/thumbnails" target="_blank"><?php _e('Read more', 'thumbnails') ?></a>.
                    </p>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" name="save" value="<?php _e('Save', 'thumbnails')?>" class="button-primary">
        </p>
    </form>

</div>





