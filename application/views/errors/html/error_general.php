<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.error_header {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

#container {
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
<div id="container" class="container">
	<h1 class="error_header"><?php echo $heading; ?></h1>
	<?php echo $message; ?>
</div>