<?php

class adminCtrl extends Ctrl {

    public $uses = array(
        'Models' => array('User', 'Ticket', 'Post')
    );
    public $allowed = 'admin';

    public function admin() {
        $d['tickets'] = $this->Ticket->liste();

        $d['users'] = $this->User->search(array(), array(
            'limit' => 6,
            'order' => array(
                'field' => 'id',
                'order' => 'desc'
            )
        ));

        foreach ($d['tickets'] as $k => $v) {
            if(strlen($d['tickets'][$k]->content) > 400) {
                $d['tickets'][$k]->content = substr($v->content, 0, 400) . '...';
            }
            $d['tickets'][$k]->last_answer = date(Conf::$dateFormat, strtotime($v->last_answer));
        }

        $d['rank_translation'] = array(
            'a' => 'Administrateur',
            'u' => 'Utilisateur',
            'b' => 'Bloqué'
        );

        $this->set($d);
    }

    public function posts_list() {
        $d['posts'] = $this->Post->search(array(), array(
            'order' => array(
                'order' => 'DESC',
                'field' => 'date'
            )
        ));

        $this->set($d);
    }

    public function posts_edit() {
    }

    public function posts_delete() {
    }

    public function tuto() {
    }

    public function users_list() {
    }

    public function users_edit() {
        if(!isset($this->Request->params[0]) || !is_numeric($this->Request->params[0])) {
            $this->redirect(url('admin/users-list'));
        }

        $id = $this->Request->params[0];

        if($this->Request->posted) {
            if($this->User->validates($this->Request->post)) {
                $new_user['rank'] = $this->Request->post->rank;
                $new_user['mail'] = $this->Request->post->mail;
                $this->User->update(array('id' => $id), $new_user);
                $this->Session->setFlash('Utilisateur mis à jour', 'success');
                $this->Request->reset('post');
            } else {
                $this->Session->setFlash('Erreur dans le formulaire', 'error');
                $this->Form->errors = $this->User->errors;
            }
        }

        $d['user'] = current($this->User->search(array('id' => $id)));
        $d['ranks'] = array(
            'u' => 'Utilisateur',
            'a' => 'Administrateur'
        );

        $this->set($d);
    }

    public function mumble() {
    }

}
