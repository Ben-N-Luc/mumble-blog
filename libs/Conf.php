<?php

class Conf {

	/**
	 * DB informations (associative array with 'host', 'user', 'pwd', 'dbname' !)
	 */
	static $dbInfos = array(
		'local' => array(
			'host'   	=> 'localhost',
			'user'   	=> 'root',
			'pwd'    	=> '',
			'dbname' 	=> 'mumble-blog'
		),
		'online' => array(
			'host'   	=> 'localhost',
			'user'   	=> 'u20470',
			'pwd'    	=> 'LCIVKBILSB',
			'dbname' 	=> 'db20470'
		)
	);

	/**
	 * Debug level ('notice', 'warning', 'debug')
	 * (Everything is stored in the Logs.data file)
	 */
	static $debugLvl = 'debug';

	/**
	 * Email adress to use
	 */
	static $email = 'mumble@wtgeek.be';

	static $ticketCategories = array(
		'channel' => array(
			'text' => 'Demande de salon',
			'type' => 'info'
		),
		'tech' => array(
			'text' => 'Problème technique',
			'type' => 'warning'
		),
		'renseignement' => array(
			'text' => 'Renseignement',
			'type' => 'success'
		),
		'plainte' => array(
			'text' => 'Plainte',
			'type' => 'important'
		)
	);

	/**
	 * Contain all the navigation links
	 */
	static $navLinks = array(
		'default' => array(
			''             => 'Accueil',
			'informations' => 'Informations',
			'tutoriel'     => 'Tuto',
			'ticket'       => 'Ticket'
		),
		'public' => array(
			'left' => array(
				'connexion' => 'Connexion'
			)
		),
		'connected' => array(
			'left' => array(
				'compte'             => 'Mon compte',
				'compte/deconnexion' => 'Déconnexion'
			)
		),
		'admin' => array(
			'ticket'           => 'Tickets',
			'admin/posts-list' => 'Posts',
			'admin/tuto'       => 'Tuto',
			'admin/users-list' => 'Users',
			'admin/mumble'     => 'Le serveur',
			''                 => 'Le site',
			'left'             => array(
				'compte'             => 'Mon compte',
				'admin'              => 'Administration',
				'compte/deconnexion' => 'Déconnexion'
			)
		)
	);

	static $dateFormat = 'H\hi d-m-Y';

}
