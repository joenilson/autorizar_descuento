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
 * Description of admin_autorizaciones
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class admin_autorizaciones extends fs_controller{
    public $allow_delete;
    public function __construct() {
        parent::__construct(__CLASS__, 'Autorización de Descuentos', 'contabilidad', FALSE, TRUE, FALSE);
    }

    protected function private_core() {
        //Primero revisamos si están activas todas las páginas del menú
        $this->check_menu();
        //Luego cargamos las extensiones asociadas
        $this->shared_extensions();
        // ¿El usuario tiene permiso para eliminar en esta página?
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);

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

    public function shared_extensions(){
        $extensiones = array(
            array(
                'name' => '001_admin_autorizaciones_js',
                'page_from' => 'nueva_venta',
                'page_to' => 'nueva_venta',
                'type' => 'head',
                'text' => '<script src='.FS_PATH.'"plugins/autorizar_descuento/view/js/autorizar_descuento.js" type="text/javascript"></script>',
                'params' => ''
            ),
        );
        foreach($extensiones as $del){
            $fext = new fs_extension($del);
            if(!$fext->save()){
                $this->new_error_msg('Imposible guardar los datos de la extensión ' . $ext['name'] . '.');
            }
        }
    }

}