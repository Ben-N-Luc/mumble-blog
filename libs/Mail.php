<?php

/**
 * Description of mail
 *
 * @author bendem
 */
class mail {

	private $boundary;
	private $lineBreak;
	private $email;
	private $sujet;
	private $headers;
	private $content;
	private $sendTo;

	/**
	 * Préparation de l'envoie du mail, génération du contenu
	 * @param String $email Adresse email de réponse
	 * @param String $sujet Sujet du mail
	 * @param String $content Contenu de mail
	 * @param string $sendTo Adresse du destinataire
	 */
	function __construct($email, $sujet, $content, $sendTo = '') {
		$this->setBoundary();
		$this->setEmail($email);
		$this->setLineBreak();
		$this->setSendTo($sendTo);
		$this->setSujet($sujet);
		$this->setHeader();
		$this->setContent($content);
	}

	private function setBoundary() {
		$this->boundary = "-----=" . md5(rand());
	}

	/**
	 * On filtre les serveurs qui rencontrent des bugs
	 */
	private function setLineBreak() {
		if(preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $this->email)) {
			$this->lineBreak = "\n";
		} else {
			$this->lineBreak = "\r\n";
		}
	}

	private function setEmail($email) {
		$this->email = $email;
	}

	private function setSendTo($sendTo) {
		if(empty($sendTo)) {
			$sendTo = Conf::$email;
		}
		$this->sendTo = $sendTo;
	}

	private function setSujet($sujet) {
		$this->sujet = $sujet;
	}

	private function setHeader() {
		$header = 'From: "Mumble Blog" <' . $this->sendTo . '>' . $this->lineBreak;
		$header .= 'Reply-to: <' . $this->email . '>' . $this->lineBreak;
		$header .= "MIME-Version: 1.0" . $this->lineBreak;
		$header .= "Content-Type: multipart/alternative;" . $this->lineBreak;
		$header .= ' boundary="' . $this->boundary . '"' . $this->lineBreak;
		$this->headers = $header;
	}

	private function setContent($content) {
		//=====Création du message
		$message = $this->lineBreak . '--' . $this->boundary . $this->lineBreak;
		//=====Ajout du message au format texte
		$message .= 'Content-Type: text/plain; charset="UTF-8"' . $this->lineBreak;
		$message .= 'Content-Transfer-Encoding: 8bit' . $this->lineBreak;
		$message .= $this->lineBreak . strip_tags($content) . $this->lineBreak;
		//==========
		$message .= $this->lineBreak . "--" . $this->boundary . $this->lineBreak;
		//=====Ajout du message au format HTML
		$message .= 'Content-Type: text/html; charset="UTF-8"' . $this->lineBreak;
		$message .= 'Content-Transfer-Encoding: 8bit' . $this->lineBreak;
		$message .= $this->lineBreak . sanitize(stripcslashes($content), '<p><a><strong><h1><h2><em><ul><li><ol><table><tr><td><th><thead><tbody><tfoot>') . $this->lineBreak;
		//==========
		$message .= $this->lineBreak . '--' . $this->boundary . '--' . $this->lineBreak;
		$message .= $this->lineBreak . '--' . $this->boundary . '--' . $this->lineBreak;
		//==========
		$this->content = $message;
	}

	/**
	 * Envoie le mail préparé
	 * @return boolean Retourne true si le mail a été envoyé et false sinon
	 */
	public function send() {
		if(@mail($this->sendTo, $this->sujet, $this->content, $this->headers)) {
			return true;
		} else {
			return false;
		}
	}

}
