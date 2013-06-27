<?php

class ticketCtrl extends Ctrl {

	public $champs = array(
		'subject',
		'type',
		'msg'
	);

	public $uses = array(
		'Models' => array('User', 'Ticket')
	);

	public $allowed = 'connected';

	/**
	 * Liste des tickets
	 *
	 * @todo Pagination des tickets
	 * @todo Filtres
	 */
	public function ticket() {
		$user = $this->Session->read('user');

		// Lecture des paramètres
		$filtres = array();
		$user_id = false;
		foreach ($this->params as $param) {
			if(is_numeric($param)) {
				$filtres['ticket.user_id'] = $param;
				$user_id = $param;
			} elseif(in_array($param, array('opened', 'closed'))) {
				$filtres['ticket.closed'] = (int) ($param == 'closed');
			} else {
				foreach (Conf::$ticketCategories as $nom_categorie => $cat) {
					$categories[] = $nom_categorie;
				}
				if(in_array($param, $categories)) {
					$filtres['ticket.type'] = $param;
				}
			}
		}

		// Les non administrateurs accèdent uniquement à leurs tickets
		// Les admins accèdent par défaut à tous les tickets
		if($user->rank != 'a' && $user->id != $user_id) {
			$user_id = $user->id;
		}

		$d['tickets'] = $this->Ticket->liste($filtres);

		debug($this->Ticket->lastRequest);

		foreach ($d['tickets'] as $k => $v) {
			if(strlen($d['tickets'][$k]->content) > 800) {
				$d['tickets'][$k]->content = substr($v->content, 0, 800) . '...';
			}
			$d['tickets'][$k]->date = ($v->date) ? date('d-m-Y H:i', strtotime($v->date)) : 'NaN';
		}

		if($user->id == $user_id) {
			$d['user'] = $user->pseudo;
		} elseif($user_id === false) {
			$d['user'] = 'Tout le monde';
		} else {
			$user = $this->User->search(array('id' => $user_id));
			if($user) {
				$d['user'] = $user[0]->pseudo;
			} else {
				$this->Session->setFlash("L'utilisateur numéro $user_id n'existe pas !", 'error');
				$this->redirect(url('ticket'));
			}
		}

		$this->set($d);
	}

	/**
	 * Gestion des nouveaux tickets
	 */
	public function nouveau() {
		// test du formulaire
		if(!empty($this->Post)) {
			if(object_keys($this->Post) == $this->champs) {
				if(filter_var($this->Post->mail, FILTER_VALIDATE_EMAIL)) {
					$user = $this->Session->read('user');

					$data = array(
						'subject' => $this->Post->subject,
						'content' => $this->Post->msg,
						'type'    => $this->Post->type,
						'date'    => 'NOW()',
						'user_id' => $user->id,
						'mail'    => $this->Post->mail
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

	/**
	 * Vue complète d'un ticket
	 */
	public function view() {
		// Vérification des paramètres
		$user = $this->Session->read('user');
		if(!isset($this->params[0]) || !is_numeric($this->params[0])) {
			$this->redirect(url('ticket'));
		}

		$id = $this->params[0];
		$d['id'] = $id;

		$d['tickets']['master'] = $this->Ticket->search(array('id' => $id))[0];

		// Seul les admins peuvent accéder aux tickets des autres
		if($user->rank != 'a' && $user->id != $d['tickets']['master']->user_id) {
			$this->Session->setFlash("Vous n'avez pas les droits nécessaires pour voir ce ticket");
			$this->redirect(url());
		}

		if($this->Posted) {
			if(isset($this->Post->content)) {
				$r = $this->Ticket->add(array(
					'subject' => 'RE : ' . $d['tickets']['master']->subject,
					'content' => $this->Post->content,
					'date' => 'NOW()',
					'user_id' => $user->id,
					'master' => $d['tickets']['master']->id
				));
				if($r) {
					$this->Session->setFlash('Votre réponse a bien été ajoutée', 'success');
				} else {
					$this->Session->setFlash("Erreur lors de l'ajout de votre réponse", 'error');
				}
			} else {
				$this->Session->setFlash('Formulaire incorrect', 'error');
			}
		}

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

	public function close() {
		if(!isset($this->params[0]) || !is_numeric($this->params[0])) {
			$this->Session->setFlash('Numéro de ticket incorrect', 'error');
			$this->redirect(url('ticket'));
		} else {
			$ticket_id = $this->params[0];
		}

		$master = $this->Ticket->search(array('id' => $ticket_id))[0];

		$user = $this->Session->read('user');
		if($user->rank == 'a' || $master->id == $user->id) {
			if($master->closed == '1') {
				$this->Ticket->open($ticket_id);
				$this->Session->setFlash('Ticket rouvert', 'success');
			} else {
				$this->Ticket->close($ticket_id);
				$this->Session->setFlash('Ticket fermé', 'success');
			}

			$this->redirect(url('ticket/view/' . $ticket_id));
		} else {
			$this->Session->setFlash("Droits d'accès incorrects", 'error');
			$this->redirect(url('ticket'));
		}
	}

}
