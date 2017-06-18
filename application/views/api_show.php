<h2>Api Information</h2>
<div class="panel panel-info" id="qpage_details">
		<div class="panel-heading">API Information</div>
		<div class="panel-body">
		<p><b>Lable : </b> <?php echo $api['label'] ?></p>
		<p><b>Site Key : </b> <?php echo $api['site_key'] ?></p>
		<p><b>Secret Key : </b> <?php echo $api['secret_key'] ?></p>
		<p><b>Domains : </b> <?php echo $api['domains'] ?></p>
		<p><b>English Words : </b> <?php echo $api['en_words'] ?></p>
		<p><b>Parsian Words : </b> <?php echo $api['fa_words'] ?></p>
		</div>

</div>

<hr/>
<h2>Submit statics</h2>
<table class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
<?php echo $table?>
</table>