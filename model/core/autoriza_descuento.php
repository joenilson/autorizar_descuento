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

namespace FacturaScripts\model;
/**
 * Tabla con el listado de usuarios que pueden autorizar
 * descuentos a ser aplicados
 *
 * @author Joe Nilson <joenilson at gmail.com>
 */
class autoriza_descuento extends \fs_model {
    /**
     * usuario con permisos para
     * autorizar descuento
     * @var type varchar(12)
     */
    public $usuario;
    /**
     * Usuario que agrega la entrada
     * @var type varchar(12)
     */
    public $usuario_creacion;
    /**
     * Fecha en la que se creó el usuario
     * @var type date without timestamp
     */
    public $fecha_creacion;
    /**
     * Si se manda a eliminar solo se desactiva para no perder el historico
     * @var type boolean
     */
    public $estado;
    public function __construct($t = false) {
        parent::__construct('autoriza_descuento','plugins/autorizar_descuento');
        if($t)
        {
            $this->usuario = $t['usuario'];
            $this->usuario_creacion = $t['usuario_creacion'];
            $this->fecha_creacion = $t['fecha_creacion'];
            $this->estado = $this->str2bool($t['estado']);
            
        }
        else
        {
            $this->usuario = null;
            $this->usuario_creacion = null;
            $this->fecha_creacion = null;
            $this->estado = false;
        }
    }
    
    protected function install() {
        return '';
    }
    
    public function get($usuario)
    {
        $sql = "SELECT * FROM ".$this->table_name." WHERE usuario = ".$this->var2str($usuario);
        $data = $this->db->select($sql);
        if($data)
        {
            return new autoriza_descuento($data[0]);
        }
        else
        {
            return false;
        }
    }
    
    public function get_activo($usuario)
    {
        $sql = "SELECT * FROM ".$this->table_name." WHERE estado = TRUE AND usuario = ".$this->var2str($usuario);
        $data = $this->db->select($sql);
        if($data)
        {
            return new autoriza_descuento($data[0]);
        }
        else
        {
            return false;
        }
    }
    
    public function all()
    {
        $lista = array();
        $sql = "SELECT * FROM ".$this->table_name." ORDER BY usuario;";
        $data = $this->db->select($sql);
        if($data)
        {
            foreach($data as $d)
            {
                $lista[] = new autoriza_descuento($d);
            }
            return $lista;
        }
        else
        {
            return false;
        }
    }
    
    public function exists() {
        return $this->get($this->usuario);
    }
    
    public function save() {
        if($this->exists())
        {
            return true;
        }
        else
        {
            $sql = "INSERT INTO ".$this->table_name." (usuario,usuario_creacion,fecha_creacion,estado) VALUES (".
                $this->var2str($this->usuario).",".
                $this->var2str($this->usuario_creacion).",".
                $this->var2str($this->fecha_creacion).",".
                $this->var2str($this->estado).");";
                if($this->db->exec($sql)){
                    return true;
                }else{
                    return false;
                }
        }
    }
    
    public function delete() {
        $sql = "UPDATE ".$this->table_name." SET estado = FALSE WHERE usuario = ".$this->var2str($this->usuario);
        return $this->db->exec($sql);
    }
    
    public function desactivar() {
        $sql = "UPDATE ".$this->table_name." SET estado = FALSE WHERE usuario = ".$this->var2str($this->usuario);
        return $this->db->exec($sql);
    }
    
    public function activar() {
        $sql = "UPDATE ".$this->table_name." SET estado = TRUE WHERE usuario = ".$this->var2str($this->usuario);
        return $this->db->exec($sql);
    }
    
    public function total_autorizaciones()
    {
        $sql = "SELECT count(*) as total FROM autorizaciones_descuento WHERE usuario = ".$this->var2str($this->usuario);
        $data = $this->db->select($sql);
        return (int)$data[0]['total'];
    }
}
