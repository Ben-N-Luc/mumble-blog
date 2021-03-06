<?php

class compteCtrl extends Ctrl {

    public $uses = array(
        'Models' => array('User', 'Ticket')
    );
    public $allowed = 'connected';

    public function compte() {
        $user = $this->Session->read('user');
        $tickets = $this->Ticket->liste(array('tickets.user_id' => $user->id, 'tickets.closed' => 0), 7);

        // Modification d'avatar
        if ($this->Request->dataFile && $this->isImg($this->Request->dataFile->avatar)) {
            $fileName = $this->getAvatarName($user->id);
            $this->delFile("/img/users/" . $fileName);
            $this->saveFile($this->Request->dataFile->avatar, "/img/users/", $user->id);
            $this->Request->reset('file');
            $this->Session->setFlash('Avatar modifié avec succès !');
        } else {
            if (isset($this->Request->dataFile->avatar) && !$this->isImg($this->Request->dataFile->avatar)) {
                $this->Form->errors['avatar'] = "Le fichier envoyé doit être une image.";
            }
        }

        $this->set('tickets', $tickets);
        $this->set('user', $user);
    }

    public function delete() {
    }

    /**
     * Édition de mot de passe et d'email
     * @todo Correction du changement de mdp
     * @todo Changement d'email
     */
    public function edit() {
        $user = $this->Session->read('user');
        $user = current($this->User->search(array(
            'id'	=> $user->id
        )));
        $to_validate = new stdClass();

        if ($this->Request->post) {
            // Changement de mot de passe
            if ($this->Request->post->action == "pass_edit") {
                unset($this->Request->post->action);
                $to_validate->password = $this->Request->post->password1;
                if ($user->password == sha1($this->Request->post->old_password)) {
                    if ($this->User->validates($to_validate)) {
                        if($this->Request->post->password1 == $this->Request->post->password2) {
                            $new_user['password'] = sha1($this->Request->post->password1);
                            $this->User->update(array('id' => $user->id), $new_user);
                            $this->Request->reset('post');
                            $this->Session->setFlash('Mot de passe modifié avec succès !', 'success');
                        } else {
                            $this->User->errors['password2'] = 'Les deux mots de passe doivent être identique';
                            $this->Session->setFlash('Erreur !', 'error');
                        }
                    } else {
                        $this->User->errors['password1'] = $this->User->errors['password'];
                        $this->Session->setFlash('Erreur !', 'error');
                    }
                } else {
                    $this->User->errors['old_password'] = 'Le mot de passe est incorrect.';
                    $this->Session->setFlash('Erreur !', 'error');
                }
            } elseif($this->Request->post->action == "mail_edit") {
                // Changement d'email
                if($this->User->validates($this->Request->post)) {
                    $new_user['mail'] = $this->Request->post->mail;
                    $this->User->update(array('id' => $user->id), $new_user);
                    $this->Request->reset('post');
                    $this->Session->setFlash('Email modifié avec succès !', 'success');

                    // mise à jour de la session
                    $user->mail = $new_user['mail'];
                    $this->Session->write('user', $user);
                }
            }
            $this->Form->errors = $this->User->errors;
        }

        $this->set('email' , $user->mail);
    }

    public function deconnexion() {
        $this->Session->write('user', array());
        $this->Session->write('auth', false);
        $this->Session->setFlash("Vous êtes déconnecté", "success");
        $this->redirect(url());
    }

}
