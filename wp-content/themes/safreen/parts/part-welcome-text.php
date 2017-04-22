<?php

				$safreen_welcome_section =  esc_html(get_theme_mod ('safreen_welcome',esc_attr__('Home Page welcome section','safreen')));
				$safreen_welcome_section = html_entity_decode(get_theme_mod ('safreen_welcome',esc_attr__('Home Page welcome section','safreen')));
				
if( !empty($safreen_welcome_section) ):
echo  '<div id="callout">';
				
echo  '<div class="row">';

echo $safreen_welcome_section;

					echo '</div>';
					echo '</div>';

				endif;
					?>