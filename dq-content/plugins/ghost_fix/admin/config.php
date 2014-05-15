<fieldset>
<?php
$ghostPluginInfo = osc_plugin_get_info('ghost_fix/index.php');

/* core database tables checks added 2011*/
$items = ModelGhost::newInstance()->ghostCheck('t_item', 'pk_i_id');
/* core table counts */
$ghostCount = count($items);
/* end core tables counts */
osc_set_preference('ghost_admin_toolbar_count', 0, 'plugin-ghost', 'INTEGER');
if( $ghostCount != 0 ) {

	if(@$items != '') {
		$i = 0;
		foreach ($items as $item) {
			$del = ModelGhost::newInstance()->ghostDelete('t_item', $item['pk_i_id'], 'pk_i_id');
			$failedDel = array();
			while ($del != true) {

				$subject = ModelGhost::newInstance()->dao->getErrorDesc();
				echo $subject;
				//find the table that is causing the problem.
				$pattern = '/' . DB_TABLE_PREFIX . 't_(.*?)`/';
				//find the primary key name in the foreign table
				$patt = '/FOREIGN KEY \(`(.*?)`\)/';
				if(!preg_match_all($pattern, $subject, $matches)) {
					$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
				}
				if(!preg_match($patt, $subject, $match)) {
					$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
				}
				echo '<pre>';
				print_r($matches);
				print_r($match);
				echo '</pre>';

				if(@array_key_exists($matches[1][0], $failedDel) || @array_key_exists($match[1], $failedDel) ) {
					echo '<pre>';
					echo 'Please PM me these errors. Thanks <a href="http://forums.osclass.org/index.php?action=profile;u=1728" target="_blank">Jay</a>.<br /> <br />';
					echo json_encode($failedDel);
					echo '</pre>';
					$i = 1;
					osc_set_preference('ghost_admin_toolbar_count', 1, 'plugin-ghost', 'INTEGER');
					break;
				}

				osc_set_preference('ghost_admin_toolbar_count', 0, 'plugin-ghost', 'INTEGER');

				if($matches[1][0] == 'item_resource') {
					//the following section removes the images if any associated with the listing.
					$itemRes = ModelGhost::newInstance()->getResource($item['pk_i_id']);

					@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '.' 		   . $itemRes['s_extension']);
					@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_original.'  . $itemRes['s_extension']);
					@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_preview.'   . $itemRes['s_extension']);
					@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_thumbnail.' . $itemRes['s_extension']);
				}

				$result = ModelGhost::newInstance()->ghostDelete('t_' . $matches[1][0], $item['pk_i_id'], $match[1]);
				if($result === false) {
					$i = 1;
					$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
				}
				$del = ModelGhost::newInstance()->ghostDelete('t_item', $item['pk_i_id'], 'pk_i_id');

			}

		}
	}
	// recalculates the category stats. Works in versions 2.4 and above.
	if(function_exists('osc_update_cat_stats') ) { osc_update_cat_stats(); }
	ghostAdCheck();

	// HACK TO DO A REDIRECT
    if($i != 1) {
        if(osc_version()<320) {
            echo '<script>location.href="' . osc_admin_render_plugin_url('ghost_fix/config.php') . '"</script>';
        } else {
            echo '<script>location.href="' . osc_route_admin_url('ghost-conf') . '"</script>';
        }
    }
}

echo '<br />';
echo __('You currently have ','ghost_fix') . @$ghostCount . _mn(' ghost ad.', ' ghost ads', $ghostCount) . '<br />';

if($ghostCount == '0') {
	echo '<h3>' . __('You are ghost free!','ghost_fix') . '</h3>';
} else {
	foreach ($items as $item) {
		echo __('Ghost ad id number ', 'ghost_fix') . $item['pk_i_id'] . '<br />';
	}
}


?>
</fieldset>
<br />
<?php echo __('Version','ghost_fix') . ': ' . $ghostPluginInfo['version'] . ' | ' . __('Author','ghost_fix') . ': ' . $ghostPluginInfo['author']; ?>

