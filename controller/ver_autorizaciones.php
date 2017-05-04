<?php

/*
 * Copyright (C) 2017 Joe Nilson <joenilson at gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_model('autoriza_descuento.php');
require_model('autorizaciones_descuento.php');
/**
 * Description of ver_autorizaciones
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class ver_autorizaciones extends fs_controller {
    public $usuario;
    public $autoriza;
    public $autorizaciones_descuento;
    public $resultados;
    public function __construct() {
        parent::__construct(__CLASS__, 'Ver Autorizaciones', 'contabilidad', FALSE, FALSE, FALSE);
    }
    
    protected function private_core() {
        $this->autoriza = new autoriza_descuento();
        $this->autorizaciones_descuento = new autorizaciones_descuento();
        $this->resultados = array();
        $usuario_p = \filter_input(INPUT_POST, 'usuario');
        $usuario_g = \filter_input(INPUT_GET, 'usuario');
        
        $this->usuario = ($usuario_p)?$usuario_p:$usuario_g;
        if($this->usuario)
        {
            $this->resultados = $this->autorizaciones_descuento->all_usuario($this->usuario);
        }
    }
}
