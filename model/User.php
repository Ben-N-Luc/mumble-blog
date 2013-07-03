<?php

class User extends Model {
	var $validate = array(
		'pseudo'	=> array(
			'verif'	  => 'notEmpty',
			'message' => 'Le pseudo est obligatoire'
		),
		'pseudo'	=> array(
			'verif'	  => 'notEmpty',
			'message' => 'Le pseudo est obligatoire'
		),
		'password'	=> array(
			'verif'   => 'regex',
			'rule'	  => '.{6,16}',
			'message' => 'Votre mot de passe doit faire entre 6 et 16 caractères'
		),
		'mail'		=> array(
			'verif'   => 'filtre',
			'rule'	  => 'mail',
			'message' => "L'email entré est incorrect"
		),
		'rank'      => array(
			'verif'   => 'in_array',
			'rule'    => array('a', 'u'),
			'message' => 'Le rang doit être "a" ou "u"'
		)
	);
}
