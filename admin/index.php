<div class="wrap">
	<h1>Settings<span style="font-size: 10px; padding-left: 12px;">- WP MOST POPULAR CUSTOM -</span></h1>

	<?php if(isset($_POST['reset'])): ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
		<p><strong><?php echo __( 'Deleted.', 'wpmpc' ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button></div>
		<?php
			function reset_count(){
				global $wpdb;
				$table = $wpdb->prefix.'most_popular';
				$results = $wpdb->get_results("
					TRUNCATE TABLE ".$table."
				");
				return $results;
			}
			reset_count();
		?>
	<?php endif; ?>

	<section>
		<form method="post" action="">
			<table class="form-table">
	            <tbody>
	                <tr>
	                    <th scope="row">
	                        <label for="wmp_range" style="font-size: 14px; margin: 0;"><?php echo __( 'Delete All Access Count', 'wpmpc' ); ?></label>
							<p style="color: red; font-size: 10px; margin-top: 0;"><?php echo __( "This operation can't be restored", 'wpmpc' ); ?></p>
	                    </th>
	                    <td>
	                    	<input name="reset" class="button button-primary" type="submit" value="Delete All Count Data" onClick="window.alert('<?php echo __( 'Delete All Access Count', 'wpmpc' ); ?>?')" >
							<input type="hidden" name="action" value="reset" />
	                    </td>
	                </tr>
	            </tbody>
	        </table>
		</form>
	</section>
	<section>
		<form method="post" action="options.php">
	        <?php
	            settings_fields( 'ranking-settings-group' );
				do_settings_sections( 'ranking-settings-group' );
	        ?>
	        <table class="form-table">
	            <tbody>
	                <tr>
	                    <th scope="row">
	                        <label for="wmp_range"><?php echo __( 'Range', 'wpmpc' ); ?>：</label>
	                    </th>
	                    <td>
	                    	<select id="wmp_range" name="wmp_range">
								<option value="all_time" <?php if(get_option('wmp_range')=="all_time") echo "selected=selected"; ?>>all_time</option>
								<option value="monthly" <?php if(get_option('wmp_range')=="monthly") echo "selected=selected"; ?>>monthly (1momnth)</option>
								<option value="biweekly" <?php if(get_option('wmp_range')=="biweekly") echo "selected=selected"; ?>>biweekly (2weeks)</option>
								<option value="weekly" <?php if(get_option('wmp_range')=="weekly") echo "selected=selected"; ?>>weekly (1week)</option>
								<option value="daily" <?php if(get_option('wmp_range')=="daily") echo "selected=selected"; ?>>daily (1day)</option>
							</select>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="row">
	                        <label for="wmp_loginuser"><?php echo __( 'Exclude the login user access', 'wpmpc' ); ?>：</label>
	                    </th>
	                    <td>
	                    	 <input type="checkbox" id="wmp_loginuser" name="wmp_loginuser" <?php if(get_option('wmp_loginuser')=="on") echo 'checked="checked"'; ?>>
	                    </td>
	                </tr>
					<tr>
	                    <th scope="row">
	                        <label for="wmp_split_single_page"><?php echo __( 'Exclude access to the split single page', 'wpmpc' ); ?>：</label>
	                    </th>
	                    <td>
	                    	 <input type="checkbox" id="wmp_split_single_page" name="wmp_split_single_page" <?php if(get_option('wmp_split_single_page')=="on") echo 'checked="checked"'; ?>>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
	        <?php submit_button(); ?>
	    </form>
	</section>
</div>
