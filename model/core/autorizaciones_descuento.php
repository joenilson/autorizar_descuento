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
class autorizaciones_descuento extends \fs_model {
    /**
     * Id unico del ingreso
     * @var type integer
     */
    public $id;
    /**
     * usuario con permisos para
     * autorizar descuento
     * @var type varchar(12)
     */
    public $usuario;
    /**
     * usuario solicitante 
     * del descuento
     * @var type varchar(12)
     */
    public $solicitante;
    /**
     * Codigo del cliente al que se está autorizando el descuento
     * @var type varchar(6)
     */
    public $codcliente;
    /**
     * Id del documento a autorizar
     * si es en nueva_venta este valor estará en 0
     * @var type integer
     */
    public $iddocumento;
    /**
     * Tipo de documento que se está autorizando
     * @var type varchar(20)
     */
    public $documento;
    /**
     * Fecha en la que se solicitó la autorizacion
     * @var type date
     */
    public $fecha;
    /**
     * Valor de descuento autorizado
     * @var type float
     */
    public $descuento;
    /**
     * Fecha de creación del ingreso
     * @var type timestamp without timezone
     */
    public $fecha_creacion;
    /**
     * Usuario que guarda la entrada en el controlador
     * esto para efectos de auditoria
     * @var type varchar(12)
     */
    public $usuario_creacion;
    public function __construct($t = false) {
        parent::__construct('autorizaciones_descuento','plugins/autorizar_descuento');
        if($t)
        {
            $this->id = $t['id'];
            $this->usuario = $t['usuario'];
            $this->solicitante = $t['solicitante'];
            $this->codcliente = $t['codcliente'];
            $this->iddocumento = $t['iddocumento'];
            $this->documento = $t['documento'];
            $this->fecha = $t['fecha'];
            $this->descuento = floatval($t['descuento']);
            $this->fecha_creacion = $t['fecha_creacion'];
            $this->usuario_creacion = $t['usuario_creacion'];
        }
        else
        {
            $this->id = null;
            $this->usuario = null;
            $this->solicitante = null;
            $this->codcliente = null;
            $this->iddocumento = null;
            $this->documento = null;
            $this->fecha = null;
            $this->descuento = null;
            $this->fecha_creacion = null;
            $this->usuario_creacion = null;
        }
    }
    
    protected function install() {
        return '';
    }
    
    public function exists() {
        if(is_null($this->id))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function save() {
        if($this->exists())
        {
            $sql = "";
            return true;
        }
        else
        {
            $sql = "INSERT INTO ".$this->table_name." (usuario,solicitante,codcliente,iddocumento,documento,fecha,descuento,fecha_creacion,usuario_creacion) VALUES (".
                $this->var2str($this->usuario).",".
                $this->var2str($this->solicitante).",".
                $this->var2str($this->codcliente).",".
                $this->intval($this->iddocumento).",".
                $this->var2str($this->documento).",".
                $this->var2str($this->fecha).",".
                $this->var2str($this->descuento).",".
                $this->var2str($this->fecha_creacion).",".
                $this->var2str($this->usuario_creacion).
            ");";
            return $this->db->exec($sql);
        }
    }
    
    public function all()
    {
        $lista = array();
        $sql = "SELECT * FROM ".$this->table_name." ORER BY fecha,documento,usuario,solicitante;";
        $data = $this->db->select($sql);
        if($data)
        {
            foreach($data as $d)
            {
                $lista[] = new autorizaciones_descuento($d);
            }
        }
        return $lista;
    }
    
    public function all_usuario($usuario=false,$solicitante=false)
    {
        $lista = array();
        $query = "usuario != '' ";
        if($usuario)
        {
            $query .= " AND usuario = ".$this->var2str($usuario);
        }
        
        if($solicitante)
        {
            $query .= " AND solicitante = ".$this->var2str($solicitante);
        }
        $sql = "SELECT * FROM ".$this->table_name." WHERE ".$query." ORDER BY usuario,solicitante";
        $data = $this->db->select($sql);
        if($data)
        {
            foreach($data as $d)
            {
                $lista[] = new autorizaciones_descuento($d);
            }
        }
        return $lista;
    }
    
    public function delete() {
        return true;
    }
}
