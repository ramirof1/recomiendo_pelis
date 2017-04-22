<?php
/**
 * Getting started template
 */

$customizer_url = admin_url() . 'customize.php' ;
?>

<div class="backend_wrapper">
    	<div class="back_header">
            <div class="center">
            	<h3><a><?php _e('Safreen','safreen'); ?></a><span><?php $the_theme = wp_get_theme(); echo $the_theme->get('Version');?></span></h3>
               
           </div> 
       </div>
         <div id="verticalTab">
<ul class="resp-tabs-list">
<li class="btn btn-4 btn-4c icon-arrow-right"><?php echo esc_attr__('Wellcome','safreen');?></li>
<li class="btn btn-4 btn-4c icon-arrow-right"><?php echo esc_attr__('Front page settings','safreen');?></li>
<li class="btn btn-4 btn-4c icon-arrow-right"><?php echo esc_attr__('setup static image','safreen');?></li>
<li class="btn btn-4 btn-4c icon-arrow-right" style="color:#B70000" ><?php echo esc_attr__('How to make home page like demo !!','safreen');?></li>
<li class="btn btn-4 btn-4c icon-arrow-right"><?php echo esc_attr__('Support','safreen');?></li>

</ul>
<div class="resp-tabs-container">
<div>
<p> <div class="blocks_wrap">
        	<div class="center">
            
                <!--BLOCK 1-->
                <div class="block">
                <i class="fa fa-cogs fa-4x" aria-hidden="true"></i>
                    <p><?php _e('Customize your website live with our improved customizer, which cuts down the website building time in half.','safreen'); ?></p>
                    <a href="<?php echo esc_url(admin_url('/customize.php')); ?>" target="_blank" ><?php _e('Customize','safreen'); ?></a>
                </div>
                <!--BLOCK 2-->
                <div class="block">
                <i class="fa fa-book fa-4x" aria-hidden="true"></i>
                    <p><?php _e('safreen is extensively documented. You will find useful information about the theme ranging from introductions to advanced features.','safreen'); ?></p>
                    <a href="<?php echo esc_url('http://safreen-docs.imonthemes.com/');?>" target="_blank"  ><?php _e('Documentation','safreen'); ?></a>
                </div>
                 <!--BLOCK 1-->
                <div class="block">
                 <i class="fa fa-shopping-cart fa-4x" aria-hidden="true"></i>
                    <p><?php _e('Upgrade to Pro for Unlock all Features','safreen'); ?></p>
                    <a href="<?php echo esc_url('http://www.imonthemes.com/safreen-pro/');?>" target="_blank" ><?php _e('Upgrade to Pro','safreen'); ?></a>
                </div>

                
            </div></div></p>
</div>
<div>
<p>
<h2 style="text-align: left;"><?php _e('Want to make front page like ','safreen'); ?><a href="<?php echo esc_url( 'http://safreen.imonthemes.com/'); ?>" target="_blank"><?php _e('DEMO !!','safreen'); ?></a></h2>
<h3><?php _e('Front page settings :','safreen'); ?></h3>
<ol>
 	<li><?php _e('If you are planning to create a site for your business(like demo), you will probably want to have a static front page set. ','safreen'); ?></li>
 	<li><?php _e('Create a new page, name it however you want and assign the "Business" page template to it;','safreen'); ?></li>
    <li><?php _e('Create another page that will hold you blog posts. Name it however you want but donst assign a page template to it;','safreen'); ?></li>
 	
 	<li><?php _e('Go to Customize > Static Front Page and select A Static Page from the radio;','safreen'); ?> </li>
    <li><?php _e('Set your front page and your blog page from the Front Page and Posts Page dropdowns','safreen'); ?> </li>
</ol>
<?php _e('Thats all !!','safreen'); ?>
<ol>
 	<li><?php _e('Setup Front page','safreen'); ?> </li>
</ol>
<img class="size-medium wp-image-198 alignleft" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/front1.jpg');?>" alt="1" width="600" height="300" />

<img class="aligncenter wp-image-200 size-large" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/front2.jpg');?>" alt="3" width="500" height="400" style="margin-left:5%; margin-top:2%;" />
</p>
</div>

