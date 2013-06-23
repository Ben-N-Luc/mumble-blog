<?php

class ticketCtrl extends Ctrl {

	public $champs = array(
		'mail',
		'pseudo',
		'subject',
		'type',
		'msg'
	);

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);

	public $allowed = 'connected';

	public function ticket() {

		// Renvoi de l'adresse mail
		$user = $this->Session->read('user');
		if(!isset($user->mail)) {
			$user->mail = false;
		}
		$this->set('mail', $user->mail);

		// test du formulaire
		if(!empty($this->Post)) {
			if(object_keys($this->Post) == $this->champs) {
				if(filter_var($this->Post->mail, FILTER_VALIDATE_EMAIL)) {
					$user = $this->Session->read('user');

					$data = array(
						'subject' => $this->Post->subject,
						'content' => $this->Post->msg,
						'type' => $this->Post->type,
						'date' => 'NOW()',
						'user_id' => $user->id,
						'mail' => $this->Post->mail
					);

					if ($tmp = $this->Ticket->add($data)) {
						$this->Session->setFlash("Votre message a bien été pris en compte !");
					} else {
						$this->Session->setFlash("Erreur lors de l'écriture en bdd !", 'error');
					}

					// envoie du mail sur l'adresse partagée
					$mail = new Mail(
						$this->Post->mail,
						'Formulaire de contact Mumble',
						'Nouveau message sur le <a href="mumble.wtgeek.be">site</a>, de la part de '.
						$this->Post->pseudo . ', sujet : ' . $this->Post->subject . ', type : ' . $this->Post->type
					);
					if($mail->send()) {
						$this->Session->setFlash('Votre message à bien été envoyé', 'success');
					} else {
						$this->Session->setFlash("Erreur lors de l'envoi du mail à un admin, il ne verra pas le ticket avant sa prochaine connexion...", 'error');
					}
				} else {
					$this->Session->setFlash('Mauvaise adresse Email', 'error');
				}
			} else {
				$this->Session->flash('Tous les champs sont requis', 'error');
			}

		}
	}

	public function view() {
		// Rejet divers
		$user = $this->Session->read('user');
		if(!isset($this->params[0]) || !is_numeric($this->params[0])) {
			if($user->rank == 'a') {
				$this->redirect(url('admin/tickets-list'));
			} else {
				$this->redirect(url('ticket/list'));
			}
		}
		if($user->rank != 'a' && $user->id != $d['tickets']['master']->user_id) {
			$this->Session->setFlash("Vous n'avez pas les droits nécessaires pour voir ce ticket");
			$this->redirect(url());
		}

		$id = $this->params[0];
		$d['id'] = $id;

		$d['tickets']['master'] = $this->Ticket->search(array('id' => $id))[0];


		$d['tickets']['answers'] = $this->Ticket->search(array(
				'master' => $id
			),
			array(
				'order' => array(
					'field' => 'date',
					'order' => 'ASC'
				)
			)
		);

		$d['tickets']['master']->date = date('d-m-Y H:i', strtotime($d['tickets']['master']->date));
		foreach ($d['tickets']['answers'] as $k => $v) {
			$d['tickets']['answers'][$k]->date = date('d-m-Y H:i', strtotime($v->date));
		}

		$this->set($d);
	}

}
