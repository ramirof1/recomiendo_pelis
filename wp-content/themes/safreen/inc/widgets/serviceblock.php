<?php

/**************************/
/******service block widget */
/************************/



class safreen_serviceblock extends WP_Widget {

	public function __construct() {
		if(is_customize_preview()){$widgetname = __( 'Safreen - Service Block', 'safreen' ); }else{ $widgetname = __( 'Safreen - Service Block', 'safreen' ); }
		
		parent::__construct(
			'ctUp-ads-servicewidget',$widgetname,
			 array(
			'classname'   => 'ctUp-ads-servicewidgets',
			'description' => __( 'Service Block Section widget', 'safreen' ),
			'customize_selective_refresh' => true,
		) );
	}

    function widget($args, $instance) {

        extract($args);

$page_safreen = isset( $instance['page'] ) ? $instance['page'] : '';
$link_name = isset( $instance['link_name'] ) ? esc_attr($instance['link_name']) : '';
        echo $before_widget;
        ?>
 <?php

			$args = array(	'post_type' => 'page',
							'page_id' => $page_safreen,
						    'posts_per_page'=>1,
							'order'=>'ASC');

			$wp_query_safreen = new WP_Query($args);

   if($wp_query_safreen->have_posts()) {		?>
			<?php  while ($wp_query_safreen->have_posts()) { $wp_query_safreen->the_post();?>

						<div  class="bg-service-1 box-one-third light-text" >

       						 <?php the_post_thumbnail(); ?>



						 <div class="wow animated fadeUp matchhe">
							<div class="inner">

											<!-- TITLE -->
							 <h2 class="wow fadeIn"><?php the_title(); ?></h2>
											<!-- CONTENT -->
								<p>  <?php the_excerpt(); ?>  </p>

											<!-- MORE INFO -->
								   <div class="divider-single"></div>

										<a href="<?php echo esc_url(get_permalink());?>" class="hvr-sweep-to-top-border wow fadeUp"><?php  echo $link_name;?> </a>


									</div>
                                  </div>
                                </div>
		<?php } ?>
	<?php }?>

<?php
        echo $after_widget;


    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['page'] = wp_kses_post($new_instance['page']);
		$instance['link_name'] = wp_kses_post( $new_instance['link_name'] );

        return $instance;

    }

    function form($instance) {

		/* Set up some default widget settings. */
		$defaults = array(
		'page'=> 'sample-page' ,
		'link_name'=> 'Read More' ,

		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

     <p>
					<label>
						<?php _e( 'Page','safreen' ); ?>:
						<?php wp_dropdown_pages( array(  'name' => $this->get_field_name("page"), 'selected' => $instance["page"] ) ); ?>
					</label>
				</p>


        <p>
			<label for="<?php echo $this->get_field_id('link_name'); ?>"><?php _e('Link Name','safreen'); ?></label><br />
		<input type="text" name="<?php echo $this->get_field_name('link_name'); ?>" id="<?php echo $this->get_field_id('link_name'); ?>" value="<?php if( !empty($instance['link_name']) ): echo $instance['link_name']; endif; ?>" class="widefat">
	 </p>


            <?php

    }

}
