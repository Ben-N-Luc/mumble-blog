<?php

class AppModel {

	static $connection = false;
	public $primaryKey = 'id';
	public $table = false;
	public $lastRequest = false;
	protected $_sqlFunctions = array(
		'now'
	);

	public function __construct() {
		$this->table = strtolower(get_class($this)).'s';

		// Formatages des noms de fonctions SQL
		foreach ($this->_sqlFunctions as $k => $v) {
			$this->_sqlFunctions[$k] = strtoupper($v) . '()';
		}

		// Connexion à la bdd
		if(!self::$connection) {
			try {
				$dbInfos = (ONLINE) ? Conf::$dbInfos['online'] : Conf::$dbInfos['local'];
				$db = new PDO('mysql:host='.$dbInfos['host'].';dbname='.$dbInfos['dbname'].';',
						$dbInfos['user'],
						$dbInfos['pwd'],
						array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
				);
				self::$connection = $db;
			} catch(PDOException $e) {
				if(Conf::$debugLvl) {
					error($e->getMessage(), E_USER_ERROR);
					die($e->getMessage());
				} else {
					die('Connexion impossible à la base de données');
				}
			}
		}
	}

	/**
	 * Échappe une valeur grâce à la connexion ouverte
	 * @param  string $value Valeur à échapper
	 * @return string Valeur échappée
	 */
	protected function _quote($value) {
		// Les fonctions SQL ne doivent pas être échapées
		if(in_array($value, $this->_sqlFunctions)) {
			return $value;
		}

		return self::$connection->quote($value);
	}

	/**
	 * Prépare une requête PDO
	 * @param  string $sql Requête SQL
	 * @return Object Objet PDO
	 */
	protected function _prepare($sql) {
		return self::$connection->prepare($sql);
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
			$values[] = $this->_quote($v);
		}
		$sql = 'INSERT INTO ' . $this->table . ' (' . implode(', ', $champs) . ') VALUES (' . implode(', ', $values) . ')';

		$pre = $this->_prepare($sql);
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
			if(is_array($cond)) {
				$c = array();
				foreach ($cond as $k => $v) {
					// La clé ne représente pas un champs
					// $v contient la condition !
					if(is_int($k)) {
						$c[] = $v;
					} else {
						if(!is_numeric($v)) {
							$v = $this->_quote($v);
						}
						$c[] = $k . '=' . $v;
					}
				}
				$glue = (isset($param['logical_term'])) ? ' ' . $param['logical_term'] . ' ' : ' AND ';
				$sql .= implode($glue, $c);
			} else {
				$sql .= $cond;
			}
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

		$pre = $this->_prepare($sql);
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
		$sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $pk . '=' . $this->_quote(implode(' OR ', $toDel));

		$pre = $this->_prepare($sql);
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
			$changes[] = $k . '=' . $this->_quote($v);
		}

		$sql = 'UPDATE ' . $this->table . ' SET ';
		$sql .= implode(', ', $changes);
		$sql .= ' WHERE ' . implode(' OR ', $toChange);

		$pre = $this->_prepare($sql);
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
	 * @param string $sql Requête SQL
	 */
	public function sql($sql, $data = array()) {
		$pre = $this->_prepare($sql);
		$this->lastRequest = $pre->queryString;
		$pre->execute($data);

		return $pre;
	}

}
