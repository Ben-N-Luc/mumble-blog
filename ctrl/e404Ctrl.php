<?php

class e404Ctrl extends Ctrl {

    public function e404() {
        if(isset($this->Request->params[0])) {
            $d['msg'] = $this->Request->params[0];
        } else {
            $d['msg'] = '';
        }

        $this->set($d);
    }

}
