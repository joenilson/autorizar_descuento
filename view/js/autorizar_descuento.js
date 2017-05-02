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
 * Verificamos que estén ingresando un número y no permitimos letras ni separador de decimal
 * en formato coma
 * @param {type} element
 * @returns number
 */
function verificarDescuento(element){
    element.value = element.value.replace(/[^0-9\.]/g,'');
}

$(document).ready(function()
{
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    //Guardamos el tipo de documento que se va autorizar
    var tipoDocumento = getUrlParameter('tipo');

    //Verificamos si ya se cargó el formulario de nueva_venta y si el documento no es del tipo presupuesto
    if ($("#f_new_albaran").length === 1 && tipoDocumento!=='presupuesto') {
        //Buscamos la primera linea de ayuda para modificarla indicando la presencia del plugin autorizar_descuento
        var textoAyuda = $("div.col-sm-6 > p.help-block").first();
        textoAyuda.html('<span class="text-warning fa fa-2x fa-info-circle"></span>&nbsp;Está activo el plugin <b>Autorizar Descuento</b> por lo que deberá de solicitar autorización para aplicar un descuento a un usuario autorizado para realizar esta acción.');
        //buscamos el boton guardar
        var botonGuardar = $("button:contains('Guardar...')");
        //Le agregamos antes de este el boton de Autorizar descuento
        botonGuardar.closest("div").prepend('<button id="btn_autorizar_descuento" class="btn btn-sm btn-warning"><span class="fa fa-unlock"></span>&nbsp;Autorizar Descuento</button>');
        //Obtenemos el nombre del cliente
        var nombreCliente = $('h1 > a').text();
        var codigoCliente = document.f_new_albaran.cliente.value;
        //Hacemos un seguimiento a las lineas_albaran para restringir a readonly cuando se agregue una linea nueva
        $('#lineas_albaran').bind('DOMNodeInserted', function(element) {
            var item = element.target.id;
            var item_parts = item.split('_');
            var linea = item_parts[1];
            $('#pvp_'+linea).attr('readonly','true');
            $('#dto_'+linea).attr('readonly','true');
            $('#neto_'+linea).attr('readonly','true');
            $('#iva_'+linea).attr('readonly','true');
            $('#total_'+linea).attr('readonly','true');
        });
    }

    $('#btn_autorizar_descuento').click(function(event) {
        event.preventDefault();
        var lineas = $("#lineas_albaran > tr").length;
        if(lineas>0){
            bootbox.dialog({
                title: "<b>¿Autorizar descuento para el cliente "+nombreCliente+"?</b>",
                message: '<div class="row">  ' +
                    '<div class="col-sm-12 col-md-12"> ' +
                    '<form id="f_autorizar_descuento" class="form"> ' +
                    '<input name="codcliente" type="hidden" value="'+codigoCliente+'"> ' +
                    '<input name="accion" type="hidden" value="autorizar"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-sm-4 col-md-4 control-label" for="usuario">Usuario</label> ' +
                    '<div class="col-sm-8 col-md-8"> ' +
                        '<div class="col-sm-7 col-md-7"> ' +
                            '<input id="usuario" name="usuario" autocomplete="off" type="text" required placeholder="Usuario que autoriza" class="col-sm-4 form-control input-sm"> ' +
                        '</div> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-sm-4 col-md-4 control-label" for="password">Clave</label> ' +
                    '<div class="col-sm-8 col-md-8"> ' +
                        '<div class="col-sm-7 col-md-7"> ' +
                            '<input id="password" name="password" type="password" required class="form-control input-sm" placeholder="Clave de autorización"> ' +
                        '</div> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-sm-4 col-md-4 control-label" for="descuento">Descuento</label> ' +
                    '<div class="col-sm-8 col-md-8"> ' +
                        '<div class="col-sm-3 col-md-3"> ' +
                            '<input id="descuento" autocomplete="off" name="descuento" onkeyup="verificarDescuento(this)" type="text" required class="form-control input-sm"> ' +
                        '</div> ' +
                    '</div> ' +
                    '<div class="col-md-4"> ' +
                    '</div> ' +
                    '<div class="col-md-8"> ' +
                    '<span class="help-block">Ingrese el porcentaje de descuento autorizado</span> </div> ' +
                    '</div> ' +
                    '</div> </div>' +
                    '</form> </div>  </div>',
                buttons: {
                    success: {
                        label: '<span class="fa fa-unlock"></span> Autorizar',
                        className: "btn-sm btn-success",
                        callback: function () {
                            $.ajax({
                                type: 'POST',
                                url: 'index.php?page=autorizar_descuento',
                                async: false,
                                data: $('#f_autorizar_descuento').serialize(),
                                success : function(datos) {
                                    if(datos.success){
                                        $('#lineas_albaran > tr').each(function(idx) {
                                            var item = this.id;
                                            var item_parts = item.split('_');
                                            var linea = item_parts[1];
                                            if($('#dto_'+linea).val()==='0'){
                                                $('#dto_'+linea).val(datos.descuento);
                                            }
                                        });
                                        recalcular();
                                        bootbox.alert({
                                            title: "¡Descuento autorizado!",
                                            message: 'Se ha autorizado el descuento y recalculado los valores, proceda a guardar el documento.<br/>'+
                                                'Si agrega una nueva linea despues de esta autorización no se le aplicará el descuento.'
                                        });
                                    }
                                    else{
                                        bootbox.alert({
                                            title: "¡Descuento no autorizado!",
                                            message: datos.mensaje
                                        });
                                    }
                                },
                                error: function(datos) {
                                    bootbox.alert("Ocurrio un error no contemplado en el plugin, por favor envie un mensaje en el foro de soporte de FacturaScripts para verificar el problema, gracias.");
                                }
                            });
                        }
                    },
                    danger: {
                      label: '<span class="fa fa-times"></span> Cancelar',
                      className: "btn-sm btn-danger",
                      callback: function() {
                        this.hide();
                      }
                    }
                }
            });
        }
        else
        {
            bootbox.alert({title:'Solicitud incompleta',message:'¡Debes agregar artículos antes de solicitar una autorización de descuento!'});
        }
    });
});



