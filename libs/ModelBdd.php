<?php

class Model {

	static $connection = false;
	public $primaryKey = 'id';
	public $table = false;
	public $lastRequest = false;

	public function __construct() {
		//Initialisation de variables utiles...
		if($this->table === false) {
			$this->table = strtolower(get_class($this)).'s';
		}

		// connection
		if(!empty(self::$connection)) {
			return true;
		}
		try {
			$db = new PDO('mysql:host='.Conf::$dbInfos['host'].';dbname='.Conf::$dbInfos['dbname'].';',
					Conf::$dbInfos['user'],
					Conf::$dbInfos['pwd'],
					array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
			);
			Model::$connection = $db;
		} catch(PDOException $e) {
			if(Conf::$debugLvl) {
				error($e->getMessage(), E_USER_ERROR);
				die($e->getMessage());
			} else {
				die('Connexion impossible à la base de données');
			}
		}
	}

	/**
	 * Ajoute une ligne en bdd
	 * @param array $data Tableau avec les données à ajouter en bdd
	 * @return bool false en cas d'échec, true en cas de réussite
	 */
	public function add($data) {
		$champs = array();
		$values = array();
		foreach ($data as $k => $v) {
			$champs[] = $k;
			$values[] = Model::$connection->quote($v);
		}
		$sql = 'INSERT INTO ' . $this->table . ' (' . implode(', ', $champs) . ') VALUES (' . implode(', ', $values) . ')';

		$pre = Model::$connection->prepare($sql);
		$tmp = $pre->execute();
		$this->lastRequest = $pre->queryString;
		return $tmp;
	}

	/**
	 * Cherche une chaine de caractère
	 * @param array $cond Tableau associatif contenant en clé le nom du champs et en valeur, la valeur cherchée
	 * @param array $param Tableau de paramètre ('champs', 'logical_term, 'order' et limit, 'order' peut être une chaine ou un tableau contenant 'field' et 'order')
	 * @return array Tableau vide si non trouvé ou le tableau associatif des résultats
	 * @return boolean False en cas de fichier vide
	 */
	public function search($cond, $param = array()) {
		$sql = 'SELECT ';
		if(isset($param['champs'])) {
			$sql .= implode(', ', $param['champs']);
		} else {
			$sql .= '*';
		}

		$sql .= ' FROM ' . $this->table;

		if(!empty($cond)) {
			$sql .= ' WHERE ';
			$c = array();
			foreach ($cond as $k => $v) {
				if(!is_numeric($v)) {
					$v = Model::$connection->quote($v);
				}
				$c[] = $k . '=' . $v;
			}
			$glue = (isset($param['logical_term'])) ? ' ' . $param['logical_term'] . ' ' : ' AND ';
			$sql .= implode($glue, $c);
		}

		if(isset($param['order'])) {
			if(is_array($param['order'])) {
				$sql .= " ORDER BY " . $param['order']['field'] . ' ' . strtoupper($param['order']['order']);
			} else {
				$sql .= " ORDER BY " . strtoupper($param['order']);
			}
		}

		if(isset($param['limit'])) {
			$sql .= ' LIMIT ' . $param['limit'];
		}

		$pre = Model::$connection->prepare($sql);
		$pre->execute();
		$this->lastRequest = $pre->queryString;
		return $pre->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Supprime une ligne en fonction de la condition
	 * @param array $cond Condition formatée pour $this->search
	 */
	public function del($cond) {

		// Récupération des id des champs à supprimer
		$tmp = $this->search($cond, array('champs' => array('id')));
		$toDel = array();
		$pk = $this->primaryKey;
		foreach ($tmp as $v) {
			$toDel[] = $v->$pk;
		}

		// si rien a supprimer, pas d'exécution de la requête
		if(empty($toDel)) {
			return false;
		}

		// construction de la requête
		$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $pk . '=' . Model::$connection->quote(implode(' OR ', $toDel));

		$pre = Model::$connection->prepare($sql);
		$tmp = $pre->execute();
		$this->lastRequest = $pre->queryString;
		return $tmp;
	}

	/**
	 * Met à jour les données correspondant à la condition
	 * @param array $cond Condition formatée pour $this->search
	 * @param array $data Données formatées comme pour $this->add
	 */
	public function update($cond, $data) {
		$tmp = $this->search($cond);
		$toChange = array();
		$pk = $this->primaryKey;
		foreach ($tmp as $v) {
			$toChange[] = $pk . '=' .  $v->$pk;
		}

		$changes = array();
		foreach ($data as $k => $v) {
			$changes[] = $k . '=' . Model::$connection->quote($v);
		}

		$sql = 'UPDATE ' . $this->table . ' SET ';
		/* TO DO !!!! */
		foreach ($data as $k => $v) {
			$sql .= implode(', ', $changes);
		}
		$sql .= ' WHERE ' . implode(' OR ', $toChange);


		$pre = Model::$connection->prepare($sql);
		$tmp = $pre->execute();
		$this->lastRequest = $pre->queryString;

		return $tmp;


	}

	/**
	 * Retourne la totalité des données ou false si vide
	 */
	public function getAll() {
		return $this->search(array());
	}

	/**
	 * Permet d'envoyer une requêtes sql préformatée
	 * @param string $request Requete SQL
	 * @return boolean Réussite ou non de la requête
	 */
	public function sql($request) {
		$pre = Model::$connection->prepare($sql);
		$tmp = $pre->execute();
		$this->lastRequest = $pre->queryString;

		return $tmp;
	}

}


?>