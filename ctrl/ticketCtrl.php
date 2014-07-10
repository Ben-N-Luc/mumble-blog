<?php

class ticketCtrl extends Ctrl {

    public $champs = array(
        'subject',
        'type',
        'msg'
    );

    public $uses = array(
        'Models' => array('User', 'Ticket', 'Answer')
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
        $categories = array();
        $user_id = false;
        foreach (Conf::$ticketCategories as $nom_categorie => $cat) {
            $categories[] = $nom_categorie;
        }
        foreach ($this->Request->params as $param) {
            if(is_numeric($param)) {
                $filtres['tickets.user_id'] = $param;
                $user_id = $param;
            } elseif(in_array($param, array('opened', 'closed'))) {
                $filtres['tickets.closed'] = (int) ($param == 'closed');
            } else {
                if(in_array($param, $categories)) {
                    $filtres['tickets.type'] = $param;
                }
            }
        }

        // Les non administrateurs accèdent uniquement à leurs tickets
        // Les admins accèdent par défaut à tous les tickets
        if($user->rank != 'a' && $user->id != $user_id) {
            $filtres['tickets.user_id'] = $user->id;
            $user_id = $user->id;
        }

        $d['tickets'] = $this->Ticket->liste($filtres);

        foreach ($d['tickets'] as $k => $v) {
            if(strlen($d['tickets'][$k]->content) > 800) {
                $d['tickets'][$k]->content = substr($v->content, 0, 800) . '...';
            }

            if($v->last_answer) {
                $d['tickets'][$k]->date = date(Conf::$dateFormat, strtotime($v->last_answer));
            } else {
                $d['tickets'][$k]->date = date(Conf::$dateFormat, strtotime($v->date));
            }
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
        if($this->Request->posted) {
            if(object_keys($this->Request->post) == $this->champs) {
                $user = $this->Session->read('user');

                $data = array(
                    'subject' => $this->Request->post->subject,
                    'content' => nl2p($this->Request->post->msg),
                    'type'    => $this->Request->post->type,
                    'date'    => 'NOW()',
                    'user_id' => $user->id
                );

                if ($tmp = $this->Ticket->add($data)) {
                    $this->Session->setFlash("Votre message a bien été pris en compte !");
                } else {
                    $this->Session->setFlash("Erreur lors de l'écriture en bdd !", 'error');
                }

                // envoie du mail sur l'adresse partagée
                $mail = new Mail(
                    $user->mail,
                    'Formulaire de contact Mumble',
                    'Nouveau message sur le <a href="mumble.wtgeek.be">site</a>, de la part de '.
                    $user->pseudo . ', sujet : ' . $this->Request->post->subject . ', type : ' . $this->Request->post->type
                );
                if($mail->send()) {
                    $this->Session->setFlash('Votre message à bien été envoyé', 'success');
                } else {
                    $this->Session->setFlash("Erreur lors de l'envoi du mail à un admin, il ne verra pas le ticket avant sa prochaine connexion", 'warning');
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
        if(!isset($this->Request->params[0]) || !is_numeric($this->Request->params[0])) {
            $this->redirect(url('ticket'));
        }

        $id = $this->Request->params[0];
        $d['id'] = $id;

        $d['tickets']['master'] = current($this->Ticket->innerJoin($this->User, array('tickets.id' => $id)));
        $d['tickets']['master']->user_id = $d['tickets']['master']->id;
        $d['tickets']['master']->id = $id;

        // Seul les admins peuvent accéder aux tickets des autres
        if($user->rank != 'a' && $user->id != $d['tickets']['master']->user_id) {
            $this->Session->setFlash("Vous n'avez pas les droits nécessaires pour voir ce ticket");
            $this->redirect(url());
        }

        if($this->Request->posted) {
            if(isset($this->Request->post->content)) {
                $r = $this->Answer->add(array(
                    'content' => nl2p($this->Request->post->content),
                    'date' => 'NOW()',
                    'user_id' => $user->id,
                    'ticket_id' => $d['tickets']['master']->id
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

        $d['tickets']['answers'] = $this->Answer->search(array(
                'ticket_id' => $id
            ),
            array(
                'order' => array(
                    'field' => 'date',
                    'order' => 'ASC'
                )
            )
        );

        $d['tickets']['master']->date = date(Conf::$dateFormat, strtotime($d['tickets']['master']->date));
        foreach ($d['tickets']['answers'] as $k => $v) {
            $d['tickets']['answers'][$k]->date = date(Conf::$dateFormat, strtotime($v->date));
        }

        $this->set($d);
    }

    public function close() {
        if(!isset($this->Request->params[0]) || !is_numeric($this->Request->params[0])) {
            $this->Session->setFlash('Numéro de ticket incorrect', 'error');
            $this->redirect(url('ticket'));
        } else {
            $ticket_id = $this->Request->params[0];
        }

        $master = current($this->Ticket->search(array('id' => $ticket_id)));

        $user = $this->Session->read('user');
        if($user->rank == 'a' || $master->id == $user->id) {
            if($master->closed) {
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
