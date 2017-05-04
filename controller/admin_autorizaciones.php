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
require_model('autoriza_descuento.php');
require_model('autorizaciones_descuento.php');
/**
 * Description of admin_autorizaciones
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class admin_autorizaciones extends fs_controller{
    public $allow_delete;
    public $autoriza_descuento;
    public function __construct() {
        parent::__construct(__CLASS__, 'Autorización de Descuentos', 'contabilidad', FALSE, TRUE, FALSE);
        //Cargamos las tablas
        new autoriza_descuento();
        new autorizaciones_descuento();
    }

    protected function private_core() {
        //Primero revisamos si están activas todas las páginas del menú
        $this->check_menu();
        // ¿El usuario tiene permiso para eliminar en esta página?
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
        $this->autoriza_descuento = new autoriza_descuento();
        $accion = \filter_input(INPUT_POST, 'accion');
        switch($accion)
        {
            case "desactivar":
            case "activar":
            case "agregar":
                $this->tratar_usuario($accion);
            break;
            default:
            break;
        }
    }
    
    private function tratar_usuario($accion)
    {
        $usuario = \filter_input(INPUT_POST, 'usuario');
        if($usuario){
            $auth = new autoriza_descuento();
            $auth->usuario = $usuario;
            $auth->fecha_creacion = \date('d-m-Y H:i:s');
            $auth->usuario_creacion = $this->user->nick;
            if($accion=='agregar'){
                $auth->estado = TRUE;
                if($auth->save())
                {
                    $this->new_message('Usuario <b>'.$usuario.'</b> agregado exitosamente para autorizar descuentos.');
                }
                else
                {
                    $this->new_error_msg('No se pudo agregar el usuario <b>'.$usuario.'</b>, por favor intentelo nuevamente.');
                }
            }elseif($accion=='desactivar'){
                if($auth->desactivar())
                {
                    $this->new_message('Usuario <b>'.$usuario.'</b> desactivado exitosamente para autorizar descuentos.');
                }
                else
                {
                    $this->new_error_msg('No se pudo desactivar el usuario <b>'.$usuario.'</b>, por favor intentelo nuevamente.');
                }
            }elseif($accion=='activar'){
                if($auth->activar())
                {
                    $this->new_message('Usuario <b>'.$usuario.'</b> activado exitosamente para autorizar descuentos.');
                }
                else
                {
                    $this->new_error_msg('No se pudo activar el usuario <b>'.$usuario.'</b>, por favor intentelo nuevamente.');
                }
            }
        }
    }

    /**
     * Cargamos el menú en la base de datos, pero en varias pasadas.
     */
    private function check_menu() {
        if (file_exists(__DIR__)) {
            $max = 25;

            /// leemos todos los controladores del plugin
            foreach (scandir(__DIR__) as $f) {
                if ($f != '.' AND $f != '..' AND is_string($f) AND strlen($f) > 4 AND ! is_dir($f) AND $f != __CLASS__ . '.php') {
                    /// obtenemos el nombre
                    $page_name = substr($f, 0, -4);

                    /// lo buscamos en el menú
                    $encontrado = FALSE;
                    foreach ($this->menu as $m) {
                        if ($m->name == $page_name) {
                            $encontrado = TRUE;
                            break;
                        }
                    }

                    if (!$encontrado) {
                        require_once __DIR__ . '/' . $f;
                        $new_fsc = new $page_name();

                        if (!$new_fsc->page->save()) {
                            $this->new_error_msg("Imposible guardar la página " . $page_name);
                        }

                        unset($new_fsc);

                        if ($max > 0) {
                            $max--;
                        } else {
                            $this->recargar = TRUE;
                            $this->new_message('Instalando las entradas al menú para el plugin... &nbsp; <i class="fa fa-refresh fa-spin"></i>');
                            break;
                        }
                    }
                }
            }
        } else {
            $this->new_error_msg('No se encuentra el directorio ' . __DIR__);
        }

        $this->load_menu(TRUE);
    }
}
