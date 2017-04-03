# CM.Flow.Mailgun

This package allows you to send E-Mails using the `EmailService` (contained in the package `CM.Flow.Utilities`), via mailgun instead of the local mail server.

## Configuration

### Settings.yaml

To be able to connect to mailgun, the following configuration is required (preferably in the global Settings.yaml).

```
CM:
  Flow:
    Mailgun:
      auth:
        mailgun-key: 'key-xxxxxxxxxx' # your mailgun api key
        domain: 'mg.example.com' # your mailgun domain
```

### Objects.yaml

On top of that, a global `Objects.yaml` is required, containing the following lines. This will tell flow to send mails using the `MailgunBackend`.

```
CM\Flow\Utilities\Email\EmailBackendInterface:
  className: CM\Flow\Mailgun\Services\MailgunBackend
  scope: singleton
```
