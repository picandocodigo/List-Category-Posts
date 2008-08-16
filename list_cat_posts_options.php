<form method="post" action="options.php">
	<?php 
		wp_nonce_field('update-options');
		$limit=get_option('lcp_limit');
		if($limit=="")
			$limit=5
	?>
			<table>
				<tr>
					<td>Posts limit:
					</td>
					<td><input type="text" name="lcp_limit" value="<?php echo $limit; ?>">
					</td>
				</tr>
			</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="lcp_limit" />
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
	</p>
</form>
