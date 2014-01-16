<h2>SMS-based</h2>
<p>
    There's a number of SMS-based two-factor options available out there. They range all the way from hosted services
    back to a more do-it-yourself kind of approach. For this example, I've set it up for the latter
    and am using <a href="http://twilio.com">Twilio</a> to send the messages.
</p>

<h3>Send the message</h3>
<p>
    The form below asks for your (cell) phone number and an email address that'll be used as a
    unique identifier. The idea is that it's something you have, the phone, to provide more validation
    that you're you.
</p>
<form action="/sms/send" method="POST" role="form" class="form-horizontal">
    <div class="form-group">
        <label for="phone">Phone Number:</label>
        <div class="col-sm-4">
            <input type="text" name="phone" size="25" value="" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email Address:</label>
        <div class="col-sm-4">
            <input type="text" name="email" size="25" value="" class="form-control" placeholder="foo@bar.com"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-4">
            <button type="submit" name="submit" class="btn btn-primary">Generate</button>
        </div>
    </div>
</form>

<h3>Verify the code</h3>
<p>
    Once you recieve the message, it will contain a six digit code - enter this below along with your email address
    and you'll get a "valid" or "invalid" response.
</p>
<form action="/sms/verify" method="POST" role="form" class="form-horizontal">
    <div class="form-group">
        <label for="email">Email Address:</label>
        <div class="col-sm-4">
            <input type="text" name="email" size="25" value="" class="form-control" placeholder="foo@bar.com"/>
        </div>
    </div>
    <div class="form-group">
        <label for="code">Code:</label>
        <div class="col-sm-4">
            <input type="text" name="code" size="25" value="" class="form-control" placeholder="Code from SMS"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-4">
            <button type="submit" name="submit" class="btn btn-primary">Validate</button>
        </div>
    </div>
</form>