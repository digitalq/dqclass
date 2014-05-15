<?php $styleurl = dirname(__FILE__)."/../style.css"; ?>
<style>
<?php include($styleurl) ; ?>
</style>
<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
	<div style="padding: 0 20px 20px;">
		<div>
			<h1><?php _e('PHP Info', 'phpInfo'); ?></h1>
			<blockquote>
<?php
ob_start();                                                                                                        
phpinfo();                                                                                                     
$info = ob_get_contents();                                                                                         
ob_end_clean();                                                                                                    
echo preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);
?>
</blockquote>
			
		</div>
	</div>
</div>
