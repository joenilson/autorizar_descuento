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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Verificamos que estén ingresando un número y no permitimos letras ni separador de decimal
 * en formato coma
 * @param {type} element
 * @returns number
 * 
 * @update Fix with button
 * @date 08-08-2018
 * @author hernandomanotoa@gmail.com
 * @url private https://www.facturascripts.com/comm3/index.php?page=community_item&id=7292
 */
function verificarDescuento(element)
{
    element.value = element.value.replace(/[^0-9\.]/g, '');
}

function bloquearDescuentos()
{
    $('#lineas_doc > tr').each(function (idx) {
        var item = this.id;
        var item_parts = item.split('_');
        var linea = item_parts[1];
        $('#pvp_' + linea).attr('readonly', true);
        $('#dto_' + linea).attr('readonly', true);
        $('#dto2_' + linea).attr('readonly', true);
        $('#dto3_' + linea).attr('readonly', true);
        $('#dto4_' + linea).attr('readonly', true);
        $('#neto_' + linea).attr('readonly', true);
        $('#iva_' + linea).attr('readonly', true);
        $('#total_' + linea).attr('readonly', true);
    });

    $('#adtopor1').attr('readonly', true);
    $('#adtopor2').attr('readonly', true);
    $('#adtopor3').attr('readonly', true);
    $('#adtopor4').attr('readonly', true);
    $('#adtopor5').attr('readonly', true);
    $('#btn_bloquear_descuento').remove();
    $('#btn_autorizar_descuento').show();
}

function nuevaVenta(tipoDocumento)
{
    //Verificamos si ya se cargó el formulario de nueva_venta y si el documento no es del tipo presupuesto
    if ($("#f_new_albaran").length === 1 && tipoDocumento!=='presupuesto') {
        //Buscamos la primera linea de ayuda para modificarla indicando la presencia del plugin autorizar_descuento
        var textoAyuda = $("div.col-sm-6 > p.help-block").first();
        textoAyuda.html('<span class="text-warning fa fa-2x fa-info-circle"></span>&nbsp;Está activo el plugin <b>Autorizar Descuento</b> por lo que deberá de solicitar autorización para aplicar un descuento a un usuario autorizado para realizar esta acción.');
        //buscamos el boton guardar
        var botonGuardar = $("button:contains('Guardar...')");
        //Le agregamos antes de este el boton de Autorizar descuento
        botonGuardar.closest("div").prepend('<button id="btn_autorizar_descuento" class="btn btn-sm btn-warning"><span class="fa fa-unlock"></span>&nbsp;Autorizar Descuento</button>');
        //Hacemos un seguimiento a las lineas_doc para restringir a readonly cuando se agregue una linea nueva
        $('#lineas_doc').bind('DOMNodeInserted', function (element) {
            var item = element.target.id;
            var item_parts = item.split('_');
            var linea = item_parts[1];
            $('#pvp_' + linea).attr('readonly', 'true');
            $('#dto_' + linea).attr('readonly', 'true');
            $('#dto2_' + linea).attr('readonly', 'true');
            $('#dto3_' + linea).attr('readonly', 'true');
            $('#dto4_' + linea).attr('readonly', 'true');
            $('#neto_' + linea).attr('readonly', 'true');
            $('#iva_' + linea).attr('readonly', 'true');
            $('#total_' + linea).attr('readonly', 'true');
        });
        $('#adtopor1').attr('readonly', 'true');
        $('#adtopor2').attr('readonly', 'true');
        $('#adtopor3').attr('readonly', 'true');
        $('#adtopor4').attr('readonly', 'true');
        $('#adtopor5').attr('readonly', 'true');
    }
}

