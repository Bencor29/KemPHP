<?php

	namespace App\Libraries;

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\OAuth;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	use App\System\ConfigLoader;


	class MailLib {

		private $host;
		private $smtpAuth;
		private $username;
		private $password;
		private $smtpsecure;
		private $port;
		private $from;
		private $fromName;
		private $wordWrap;
		private $charSet;
		private $encoding;
		private $isHtml;

		public function __construct() {
			extract((new ConfigLoader)->load('mail'));

			$this->host = $host;
			$this->smtpAuth = $SMTPAuth;
			$this->username = $username;
			$this->password = $password;
			$this->smtpsecure = $SMTPSecure;
			$this->port = $port;
			$this->from = $from;
			$this->fromName = $fromName;
			$this->wordWrap = $wordWrap;
			$this->charSet = $charSet;
			$this->encoding = $encoding;
			$this->isHtml = $isHTML;
		}


		public function send(string $to, string $sub, string $msg, string $msg_nohtml) {
			$phpmailer = new PHPMailer;

			$phpmailer->isSMTP();
			$phpmailer->Host = $this->host;
			$phpmailer->SMTPAuth = $this->smtpAuth;
			$phpmailer->Username = $this->username;
			$phpmailer->Password = $this->password;
			$phpmailer->SMTPSecure = $this->smtpsecure;
			$phpmailer->Port = $this->port;
			$phpmailer->From = $this->from;
			$phpmailer->FromName = $this->fromName;
			$phpmailer->WordWrap = $this->wordWrap;
			$phpmailer->CharSet = $this->charSet;
			$phpmailer->Encoding = $this->encoding;
			$phpmailer->isHTML($this->isHtml);

			$phpmailer->addAddress($to);
			$phpmailer->Subject = $sub;
			$phpmailer->Body    = $msg;
			$phpmailer->AltBody = $msg_nohtml;
			return $phpmailer->send();
		}
	}

?>
