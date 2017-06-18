<h2>User Documentation</h2>
<p>
<ol>
<li>First of all you must sign up in system . <a href="<?php echo site_url('sign/up') ?>" target="_blank">click here to signup</a> </li>
<li>After Signin to system you should register api by click on "add api"
	<ul>
		<li>You can add your persian and english words with dash(-) delimeted</li>
		<li>Words with less than 4 character and more than 6 character will not be accept </li>
		<li>Notice : you dont need to add <b>localhost</b> to domains list</li>
	</ul>
</li>
<li>Next add this javascript to your client page -- <b>Notice : You must include jquery file before load this file</b><br/>
<code>
<?php echo htmlentities('<script type="text/javascript" src="'. site_url('api/captcha/js') .'"></script>') ?>
</code>
</li>
<li>Next you should add this code to your form <br/>
<code>
<?php echo htmlentities('<div data-sitekey="{site_key}" data-lang="fa" data-method="self" class="cp-div"></div>') ?>
</code>
	<ul>
		<li>you get site_key from your panel its a string like this = "add318d55c3eb8a03ee0149f719b4abb"</li>
		<li>data-lang can be "fa" or "en" for farsi or english captcha</li>
		<li>data-method can be "self" (captcha word select from you api word list if exist) or "random" (random word)</li>
	</ul>
</li>
<li>In your server side code when form submited you will get 2 paramter
	<ul>
	<li><code>$_POST['cp_id']</code> : its captcha id </li>
	<li><code>$_POST['cp_response']</code> : its user response</li>
	</ul>
</li>
<li>And at the end you should send user data and your secret_key to this url to get answer<br/>
<code>
<?php echo htmlentities(site_url('api/captcha/siteverify') . '/?secret={secret_key}&response={cp-response}&id={cp-id}') ?>
</code>
</li>
<li>If answer output was <b>yes</b> that means user response was correct otherwise it was incorrect</li>
<li>You can see number of request , correct and incorrect response by select on your api in api list</li>
</ol>


</p>
