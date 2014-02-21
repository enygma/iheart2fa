<script type="text/javascript">
$(function() {
    $('#validate-gauth').click(function(e){
        e.preventDefault();
        $.get('/verify-otp.php?code='+$('#gauth-code').val(),
        function(data) {
            if (data == 'pass') {
                var style = 'success';
                var message = 'Code validated!';
            } else if (data == 'fail') {
                var style = 'danger';
                var message = 'Invalid code!';
            }
            $('#validate-output').html(
                '<div class="alert alert-'+style+'" style="width:150px">'+message+'</div>'
            );            
        });
    });
});
</script>
<h2>Google Authenticator</h2>
<p>
    The <b>Google Authenticator</b> two-factor system works both online and offline without the need to
    send a message or reach out to an external API to send and validate the user's code.
</p>
<p>
    Google's solution uses a one-time password (OTP) that's a six digit numeric code that changes every so
    often. By default the "stray" on validating these codes is about 2-3 minutes, but this can be
    configured during the validation process.
</p>
<blockquote>
    Remember, this is meant to be a part of a <b>two-factor solution</b>. You still need to identify the user
    <b>before</b> you use this method.
</blockquote>

<h3>Generate QR Code</h3>
<p>
    First, we'll want to generate a QR code for you to scan and set up the application to give you
    the rotating code. Please enter your email address in the form below.
</p>
<form action="/gauth/generate" method="POST" role="form" class="form-horizontal">
    <div class="form-group">
        <label for="email">Email Address:</label><br/>
        <div class="col-sm-4">
            <input type="text" name="email" size="25" value="" class="form-control"/>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Generate</button>
    </div>
</form>
<p style="color:#CCCCCC">
    Don't worry, I'm not a spammer - I'm not going to sell it off or anything
</p>

<hr/>

<h3>Verify code</h3>
<p>
    Once you have the code scanned and the application providing a code, use this form to verify the code.
</p>
<span id="validate-output"></span>
<form role="form" class="form-horizontal">
    <div class="form-group">
        <label for="code">Code:</label><br/>
        <div class="col-sm-4">
            <input type="text" name="code" id="gauth-code" size="15" value="" class="form-control"/>
        </div>
        <a id="validate-gauth" class="btn btn-primary">Validate</a>
    </div>
</form>