<div>
<p><h3><?php _e('Set Up setup static image :','safreen'); ?></h3>
<ol>
 	<li><?php _e('go to post =&gt; add new =&gt; add your post title and description =&gt; Publish','safreen'); ?></li>
 	
 	<li><?php _e('Upload static slider image','safreen'); ?> </li>
</ol>
<?php _e('Thats all !!','safreen'); ?>
<ol>
 	<li><?php _e('Setup Post for','safreen'); ?> <span class="description customize-control-description"><?php _e('static-image','safreen'); ?></span></li>
</ol>
<img class="size-medium wp-image-198 alignleft" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/1.png');?>" alt="1" width="84" height="300" />
<p style="text-align: center;"><img class="alignleft wp-image-199 size-large" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/2.png');?>" alt="2" width="630" height="338" />2. <?php _e('Setup static-image from customize','safreen'); ?></p>
<img class="aligncenter wp-image-200 size-large" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/3.png');?>" alt="3" width="500" height="400" style="margin-left:5%;" />
</p>
</div>
<div>
<p>
<h2 style="text-align: left;"><?php _e('Want to make front page like ','safreen'); ?><a href="<?php echo esc_url( 'http://safreen.imonthemes.com/'); ?>" target="_blank"><?php _e('DEMO !!','safreen'); ?></a></h2>
<h4 style="text-align: left;"><?php _e('Install recommended plugin : Safreen widgets','safreen'); ?></h4>
<h4 style="text-align: left;"><?php _e('How to add service Block ?','safreen'); ?></h3>

<ol style="text-align: left;">
 	<li>
<h3><strong><?php _e('Service Block','safreen'); ?></strong></h3>
</li>
</ol>
<ul>
 	<li style="text-align: left;"><?php _e('Create new 3 pages from pages tab  => add title => content => featured image => published','safreen'); ?></li>
 	<li style="text-align: left;"><?php _e('Go to customize  =&gt; theme option =&gt; service block =&gt; Add a widgets =&gt; select','safreen'); ?> <strong><?php _e('Safreen-service block','safreen'); ?> </strong><?php _e('(like that add 3 service block )','safreen'); ?></li>
 	<li style="text-align: left;"><?php _e('now from service block widgets select your page for service block ','safreen'); ?></li>
</ul>
<img class="alignnone wp-image-16" src="<?php echo  esc_url(get_template_directory_uri().'/inc/admin/img/service.jpg');?>" alt="service" width="824" height="500" />
<ul>
 	<li>
<h3><strong><?php _e('About us , Our team , Clients, welcome section','safreen'); ?></strong></h3>
</li>
</ul>
<?php _e('Those section you can easily add from customize =&gt; theme option','safreen'); ?>

</br><?php _e('or','safreen'); ?></br>

<?php _e('You can also add from widgets (appear under appearance tab )','safreen'); ?>
<ul>
 	<li>
<h3><strong><?php _e('Menu','safreen'); ?></strong></h3>
</li>
</ul>
<?php _e('For show menu in mobile and any device you need to set up menu','safreen'); ?>
<ul>
 	<li>
<h3><strong><?php _e('How to setup menu ?','safreen'); ?></strong></h3>
</li>
</ul>
<?php _e('For tutorial of menu check this link :','safreen'); ?> <a href="<?php echo  esc_url('https://codex.wordpress.org/WordPress_Menu_User_Guide');?>" target="_blank"><?php _e('Menu setup','safreen'); ?></a>

</p>
</div>
<div>
<p> 
<a href="<?php echo  esc_url('https://wordpress.org/support/theme/safreen');?>" target="_blank" class="free_support"><button class="btn btn-2 btn-2e"><?php _e('Free Support','safreen'); ?></button></a>
<a href="<?php echo  esc_url('http://www.imonthemes.com/imon-themes-support-forums/');?>" target="_blank" class="free_support"><button class="btn btn-2 btn-2f"><?php _e('Pro Support','safreen'); ?></button></a>
</p>
</div>
</div>
</div>
<br />
<div style="height: 30px; clear: both"></div>
</div>
        
        
    </div>