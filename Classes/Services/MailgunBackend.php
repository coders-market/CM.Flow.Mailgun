<?php

namespace CM\Flow\Mailgun\Services;

use CM\Flow\Utilities\Email\EmailBackendInterface;
use Mailgun\Mailgun;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class MailgunBackend implements EmailBackendInterface {
    /**
     * @Flow\InjectConfiguration(path="auth", package="CM.Flow.Mailgun")
     * @var array
     */
    protected $configuration;


    /**
     * This function sends emails with the given parameter through Mailgun Service via API
     *
     * @param array $from          email sender
     * @param array $to            email receiver
     * @param string $subject       subject of the email
     * @param string $textBody      body message as plain text
     * @param string $htmlBody      body message as html
     * @param array $attachments    E-Mail attachments
     * @param array $tags           Up to three tags with max 128chars each to categorize E-Mails
     * @param array $cc
     * @param array $bcc
     * @return bool
     * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
     */
    public function send($from, $to, $subject, $textBody = null, $htmlBody = null, $attachments = array(), $tags = null, $cc = null, $bcc = null){
        # First, instantiate the SDK with your API credentials and define your domain.
        $mg = new Mailgun($this->configuration['mailgun-key']);
        $domain = $this->configuration['domain'];

        $msg = array(
            'from'      => $this->createEmailString($from),
            'to'        => $this->createEmailString($to),
            'subject'   => $subject,
            'text'      => $textBody,
            'html'      => $htmlBody,
            'tags'      => $tags
        );
        if($cc != null) {
            $msg['cc'] = $this->createEmailString($cc);
        }
        if($bcc != null) {
            $msg['bcc'] = $this->createEmailString($bcc);
        }

        $files = array(
            'attachment' => $attachments
        );

        # Now, compose and send your message.
        $response = $mg->sendMessage($domain, $msg, $files);

        return $response != null;
    }

    /**
     * @param string|array $array
     * @return string
     * @throws \Exception
     */
    private function createEmailString($array) {
        if(is_string($array)) {
            return $array;
        }

        if(!is_array($array)) {
            throw new \Exception("Couldn't create email string: illegal format");
        }

        $parts = array();
        foreach($array as $key => $value) {
            if(is_numeric($key)) {
                $parts[] = $value;
            } else {
                $parts[] = $value . ' <' . $key . '>';
            }
        }
        return implode(',',$parts);
    }
}