function ventas(formulario)
{
    //Verificamos si ya se cargó el formulario de ventas_pedido
    if (formulario) {
        //buscamos el boton imprimir
        var botonImprimir = $("#b_imprimir");
        //Le agregamos despues de este el boton de Autorizar descuento
        botonImprimir.closest("div").append('<button id="btn_autorizar_descuento" class="btn btn-sm btn-default"><span class="fa fa-unlock"></span>&nbsp;Autorizar Descuento</button>');
        //Activamos el control para que no modifiquen la información de las lineas
        $('#lineas_doc > tr').each(function (idx) {
            var item = this.id;
            var item_parts = item.split('_');
            var linea = item_parts[1];
            $('#pvp_' + linea).attr('readonly', 'true');
            $('#dto_' + linea).attr('readonly', 'true');
            $('#dto2_' + linea).attr('readonly', 'true');
            $('#dto3_' + linea).attr('readonly', 'true');
            $('#dto4_' + linea).attr('readonly', 'true');
            $('#neto_' + linea).attr('readonly', 'true');
            $('#iva_' + linea).attr('readonly', 'true');
            $('#total_' + linea).attr('readonly', 'true');
        });
        $('#adtopor1').attr('readonly', 'true');
        $('#adtopor2').attr('readonly', 'true');
        $('#adtopor3').attr('readonly', 'true');
        $('#adtopor4').attr('readonly', 'true');
        $('#adtopor5').attr('readonly', 'true');

        //Hacemos un seguimiento a las lineas_doc para restringir a readonly cuando se agregue una linea nueva
        $('#lineas_doc').bind('DOMNodeInserted', function (element) {
            var item = element.target.id;
            var item_parts = item.split('_');
            var linea = item_parts[1];
            $('#pvp_' + linea).attr('readonly', 'true');
            $('#dto_' + linea).attr('readonly', 'true');
            $('#dto2_' + linea).attr('readonly', 'true');
            $('#dto3_' + linea).attr('readonly', 'true');
            $('#dto4_' + linea).attr('readonly', 'true');
            $('#neto_' + linea).attr('readonly', 'true');
            $('#iva_' + linea).attr('readonly', 'true');
            $('#total_' + linea).attr('readonly', 'true');
        });
        
        $('#adtopor1').attr('readonly', 'true');
        $('#adtopor2').attr('readonly', 'true');
        $('#adtopor3').attr('readonly', 'true');
        $('#adtopor4').attr('readonly', 'true');
        $('#adtopor5').attr('readonly', 'true');
        
    }
}

