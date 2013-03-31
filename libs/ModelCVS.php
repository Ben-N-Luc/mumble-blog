<?php

class Model {

	/**
	 * Données du fichiers
	 */
	public $data = array();

	/**
	 * Structure du fichier CVS
	 */
	public $struct = array();

	/**
	 * Nom du model
	 */
	public $name;

	/**
	 * Chemin du fichier
	 */
	public $path;

	/**
	 * Initialise la class avec les informations correspondant au modèle passé en paramètre
	 * @param string $name Nom du modèle à instancier
	 */
	public function __construct($name) {
		$this->name = ucfirst($name);
		$this->path = DATA_DIR . DS . $this->name . '.data';
		/* Checking if the structure is set */
		if(empty($this->struct)) {
			error('The model structure is not defined, write the ' . $this->name . '::struct variable in ' . MODEL_DIR . DS . $this->name . '.php', E_USER_ERROR);
		}

		/* if the file do not exist, creating it */
		if(!file_exists($this->path)) {
			$file = fopen($this->path, 'w');
			fclose($file);
			$this->data = array();
			error('The ' . $this->path . ' file has been created', E_USER_NOTICE);
		} else {
			$this->refresh();
		}
	}

	/**
	 * Met à jour la variable $this->data à partir du fichier
	 */
	private function refresh() {
		$file = $this->path;
		$data = file($file);
		$r = array();
		foreach ($data as $k => $v) {
			$array = explode(';', $v);
			foreach ($array as $champsId => $string) {
				$r[$k][$this->struct[$champsId]] = str_replace("\n", '', $string);
			}
		}

		$this->data = $r;
	}

	/**
	 * Supprime les lignes du fichier
	 * @param array $lines Lignes à supprimer
	 */
	private function delLines(array $lines) {
		foreach ($lines as $line) {
			unset($this->data[$line]);
		}
		foreach ($this->data as $k => $v) {
			$this->data[$k] = implode(';', $v) . "\n";
		}
		$file = fopen($this->path, 'w');
		fwrite($file, implode($this->data));
		fclose($file);
		$this->refresh();
	}

	/**
	 * Cherche une chaine de caractère
	 * @param array $cond Tableau $cond['champs'] Contient le champs où effectuer la recherche,
	 * 					  $cond['string'] la chaine à chercher
	 * @param bool $returnLines Retourne les numéros de lignes à la place du résultat trouvé
	 * @return array Tableau vide si non trouvé ou le tableau associatif des résultats
	 * @return boolean False en cas de fichier vide
	 */
	public function search($cond, $returnLines = false) {
		if(!is_array($this->data) || empty($this->data)) {
			return false;
		}
		$res = array();
		$ids = array();
		if(is_array($cond)) {
			/* recherche de $cond['string'] dans la partie $cond['champs'] de la structure $struct */
			foreach ($this->data as $id => $v) {
				if(!isset($v[$cond['champs']])) {
					error('Le champs "' . $cond['champs'] . "\" n'est pas défini dans la structure du fichier " . DATA_DIR . DS . $this->name . '.data', E_USER_ERROR);
				} elseif(!empty($v)) {
					if(stripos($v[$cond['champs']], $cond['string']) !== false) {
						$res[] = $v;
						$ids[] = $id;
					}
				}
			}
		} else {
			foreach ($this->data as $id => $v) {
				if(!empty($v)) {
					foreach ($v as $champsId => $string) {
						if(stripos($string, $cond) !== false) {
							$res[$id] = $v;
							$ids[] = $id;
						}
					}
				}
			}
		}

		if($returnLines) {
			return $ids;
		} else {
			return $res;
		}
	}

	/**
	 * Ajoute une ligne au fichier
	 * @param array $data Tableau associatif ou non avec les données à écrire
	 * 		Doit faire la même taille que $this->struct
	 * @param string $data Chaine préformatée à ajouter au fichier
	 * 		Le nombre de ';' doit correspondre à la taille de $this->struct
	 * @return bool false en cas d'échec, true en cas de réussite
	 */
	public function add($data) {
		if(is_array($data)) {
			if(count($this->struct) == count($data)) {
				$d = array();
				if(isAssociative($data)) {
					foreach ($data as $k => $v) {
						$d[current(array_keys($this->struct, $k))] = $v;
					}
				} else {
					$d = $data;
				}
				sort($d);
				$s = implode(';', $d) . "\n";
				$file = fopen($this->path, 'a');
				fwrite($file, $s);
				fclose($file);
			} else {
				error('You try to save more or less information than the structure has', E_USER_ERROR);
				return false;
			}
		} else {
			if(count($this->struct) != count(explode(';', $data))) {
				error('The string you try to save has not the same size than the structure', E_USER_ERROR);
				return false;
			} else {
				$file = fopen($this->path, 'a');
				fwrite($file, $data . "\n");
				fclose($file);
			}
		}
		$this->refresh();
		return true;
	}

	/**
	 * Supprime une ligne en fonction de la condition
	 * @param array/string $cond Condition formatée pour $this->search
	 */
	public function del($cond) {
		$linesToDel = $this->search($cond, true);
		if($linesToDel === false) {
			return false;
		}
		$this->delLines($linesToDel);
	}

	/**
	 * Met à jour les données correspondant à la condition
	 * @param array/string $cond Condition formatée pour $this->search
	 * @param array/string $data Données formatées comme pour $this->add
	 */
	public function update($cond, $data) {
	}

	/**
	 * Retourne la totalité des données ou false si vide
	 */
	public function getAll() {
		if(empty($this->data)) {
			return false;
		} else {
			return $this->data;
		}
	}

}

?>