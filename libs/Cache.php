<?php

class Cache {

	protected $_file;
	protected $_interval = 60;

	public function __construct($cache_name, $interval = false) {
		$this->_setFile($cache_name);

		if($interval === false) {
			$interval = $this->_interval;
		}
		$this->_setInterval($interval);
		$this->_setInterval($interval);
	}

	/**
	 * Défini l'interval de mise à jour du cache
	 * @param int $interval Interval en secondes
	 * @param str $interval
	 */
	protected function _setInterval($interval) {
		if(is_int($interval)) {
			$this->_interval = "+ $interval seconds";
		} else {
			$terms = array(
				'second', 'minute', 'hour', 'day', 'month', 'year'
			);
			$terms = implode('|', array_map(function($v) { return $v . 's?'; }, $terms));

			$pattern = "\+ ?[0-9]+ ($terms)";
			$pattern = "($pattern, )*$pattern";

			if(preg_match("/$pattern/", $interval)) {
				$this->_interval = $interval;
			} else {
				trigger_error('Incorrect interval setted for the ' . $this->_file, E_USER_ERROR);
				die();
			}
		}
	}

	protected function _setFile($cache_name) {
		$this->_file = CACHE_DIR . DS . strtolower($cache_name) . '.cache';
	}

	public function validate() {
		if(!is_file($this->_file)) {
			return false;
		}

		if(strtotime($this->_interval, filemtime($this->_file)) < time()) {
			return false;
		}

		return true;
	}

	public function write($content) {
		file_put_contents($this->_file, serialize($content));
	}

	public function read() {
		return unserialize(file_get_contents($this->_file));
	}
}
