# WP Most Popular Custom


### This Plugin based on [WP Most Popular](https://github.com/MattGeri/WP-Most-Popular)


#### Installation
 1. Download the cfs-loop-field-query.zip file to your computer.  
 2. Unzip the file.  
 3. Upload the cfs-loop-field-query directory to your /wp-content/plugins/ directory.  
 4. Activate the plugin through the 'Plugins' menu in WordPress.  
 You can access the some setting by going to Settings -> CFS Loop Field Query.
 5. Setting "Post Type Name", "Loop Field Name", "Date Field Name in Loop Feld".  
 That's it. The main query of your select post types will be rewritten.


#### Example: Sub Query
	<ul>
		<?php if (class_exists('WMP_system')): ?>
			<?php
				$wmpposts = wmp_get_popular( array(
					'limit'		=> 5,
					'post_type'	=> array('fair'),
					'range'		=> get_option('wmp_range')
				));
				$i = 1;
				if ( count( $wmpposts ) > 0 ): foreach ( $wmpposts as $post ): setup_postdata( $post );
			?>
				<li id="rank-<?php echo sprintf("%02d",$i); ?>">
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</li>
				<?php $i++; ?>
			<?php endforeach; endif; wp_reset_postdata(); ?>
		<?php endif; ?>
	</ul>
