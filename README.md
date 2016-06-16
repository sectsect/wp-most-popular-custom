# WP Most Popular Custom

### This Plugin based on [WP Most Popular](https://github.com/MattGeri/WP-Most-Popular)

#### Installation
 1. `cd /path-to-your/wp-content/plugins/`
 2. `git clone git@github.com:sectsect/wp-most-popular-custom.git`
 3. Activate the plugin through the 'Plugins' menu in WordPress.  
 :memo: **NOTE**: If you are a on a multi-site, you must be activated on the child-site.  
 You can access the some setting by going to '`Ranking`' Seetings Page.

#### Added Feature
 - Settings page
 - Exclude the login user access (optional)
 - Exclude access to the split single page (optional)
 - Add the Date range "biweekly (2w)"

#### Usage Example
``` php
<ul>
	<?php if (class_exists('WMP_system')): ?>
		<?php
			$wmpposts = wmp_get_popular( array(
				'limit'		=> 5,
				'post_type'	=> array('post'),
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
```
