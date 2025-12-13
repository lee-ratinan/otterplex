<?php

use CodeIgniter\Config\Services;

if (!function_exists('send_system_email')) {
    /**
     * @param string $to_email
     * @param string $subject
     * @param string $preheader
     * @param string $message
     * @param string $reply_to_email
     * @param string $cc
     * @param string $bcc
     * @param string $microdata
     * @return bool
     */
    function send_system_email(string $to_email, string $subject, string $preheader, string $message, string $reply_to_email = '', string $cc ='', string $bcc = '', string $microdata = ''): bool
    {
        $footer_line = lang('System.email.footer-line');
        $privacy     = lang('System.email.footer-privacy');
        $terms       = lang('System.email.footer-terms');
        $email = Services::email();
        $email->clear();
        $logo  = base_url('assets/img/logo.png');
        $html  = "<!doctype html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <meta name='x-apple-disable-message-reformatting' />
    <title>{{subject}}</title>
    <style>
    @media (prefers-color-scheme: dark) {
      body, table.table-bg { background: #0b0b0c !important; }
      .card { background: #1c1c1e !important; }
      .card .txt { color: #f5f5f7 !important; }
      .muted { color: #a1a1a6 !important; }
      .divider { background: #2c2c2e !important; }
      .btn { background: #f5f5f7 !important; }
      .btn a { color: #1c1c1e !important; }
    }
    </style>
  </head>
  <body style='margin:0;padding:0;background:#f5f5f7;'>
    <!-- Preheader (hidden preview text) -->
    <div style='display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;'>
      {$preheader}
    </div>
    <div style='display:none;'>
        {$microdata}
    </div>
    <table class='table-bg' role='presentation' cellpadding='0' cellspacing='0' border='0' width='100%' style='background:#f5f5f7;'>
      <tr>
        <td align='center' style='padding:24px 12px;'>
          <!-- Container -->
          <table class='card' role='presentation' cellpadding='0' cellspacing='0' border='0' width='600' style='width:600px;max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;'>
            <!-- Header -->
            <tr>
              <td style='padding:28px 24px 18px 24px;'>
                <img src='{$logo}' width='48' height='48' alt='OtterNova' style='display:block;border:0;outline:none;text-decoration:none;border-radius:12px;' />
                <div style='height:10px;line-height:10px;font-size:10px;'>&nbsp;</div>
                <div class='txt' style='font-family:-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; font-size:18px;line-height:22px;font-weight:600;color:#1d1d1f;'>
                  OtterNova
                </div>
              </td>
            </tr>
            <!-- Divider -->
            <tr>
              <td style='padding:0 24px;'>
                <div style='height:1px;background:#e5e5ea;line-height:1px;font-size:1px;'>&nbsp;</div>
              </td>
            </tr>
            <!-- Content -->
            <tr>
              <td style='padding:22px 24px 6px 24px;'>
                <!-- Optional title -->
                <div class='txt' style='font-family:-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; font-size:20px;line-height:26px;font-weight:600;color:#1d1d1f;margin:0 0 10px 0;'>
                  {$subject}
                </div>
                <!-- Main content (HTML-safe block) -->
                <div class='txt' style='font-family:-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; font-size:15px;line-height:22px;color:#1d1d1f;margin:0 0 16px 0;'>
                  {$message}
                </div>
                <!-- Signature -->
                <div class='txt' style='font-family:-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; font-size:15px;line-height:22px;color:#1d1d1f;margin:0 0 22px 0;'>
                  OtterNova Team
                </div>
              </td>
            </tr>
            <!-- Footer -->
            <tr>
              <td style='padding:0 24px 22px 24px;'>
                <div style='height:1px;background:#e5e5ea;line-height:1px;font-size:1px;'>&nbsp;</div>
                <div style='height:14px;line-height:14px;font-size:14px;'>&nbsp;</div>
                <div class='muted' style='font-family:-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif;font-size:12px;line-height:18px;color:#6e6e73;'>
                  {$footer_line}<br/>
                  <a class='muted' href='https://otternova.com/privacy-policy' style='color:#6e6e73;text-decoration:underline;'>{$privacy}</a>
                  &nbsp;•&nbsp;
                  <a class='muted' href='https://otternova.com/terms-and-conditions' style='color:#6e6e73;text-decoration:underline;'>{$terms}</a>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>";
        // Send the email
        $no_reply = getenv('NO_REPLY_EMAIL');
        // Source, destination
        $email->setTo($to_email);
        $email->setFrom($no_reply);
        if (!empty($reply_to_email)) {
            $email->setReplyTo($reply_to_email);
        }
        if (!empty($cc)) {
            $email->setCc($cc);
        }
        if (!empty($bcc)) {
            $email->setBcc($bcc);
        }
        // Email Content
        $email->setSubject("[OtterNova] {$subject}");
        $email->setMessage($html);
        return $email->send();
    }
}