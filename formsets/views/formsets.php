<?php
use yii\helpers\Html;

// GENERAMOS EL ALEATORIO QUE PERMITA DISTINGUIR TODOS LOS IDS COMO ÚNICOS EN LA PÁGINA
$rand = rand ();
$this->registerJs('

/*
 * EN ALGUNOS CASOS LOS WIDGETS PUEDEN CONTENER VARIOS LLAMADOS EN UNA MISMA PÁGINA
 * PARA EVITAR QUE SE REPITA EL JAVASCRIPT EVALUAMOS SI YA FUE DECLARADA LA FUNCION
 */
if(typeof loadJsWidgetFormsetJogerq === "undefined") {
    function loadJsWidgetFormsetJogerq()
    {
        $("body").on( "click", ".delete-item-widget-formset-jogerq" ,function() {
          itemEliminarFila = $(this);
          itemEliminarFila.parent().parent().remove();
        });

        $("body").on( "click", ".eliminar-seleccionados" ,function() {
           var checked = false;
           $(this).parent().parent().parent().parent().find(".td-check").each(function() { 
                if($(this).prop("checked")) {
                    $(this).parent().parent().remove(); 
                    checked = true;
                }
           }); 
           
           if(!checked) {
                $("#id-modal-'.$rand.'").modal("show");
           }

        });

        $(".eliminar-todos").click(function() {
            $(this).parent().parent().parent().parent().find(".td-check").prop("checked", $(this).prop("checked"));
        });


        $(document).on("beforeSubmit", "form", function () {
            $(".table-clone-instance-widgetformsets-jogerq").remove();
        });
    }

    loadJsWidgetFormsetJogerq();
}

/*
 * ESTE JAVASCRIPT SI SE PUEDE REPETIR PORQ1UE TIEN UN NÚMERO RAND EN EL ID DEL OBJETO.
 * SE HIZO ASÍ PARA EVITAR QUE CUANDO EL WIDGET ES UTILIZADO MAS DE UNA VEZ EN UNA PÁGINA
 * CADA OBJETO PUEDE SER INDEPENDIENTE UNO DEL OTRO
 */
$("#btn-add-item-to-widget-formset-jogerq-'.$rand.'").click(function() {
    var str = document.getElementById("tr-to-clone-instance-widgetformsets-jogerq-'.$rand.'").innerHTML;
    var res = str.replace(/change-position-widget-formset-jogerq/g,$("#total-registros-widget-formset-jogerq-'.$rand.'").val());
    $("#tabla-widwet-formset-jogerq-'.$rand.' tbody").append(res);
    var pos = $("#total-registros-widget-formset-jogerq-'.$rand.'").val();
    $("#total-registros-widget-formset-jogerq-'.$rand.'").val((pos * 1) + 1);
    /*
     * UBICAMOS SI HAY UN CAMPO DE FECHA PARA PROCEDER A OBTENER EL data-krajee-kvdatepicker DEL ELEMENTO
     */
    var dataKrajeeKvdatepicker = $("input[data-krajee-kvdatepicker]").attr("data-krajee-kvdatepicker");
    if(dataKrajeeKvdatepicker != undefined) 
        jQuery("#fecha-" + pos + "-'.$rand.'").kvDatepicker({"format":"yyyy-mm-dd","autoclose":true,"language":"es"});
});

', \yii\web\View::POS_READY);

$this->registerCss("
    .error-display-widget-formset-jogerq {
        display:none; 
    }
");

?>
<?php 
if($titulo) echo '<h2>'.Html::encode($titulo).'</h2>'; 
?>
<table class="table table-striped" width="100%" id="tabla-widwet-formset-jogerq-<?= $rand ?>">
    <thead>
        <?php 
        if($headers) {
            foreach ($headers as $header) {
                echo "<th class=\"text-center\">$header</th>";
            }
        }
        elseif(isset($models[0])) {
            foreach ($fields as $field) {
                //echo "<th class=\"text-center\">".print_r($field['name'],true)."</th>";
                echo "<th class=\"text-center\">".$models[0]->getAttributeLabel($field['name'])."</th>";
            }
        }
        ?>
        <th>
            &nbsp;
        </th>
        <th class="text-center">
            <input id="eliminar-todos-facturacion-<?= $rand ?>" class="eliminar-todos" type="checkbox" name="">
        </th>
    </thead>
    <tbody>
        <?= $widget->render('_input-formsets',['models' => $models, 'fields' => $fields, 'totals' => $totals,'number_format'=>$number_format,'rand'=> $rand,'codForeignKey' => $codForeignKey]); ?> 
    </tbody>
    <tfoot>
        <?php 
        if(isset(Yii::$app->params['totalesWidgetFormSetJogerq']) && Yii::$app->params['totalesWidgetFormSetJogerq']) {
            echo Yii::$app->params['totalesWidgetFormSetJogerq'];
        }
        ?>
        <tr>
            <td colspan="7">
                <button id="btn-add-item-to-widget-formset-jogerq-<?= $rand ?>" type="button" class="btn btn-primary" pos="0">Añadir Fila</button>
                <button type="button" class="btn btn-danger eliminar-seleccionados" id="eliminar-seleccionados-<?= $rand ?>">Eliminar Seleccionados</button>
                <input type="hidden" id="total-registros-widget-formset-jogerq-<?= $rand ?>" name="posicion" value="<?= count($models) ?>">
            </td>
        </tr>
    </tfoot>
</table>
<?php
/*
 * CREAMOS UNA COPIA DEL TR DE LA POSICION 0 PARA REPLICARLA CUANDO NOS AÑADAN UN TR NUEVO
 * 
*/
if(isset($models[0]) && $models[0] instanceof $instanceof):
?>
    <table style="display: none;" class="table-clone-instance-widgetformsets-jogerq">
        <tbody id="tr-to-clone-instance-widgetformsets-jogerq-<?= $rand ?>">
            <?= $widget->render('_input-formsets',['models' => [new $instanceof()], 'fields' => $fields,'trToRepite' => true, 'rand'=> $rand, 'codForeignKey' => $codForeignKey]); ?> 
        </tbody>
    </table>
<?php 
endif;

\yii\bootstrap\Modal::begin([
    'id' => "id-modal-$rand",
    'header' => '<h4 style="margin:0; padding:0">Notificación</h4>',
    //'toggleButton' => ['label' => 'Cerrar'],
    //'footer' => '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button></div>',
    'footer' => Html::a('Ok', '#', ['class' => 'btn btn-danger','data-dismiss'=>'modal']),
]);

echo 'Debe seleccionar por lo menos un item para ser eliminado';

\yii\bootstrap\Modal::end();