<?php

class User extends Model {
	var $validate = array(
		'pseudo'	=> array(
			'rule'		=> 'notEmpty',
			'message'	=> 'Le pseudo est obligatoire'
		),
		'pseudo'	=> array(
			'rule'		=> 'notEmpty',
			'message'	=> 'Le pseudo est obligatoire'
		),
		'password'	=> array(
			'verif' 	=> 'regex',
			'rule'		=> '.{6,16}',
			'message'	=> 'Votre mot de passe doit faire entre 6 et 16 caractères'
		),
		'mail'		=> array(
			'verif' 	=> 'filtre',
			'rule'		=> 'mail',
			'message'	=> "L'email entré est incorrect"
		)
	);
}
