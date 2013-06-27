<?php

class Viewer {

	private $json;
	private $url = 'http://www.mymumble.fr/viewer/json/get-viewer.php?port=20470';

	function __construct($url = null) {
		if($url) {
			$this->url = $url;
		}
		$this->json = json_decode(file_get_contents($this->url));
		if($this->json) {
			$this->json->root->name = $this->json->name;
		}
	}

	public function get() {
		if($this->json) {
			$html = '<ul class="channels root">';

			$html .= $this->getChannels($this->json->root);

			$html .= '</ul>';

			return $html;
		} else {
			return false;
		}
	}

	private function getChannels($root) {
		$html = '<li class="channel">' . '<span class="channel-name chan' . $root->id . '">' .$root->name;
		$html .= '</span>';
		if(!empty($root->channels)) {
			$html .= $this->getSubChannels($root->channels);
		}
		if(!empty($root->users)) {
			$html .= $this->getUsers($root->users);
		}
		$html .= '</li>';

		return $html;
	}

	private function getSubChannels($chan) {
		$html = '<ul class="channels">';
		foreach($chan as $v) {
			$html .= '<li class="channel">';
			$html .= '<span class="channel-name chan' . $v->id . '">' .$v->name;
			$html .= ' <a href="' . $this->urlChannel($v->parent, $v->name) . '" title="Se connecter au salon"><span class="icon icon-share icon-white"></span></a>';
			$html .= '</span>';
			if(!empty($v->channels)) {
				$html .= '<ul class="channels">';
				foreach ($v->channels as $channel) {
					$html .= '<li class="channel">' . '<span class="channel-name chan' . $channel->id . '">' .$channel->name;
					$html .= ' <a href="' . $this->urlChannel($channel->parent, $channel->name) . '" title="Se connecter au salon"><span class="icon icon-share icon-white"></span></a>';
					$html .= '</span>';
					if(!empty($channel->channels)) {
						$html .= $this->getSubChannels($channel->channels);
					}
					if(!empty($channel->users)) {
						$html .= $this->getUsers($channel->users);
					}
					$html .= '</li>';
				}
				$html .= '</ul>';
			}
			if(!empty($v->users)) {
				$html .= $this->getUsers($v->users);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	function getInfo() {
		$infos = new stdClass();
		$infos->uptime = $this->json->x_uptime;
		$url = $this->json->x_connecturl;
		preg_match('/(?P<protocole>[a-z]+:\/\/)(?P<host>[a-z0-9\.]+):(?P<port>[0-9]+).*version=(?P<version>[a-z0-9\.\-^]+).*/', $url, $infos->url);
		$this->infos = $infos;
	}

	function getUsers($users) {
		$html = '<ul class="users">';
		foreach ($users as $user) {
			$html .= '<li class="user-name">' . $user->name;
			$html .= ($user->mute || $user->selfMute || $user->suppressed) ? ' <span class="icon-volume-off icon-white"></span>' : '';
			$html .= ($user->deaf || $user->selfDeaf) ? ' <span class="icon-headphones icon-white"></span>' : '';
			$time = secToTime($user->onlinesecs);
			$html .= ' <span class="user_uptime" title="Connecté depuis">' . $time['h'] . 'h '  . $time['min'] . "m " . $time['sec'] . 's' . '</span>';
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Récupère le salon parent
	 */
	function findParent($id, $object = null) {
		if(empty($object)) {
			$object = $this->json;
		}
		foreach ($object as $v) {
			if($v->id == $id) {
				return $v;
			} else {
				if(!empty($v->channels)) {
					return $this->findParent($id, $v->channels);
				}
			}
		}

		return false;
	}

	/**
	 * Récupère l'url pour accéder au channel
	 */
	function urlChannel($id, $name) {
		$url = $this->infos->url['protocole'] . $this->infos->url['host'] . ':' . $this->infos->url['port'] . '/' . $this->urlFinder($id);
		$url .= str_replace('+', '%20', urlencode($name)) . '/?version=' . $this->infos->url['version'];

		return $url;
	}

	/**
	 * Récupère l'adresse des sous-channels
	 */
	function urlFinder($id) {
		$parent = $this->findParent($id);
		$url = '';
		if(is_object($parent)) {
			if($parent->parent != -1) {
				$url .= $this->urlFinder($parent->parent) ;
				$url .= str_replace('+', '%20', urlencode($parent->name)) . '/';
			}
		}

		return $url;
	}

}
