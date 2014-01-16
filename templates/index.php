<h2>Generate QR Code</h2>
<form action="/gauth/generate" method="POST">
    Email Address: <input type="text" name="email" size="25" value=""/>
    <br/>
    <input type="submit" name="submit" value="Generate"/>
</form>

<hr/>

<h2>Verify code</h2>
<form action="/gauth/verify" method="POST">
    Code: <input type="text" name="code" size="15" value=""/><br/>
    <input type="submit" name="submit" value="Verify Code"/>
</form>