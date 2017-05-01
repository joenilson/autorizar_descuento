<?php

/*
 * Copyright (C) 2017 Joe Nilson <joenilson at gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
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

/**
 * Description of autorizar_descuento
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class autorizar_descuento extends fs_controller{
    public $accion;
    public $user;
    public function __construct() {
        parent::__construct(__CLASS__, 'Descuentos', 'contabilidad', FALSE, FALSE, FALSE);
    }

    protected function private_core() {
        $accion = \filter_input(INPUT_POST,'accion');
        switch ($accion){
            case "autorizar":
                $this->verificar_informacion();
                break;
            default:
                break;
        }
    }

    private function verificar_informacion(){
        $usuario = \filter_input(INPUT_POST, 'usuario');
        $password = \filter_input(INPUT_POST, 'password');
        $descuento = \filter_input(INPUT_POST, 'descuento');
        $data['usuario']=$usuario;
        $data['password']=$password;
        $data['descuento']=$descuento;
        $this->template = false;
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
