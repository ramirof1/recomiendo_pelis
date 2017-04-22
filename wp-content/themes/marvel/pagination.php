<!-- pagination -->
		<?php
			if(function_exists('wp_pagenavi')) :
				
			else :
		?>
			<div class="safreen_nav">
			<?php	// Previous/next page navigation.
			the_posts_pagination(  array('prev_text' => '&laquo;', 'next_text' => '&raquo;') );?> 

			</div>
		<?php endif; ?>      
<!-- /pagination -->

