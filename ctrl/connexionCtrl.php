<?php

class connexionCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User')
	);

	public $champs = array(
		'connexion' => array('pseudo', 'mdp'),
		'inscription' => array('username', 'email', 'pwd', )
	);

	public function connexion() {

		// Post
		if(!empty($this->Post)) {
			// Connexion
			if(object_keys($this->Post) == $this->champs['connexion']) {
				$user = current($this->User->search(
					array('pseudo' => $this->Post->pseudo)
				));
				if($user) {
					if(sha1($this->Post->mdp) == $user->password) {
						unset($user->password);
						$this->Session->auth($user);
						$this->Session->setFlash('Vous êtes bien connecté', 'success');
						$this->redirect(url());
					} else {
						$this->Session->setFlash('Mot de passe incorrect', 'error');
					}
				} else {
					$this->Session->setFlash('Pseudo non reconnu', 'error');
				}

			// Inscription
			} elseif(object_keys($this->Post) == $this->champs['inscription']) {

				// déjà un utilisateurs ?
				$user = current($this->User->search(
					array('pseudo' => $this->Post->username, 'mail' => $this->Post->email),
					array('logical_term' => 'OR')
				));
				if($user) {
					$this->Session->setFlash('Pseudo ou adresse mail déjà pris', 'warning');
				} else {
					// email valide ?
					if (filter_var($this->Post->email, FILTER_VALIDATE_EMAIL)) {

						// longueur mdp
						if (strlen($this->Post->pwd) < 6) {
							$this->Session->setFlash('Votre mot de passe doit faire au moins 6 caractères');
						} else {
							// écriture en bdd
							$data = array(
								'pseudo' => $this->Post->username,
								'password' => sha1($this->Post->pwd),
								'mail' => $this->Post->email
							);
							if($this->User->add($data)) {
								$this->Session->setFlash('Vous êtes bien inscrit', "success");
							} else {
								$this->Session->setFlash('Erreur interne, réessayez plus tard...', 'error');
								$this->Log->add(array(
									'message' => "Erreur interne lors de l'inscription sur la requête " . $this->User->lastRequest,
									'token' => 'internal error'
								));
							}
						}
					} else {
						$this->Session->setFlash('Adresse Email invalide', 'warning');
					}
				}

			}
		}

	}
}
