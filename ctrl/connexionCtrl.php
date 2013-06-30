<?php

class connexionCtrl extends Ctrl {

	public $uses = array(
		'Models' => array('User')
	);

	public $champs = array(
		'connexion' => array('pseudo', 'mdp'),
		'inscription' => array('username', 'email', 'pwd', )
	);

	public function connexion() 
	{
		if ($this->Request->posted) 
		{
			if ($this->Request->post->action == "sign") 
			{
				$user = current($this->User->search(
					array('pseudo' => $this->Request->post->pseudo)
				));
				$mail = current($this->User->search(
					array('mail' => $this->Request->post->mail)				
				));

				if($user)
				{
					$this->Helper->errors['pseudo'] = 'Pseudo déjà utilisé';
				} 
				if($mail)
				{
					$this->Helper->errors['mail'] = 'Mail déjà utilisé';
				} 
				elseif(!$mail && !$user) 
				{
					if ($this->User->validates($this->Request->post)) 
					{
						if ($this->User->add($data)) 
						{
							$this->Session->setFlash('Vous êtes bien inscrit', 'success');
						} 
						else 
						{
							$this->Session->setFlash('Erreur interne, réessayez plus tard', 'error');
							$this->Log->add(array(
								'message' => "Erreur interne lors de l'inscription sur la requête " . $this->User->lastRequest,
								'token' => 'internal error'
							));
						}
						
					} 
					else 
					{
						$this->Session->setFlash('Erreur, vérifiez vos informations', 'error');
					}
					
				}
			} 
			else 
			{
				# code...
			}
		} 
	}
}
