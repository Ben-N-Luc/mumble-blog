<?php

class Form {

    public $errors;
    public $specAttr = array(
        'label', 'type', 'file_type'
    );
    protected $_controller;
    protected $_alignment = 'left';
    protected $_usedId = array();

    public function __construct(&$controller) {
        $this->_controller = $controller;
    }

    protected function _generateId($name) {
        $i = 0;
        $id = "form_$name";
        while(in_array($id, $this->_usedId)) {
            $id = "form_$name_$i";
            $i++;
        }
        $this->_usedId[] = $id;

        return $id;
    }

    protected function _formatOptions(array $options) {
        $html = '';
        foreach ($options as $k => $v) {
            if(!in_array($k, $this->specAttr)) {
                if(is_int($k)) {
                    $html .= " $v";
                } else {
                    $html .= sprintf(' %s="%s"', $k, $v);
                }
            }
        }

        return $html;
    }

    /**
     * Ouvre un formulaire
     * @param $action Action du formulaire
     * @param $method Méthode d'envoi de formulaire
     * @param $options Tableau d'options à appliquer au formulaire (class, ...)
     */
    public function start($action = null, $options = array()) {
        if($action === null) {
            $action = url(REQUEST_URI);
        }
        if(!isset($options['method'])) {
            $options['method'] = 'post';
        }
        if(isset($options['alignment'])) {
            $this->_alignment = $options['alignment'];
        }
        $html = sprintf('<form action="%s"', $action);
        $html .= $this->_formatOptions($options);
        $html .= ">";

        return $html;
    }

    /**
     * Ferme le formulaire
     */
    public function end() {
        return "</form>";
    }

    /**
     * Crée un bouton de submit
     */
    public function submit($value = 'Valider', $options = array()) {
        if(!isset($options['class'])) {
            $options['class'] = 'btn';
        } else {
            $options['class'] .= ' btn';
        }
        $html = '<div class="actions"><input type="submit" value="' . $value . '"';
        $html .= $this->_formatOptions($options);
        $html .= '></div>';

        return $html;
    }

    /**
     * Affiche un input ou un textarea
     * @param $name Nom du champ qui sera transmis au model
     * @param $label Texte affiché avant l'input, mettre hidden pour un champ caché
     * @param $options Tableau d'options à appliquer à l'input (type, class, rows, cols, ...)
     */
    public function input($name, $options = array()) {
        $html = '';
        $error = false;
        $classError = '';
        if (isset($this->errors[$name])) {
            $error = $this->errors[$name];
            $classError = ' error';
        }
        // Si la valeur n'est pas précisée mais qu'elle vient d'être postée
        if (!isset($options['value']) && isset($this->_controller->Request->post->$name)) {
            $options['value'] = $this->_controller->Request->post->$name;
        }
        // Si le type n'est pas précisé, par défaut : text
        if (!isset($options['type'])) {
            $options['type'] = 'text';
        }
        if ($options['type'] == 'hidden') {
            return '<input type="hidden" name="' . $name . '" value="' . $options['value'] . '">';
        }
        if (isset($this->_controller->Request->post->$name)
            && $options['type'] != 'textarea'
            && $options['type'] != 'checkbox') {
            $options['value'] = $this->_controller->Request->post->$name;
        }
        $html .= "<div class=\"row$classError\">";
        if(isset($options['label'])) {
            if(!isset($options['id'])) {
                $options['id'] = $this->_generateId($name);
            } else {
                $this->_usedId[] = $options['id'];
            }
            $html .= '<label for="' . $options['id'] . '">' . $options['label'] . '</label>';
        }
        $fin = "";
        $debut = "";
        if ($error && $this->_alignment == 'right') {
            $html .= '<span class="help-inline">' . $error . '</span>';
        }
        if ($options['type'] == 'text') {
            $html .= '<input type="text"';
        } elseif ($options['type'] == 'email') {
            $html .= '<input type="email"';
        } elseif ($options['type'] == 'url') {
            $html .= '<input type="text" pattern="http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?"';
        } elseif ($options['type'] == 'file') {
            $html .= '<input type="file"';
            // Si on ne précise pas de type de fichier, on n'essaie pas d'afficher ce dernier
            if (isset($options['file_type'])) {
                if ($options['file_type'] == 'avatar') {
                    if(!isset($options['id'])) {
                        $options['id'] = $this->_generateId($name);
                    } else {
                        $this->_usedId[] = $options['id'];
                    }
                    $debut = '<label for="' . $options['id'] . '">';
                    $debut .= $this->getAvatar($this->_controller->Session->read('user')->id);
                    $debut .= '</label>';
                }
            }
        } elseif ($options['type'] == 'checkbox') {
            $html .= '<input type="hidden" name="' . $name . '" value="0"><input type="checkbox"';
            if ($options['value'] == $this->_controller->Request->post->$name) {
                $html .= 'checked ';
            }
        } elseif ($options['type'] == 'password') {
            $html .= '<input type="password" ';
        } elseif ($options['type'] == 'textarea') {
            $html .= "<textarea ";
            $fin = $this->_controller->Request->post->$name . "</textarea>";
        }
        $html .= ' name="' . $name . '"';
        $html .= $this->_formatOptions($options);
        $html .= '>' . $fin;
        if ($error && $this->_alignment == 'left') {
            $html .= '<span class="help-inline">' . $error . '</span>';
        }
        $html .= "</div>";

        return $debut . $html . "\n";
    }

    public function radio($name, array $data, array $options = array()) {
    }

    public function select($name, array $data, array $options = array()) {
        $html = "\n";
        if(isset($options['label'])) {
            if(!isset($options['id'])) {
                $options['id'] = $this->_generateId($name);
            } else {
                $this->_usedId[] = $options['id'];
            }
            $html .= '<label for="' . $options['id'] . '">' . $options['label'] . '</label>';
        }
        $html .= '<select name="' . $name . '"';
        $html .= $this->_formatOptions($options);
        $html .= '>';
        foreach ($data as $k => $v) {
            $html .= '<option value="' . $k . '"';
            if(isset($options['value']) && $k == $options['value']) {
                $html .= ' selected';
            }
            $html .= '>' . $v . "</option>\n";
        }
        $html .= "</select>\n";

        return $html;
    }

    /**
     * Affiche l'avatar de l'utilisateur $id si il en a un
     * @param int $id Id de l'utilisateur
     */
    public function getAvatar($id = null) {
        $html = "";
        if ($id && file_exists(WEBROOT_DIR . "/img/users/" . $this->_controller->getAvatarName($id))) {
            $url = url("img/users/" . $this->_controller->getAvatarName($id));
            $html = '<div class="img"><img src="' . $url . '" alt="Avatar" width="150"></div>';
        }

        return $html;
    }

    /**
     * Transform a string in valid slug
     * @param string $text Text to transform
     * @return string Valid url
     */
    public function slug($text) {
        $slug = str_replace(" ", "-", strtolower($text));
        $slug = str_replace("_", "-", $slug);
        $slug = removeAccents($slug);
        $slug = preg_filter('#[^a-z0-9|^\-]*#', "", $slug);

        return $slug;
    }

}
