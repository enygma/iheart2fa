<?php if ($error): ?>
	<div class="alert alert-warning"><?php echo $error; ?></div>
<?php else: ?>
<h3>Your code was sent to <?php echo $phone; ?>!</h3>
<br/>
<p>
	When you recieve the code, go back to the <a href="/sms">SMS main page</a>,
	enter your email address and code and click "Verify" to validate.
</p>
<?php endif; ?>