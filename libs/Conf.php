<?php

class Conf {

	/**
	 * Select if you wanna use database or cvs to store data
	 */
	static $db = true;

	/**
	 * DB informations (associative array with 'host', 'user', 'pwd', 'dbname' !)
	 */
	static $dbInfos = array(
		'host'   	=> 'localhost',
		'user'   	=> 'root',
		'pwd'    	=> '',
		'dbname' 	=> 'mumble-blog'
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
			'admin'            => 'Accueil',
			'ticket/liste'     => 'Tickets',
			'admin/posts-list' => 'Posts',
			'admin/tuto'       => 'Tuto',
			'admin/users-list' => 'Users',
			'admin/mumble'     => 'Le serveur',
			''                 => 'Le site',
			'left'    => array(
				'compte'             => 'Mon compte',
				'admin'              => 'Administration',
				'compte/deconnexion' => 'Déconnexion'
			)
		)
	);

}