$(document).ready(function ()
{
    bootbox.setLocale('es');
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

    //Obtenemos la información de la página
    var page = getUrlParameter('page');
    //Obtenemos el nick del usuario solicitante
    var usuario = $("li.user > a > span").text();

    switch (page) {
        case 'nueva_venta':
            //Guardamos el tipo de documento que se va autorizar
            var tipoDocumento = getUrlParameter('tipo');
            if ($("#f_new_albaran").length === 1) {
                $("#f_new_albaran select[name=forma_pago] option:not(:selected)").attr('disabled', true);
                document.f_new_albaran.forma_pago.readonly = true;
                var nombreCliente = $('h1 > a').text();
                var codigoCliente = document.f_new_albaran.cliente.value;
                var iddocumento = 0;
                var documento = tipoDocumento;
            }
            nuevaVenta(tipoDocumento);
            break;
        case 'ventas_pedido':
            var pendiente = $("button:contains('Pendiente')");
            $("select[name=forma_pago] option:not(:selected)").attr('disabled', true);
            if (pendiente.length === 1) {
                var nombreCliente = document.f_pedido.ac_cliente.value;
                var codigoCliente = document.f_pedido.cliente.value;
                var iddocumento = document.f_pedido.idpedido.value;
                var documento = 'pedido';
                ventas(document.f_pedido);
            }
            break;
        case 'ventas_albaran':
            var pendiente = $("button:contains('Pendiente')");
            $("select[name=forma_pago] option:not(:selected)").attr('disabled', true);
            if (pendiente.length === 1) {
                var nombreCliente = document.f_albaran.ac_cliente.value;
                var codigoCliente = document.f_albaran.cliente.value;
                var iddocumento = document.f_albaran.idalbaran.value;
                var documento = 'albaran';
                ventas(document.f_albaran);
            }
            break;
        case 'ventas_factura':
            $("select[name=forma_pago] option:not(:selected)").attr('disabled', true);
            $("input[name=fecha]").attr('readonly', true);
            break;
        default:
            break;
    }

    $('#btn_autorizar_descuento').click(function (event) {
        var lineas = $("#lineas_doc > tr").length;
        event.preventDefault();
        if (lineas > 0) {
            bootbox.dialog({
                title: "<b>¿Autorizar descuento para el cliente " + nombreCliente + "?</b>",
                message: '<div class="row"> ' +
                        '<form id="f_autorizar_descuento" class="form"> ' +
                            '<div class="col-sm-12"> ' +
                        '<input name="codcliente" type="hidden" value="' + codigoCliente + '"> ' +
                        '<input name="solicitante" type="hidden" value="' + usuario + '"> ' +
                        '<input name="iddocumento" type="hidden" value="' + iddocumento + '"> ' +
                        '<input name="documento" type="hidden" value="' + documento + '"> ' +
                                '<input name="accion" type="hidden" value="autorizar"> ' +
                                '<div id="usuario" class="form-group"> ' +
                                    '<label class="col-sm-4 control-label" for="usuario">Usuario</label> ' +
                                    '<div class="col-sm-8"> ' +
                                        '<div class="col-sm-7"> ' +
                                            '<input id="usuario_input" name="usuario" autocomplete="off" type="text" required placeholder="Usuario que autoriza" class="col-sm-4 form-control input-sm"> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div>' +
                                '<div id="password" class="form-group"> ' +
                                    '<label class="col-sm-4 control-label" for="password">Clave</label> ' +
                                    '<div class="col-sm-8"> ' +
                                        '<div class="col-sm-7"> ' +
                                            '<input id="password_input" name="password" type="password" required class="form-control input-sm" placeholder="Clave de autorización"> ' +
                                        '</div> ' +
                                    '</div> ' +
                                '</div>' +
                            '</div>' +
                        '</form>' +
                    '</div>' +
                    '<div class="well text-primary">' +
                        'Si el usuario es correcto se activarán la casillas de descuento para su edición.<br />' +
                        'Al concluir deberá darle al boton <b>Bloquear Descuentos</b> para volver a aplicar los controles.<br />' +
                    '</div>' +
                    '<div id="alerta_autorizacion" class="alert alert-warning hidden">' +
                    '</div>',
                buttons: {
                    success: {
                        label: '<span class="fa fa-unlock"></span> Autorizar',
                        className: "btn-sm btn-success",
                        callback: function () {
                            //Verificamos si llenaron los datos del formulario
                            if (!$('#alerta_autorizacion').hasClass('hidden')) {
                                $('#alerta_autorizacion').addClass('hidden');
                            }

                            if ($('#usuario').hasClass('has-error')) {
                                $('#usuario').removeClass('has-error');
                            }

                            if ($('#password').hasClass('has-error')) {
                                $('#password').removeClass('has-error');
                            }

                            var continuar = false;
                            if ($('#usuario_input').val() !== '' && $('#password_input').val() !== '') {
                                continuar = true;
                            }
                            if (continuar) {
                                $('#btn_autorizar_descuento').hide();
                                $.ajax({
                                    type: 'POST',
                                    url: 'index.php?page=autorizar_descuento',
                                    async: false,
                                    data: $('#f_autorizar_descuento').serialize(),
                                    success: function (datos) {
                                        if (datos.success) {
                                            $('#lineas_doc > tr').each(function (idx) {
                                                var item = this.id;
                                                var item_parts = item.split('_');
                                                var linea = item_parts[1];
                                                $('#pvp_' + linea).attr('readonly', false);
                                                $('#dto_' + linea).attr('readonly', false);
                                                $('#dto2_' + linea).attr('readonly', false);
                                                $('#dto3_' + linea).attr('readonly', false);
                                                $('#dto4_' + linea).attr('readonly', false);
                                                $('#neto_' + linea).attr('readonly', false);
                                                $('#iva_' + linea).attr('readonly', false);
                                                $('#total_' + linea).attr('readonly', false);
                                            });

                                            $('#adtopor1').attr('readonly', false);
                                            $('#adtopor2').attr('readonly', false);
                                            $('#adtopor3').attr('readonly', false);
                                            $('#adtopor4').attr('readonly', false);
                                            $('#adtopor5').attr('readonly', false);
                                            $("#btn_autorizar_descuento").closest("div").append('<button id="btn_bloquear_descuento" type="button" onclick="bloquearDescuentos()" class="btn btn-sm btn-success"><span class="fa fa-lock"></span>&nbsp;Bloquear Descuentos</button>');
                                        } else {
                                            bootbox.alert({
                                                title: "¡Descuento no autorizado!",
                                                message: datos.mensaje
                                            });
                                        }
                                    },
                                    error: function (datos) {
                                        bootbox.alert("Ocurrio un error no contemplado en el plugin, por favor envie un mensaje en el foro de soporte de FacturaScripts para verificar el problema, gracias.");
                                    }
                                });
                            }
                            else
                            {

                                if ($('#usuario_input').val() === '')
                                {
                                    $('#usuario').addClass('has-error');
                                }

                                if ($('#password_input').val() === '')
                                {
                                    $('#password').addClass('has-error');
                                }

                                $('#alerta_autorizacion').html('Complete la información de los campos resaltados en rojo');
                                $('#alerta_autorizacion').removeClass('hidden');
                                return false;
                            }
                        }
                    },
                    danger: {
                      label: '<span class="fa fa-times"></span> Cancelar',
                      className: "btn-sm btn-danger",
                      callback: function () {
                        this.hide();
                      }
                    }
                }
            });
        }
        else
        {
            bootbox.alert({title: 'Solicitud incompleta', message: '¡Debes agregar artículos antes de solicitar una autorización de descuento!'});
        }
    });

});