iheart2fa
================

This project was created for a talk given at the 2014 ConFoo conference around two-factor authentication.
It provides examples of two different technologies you could use:

- Google Authenticator
- A custom implementation using [Twilio](http://twilio.com)

## Setup

1. Clone the repo and run Composer
2. Chmod `_log` to be writeable by the web server user
3. Configure your Apache install to set some environment variables:

```
SetEnv TWILIO_SID "twilio-sid-here"
SetEnv TWILIO_TOKEN "twilio-token-here"
SetEnv TWILIO_NUMBER "twilio-phone-number-here"
SetEnv GAUTH_CODE "google-auth-code-here"
```

For more information on two-factor auth and some tutorials about using it in PHP, check out [Websec.io's series on 2FA](http://websec.io/tagged/twofactor).
