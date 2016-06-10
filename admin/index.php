<div class="wrap">
	<h1>Settings<span style="font-size: 10px; padding-left: 12px;">- WP MOST POPULAR CUSTOM -</span></h1>

	<?php if(isset($_POST['reset'])): ?>
		<span style="border: 2px solid #5bd535; border-radius: 5px; color: #888; padding: 3px 10px;">リセットしました</span>
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
	<?php elseif(isset($_GET['settings-updated'])): ?>
		<span style="border: 2px solid #5bd535; border-radius: 5px; color: #888; padding: 3px 10px;">変更を保存しました</span>
	<?php endif; ?>

	<section>
		<form method="post" action="">
			<table class="form-table">
	            <tbody>
	                <tr>
	                    <th scope="row">
	                        <label for="wmp_range" style="font-size: 14px; margin: 0;">Delete All Access Count</label>
							<p style="color: red; font-size: 10px; margin-top: 0;">全アクセスデータを削除します<br />※この操作はやり直しできません。</p>
	                    </th>
	                    <td>
	                    	<input name="reset" class="button button-primary" type="submit" value="Reset All Count Data" onClick="window.alert('リセットしますか？')" >
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
	                        <label for="wmp_range">Date Range：</label>
	                    </th>
	                    <td>
	                    	<select id="wmp_range" name="wmp_range">
								<option value="all_time" <?php if(get_option('wmp_range')=="all_time") echo "selected=selected"; ?>>全期間 (all_time)</option>
								<option value="monthly" <?php if(get_option('wmp_range')=="monthly") echo "selected=selected"; ?>>1ヶ月 (monthly)</option>
								<option value="biweekly" <?php if(get_option('wmp_range')=="biweekly") echo "selected=selected"; ?>>2週間 (biweekly)</option>
								<option value="weekly" <?php if(get_option('wmp_range')=="weekly") echo "selected=selected"; ?>>1週間 (weekly)</option>
								<option value="daily" <?php if(get_option('wmp_range')=="daily") echo "selected=selected"; ?>>1日 (daily)</option>
							</select>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="row">
	                        <label for="wmp_loginuser">ログインユーザーのアクセスを除外：</label>
	                    </th>
	                    <td>
	                    	 <input type="checkbox" id="wmp_loginuser" name="wmp_loginuser" <?php if(get_option('wmp_loginuser')=="on") echo 'checked="checked"'; ?>>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
	        <?php submit_button(); ?>
	    </form>
	</section>
</div>
