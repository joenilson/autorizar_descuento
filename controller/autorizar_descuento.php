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
 * Description of autorizar_descuento
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class autorizar_descuento extends fs_controller{
    public $accion;
    public $autoriza;
    public $autorizaciones_descuento;
    public function __construct() {
        parent::__construct(__CLASS__, 'Descuentos', 'contabilidad', TRUE, FALSE, FALSE);
    }

    protected function private_core() 
    {
        $this->shared_extensions();
        $accion = \filter_input(INPUT_POST,'accion');
        switch ($accion){
            case "autorizar":
                $this->verificar_informacion();
                break;
            default:
                break;
        }
    }

    private function verificar_informacion()
    {
        $usuario = \filter_input(INPUT_POST, 'usuario');
        $password = \filter_input(INPUT_POST, 'password');
        $descuento = \filter_input(INPUT_POST, 'descuento');
        $codcliente = \filter_input(INPUT_POST, 'codcliente');
        $solicitante = \filter_input(INPUT_POST, 'solicitante');
        $documento = \filter_input(INPUT_POST, 'documento');
        $iddocumento = \filter_input(INPUT_POST, 'iddocumento');
        $data['success']=false;
        $data['mensaje']='';
        if($this->autorizado($usuario,$password)){
            $autorizacion = new autorizaciones_descuento();
            $autorizacion->usuario = $usuario;
            $autorizacion->solicitante = ($solicitante)?$solicitante:$this->user->nick;
            $autorizacion->fecha = \date('d-m-Y');
            $autorizacion->codcliente = $codcliente;
            $autorizacion->descuento = $descuento;
            $autorizacion->documento = $documento;
            $autorizacion->iddocumento = $iddocumento;
            $autorizacion->usuario_creacion = $this->user->nick;
            $autorizacion->fecha_creacion = \date('d-m-Y H:i:s');
            if($autorizacion->save())
            {
                $data['success']=true;
                $data['descuento']=$descuento;
                $data['mensaje']='¡Autorización número '.$autorizacion->id.' guardada correctamente!';
            }else{
                $data['mensaje']='¡Ocurrió un error al guardar la autorización, intentelo nuevamente!';
            }
        }else{
            $data['mensaje']='¡Usuario no autorizado!';
        }
        $this->template = false;
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    
    /**
     * Verificamos el usuario de forma sencilla, esto despues debería de llamarse de un controller de autentificacion
     * @param type $usuario
     * @param type $password
     * @return boolean
     */
    private function autorizado($usuario,$password)
    {
        //Buscamos si el usuario está en el listado de usuarios
        $user = $this->user->get($usuario);
        //Si existe 
        if($user AND $user->enabled)
        {
            if( $user->password == sha1($password) OR $user->password == sha1( mb_strtolower($password, 'UTF8') ) )
            {
                $auth = new autoriza_descuento();
                return $auth->get_activo($usuario);
            }
            else
            {
                return false;
            }
        }
    }
    
    public function shared_extensions(){
        $extensiones = array(
            array(
                'name' => '001_admin_autorizaciones_js',
                'page_from' => __CLASS__,
                'page_to' => 'nueva_venta',
                'type' => 'head',
                'text' => '<script src='.FS_PATH.'"plugins/autorizar_descuento/view/js/autorizar_descuento.js" type="text/javascript"></script>',
                'params' => ''
            ),
            array(
                'name' => '002_admin_autorizaciones_js',
                'page_from' => __CLASS__,
                'page_to' => 'ventas_albaran',
                'type' => 'head',
                'text' => '<script src='.FS_PATH.'"plugins/autorizar_descuento/view/js/autorizar_descuento.js" type="text/javascript"></script>',
                'params' => ''
            ),
        );
        //Si está el plugin de presupuestos y pedidos agregamos si header
        if(in_array('presupuestos_y_pedidos',$GLOBALS['plugins'])){
            $extensiones[] = array(
                'name' => '003_admin_autorizaciones_js',
                'page_from' => __CLASS__,
                'page_to' => 'ventas_pedido',
                'type' => 'head',
                'text' => '<script src='.FS_PATH.'"plugins/autorizar_descuento/view/js/autorizar_descuento.js" type="text/javascript"></script>',
                'params' => ''
            );
        }
        foreach($extensiones as $del){
            $fext = new fs_extension($del);
            if(!$fext->save()){
                $this->new_error_msg('Imposible guardar los datos de la extensión ' . $ext['name'] . '.');
            }
        }
    }
}
