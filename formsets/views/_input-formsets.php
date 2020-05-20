<?php
use yii\helpers\Html;
$trToRepite = isset($trToRepite) ? $trToRepite : null;
$totals = isset($totals) ? $totals : false;
$codForeignKey = isset($codForeignKey) && $codForeignKey ? $codForeignKey : false;
$number_format = isset($number_format) ? $number_format : ['decimals' => 2 , 'dec_point' => "." , 'thousands_sep' => "," ];
$sums = [];

foreach ($models as $posModel => $model):
    //if($model->errors) print_r($model->errors,true);
?>
    <tr id="tr-pos-formsets-facturacion-<?= $trToRepite ? 'change-position-widget-formset-jogerq-'.$rand : $posModel.'-'.$rand; ?>'">
        <td style="display: none;">
            <?php
            if(!$trToRepite) {
                /* CREAMOS EL CAMPO ID EN CASO DE QUE TAIGA UN VALOR */
                if(isset($model->tableSchema->primaryKey) 
                    && isset($model->tableSchema->primaryKey[0]) 
                    && $model->{$model->tableSchema->primaryKey[0]}) {
                    echo Html::activeHiddenInput($model,"[$posModel]".$model->tableSchema->primaryKey[0]);
                }
            }
            /*
             * SI EXISTE UN CAMPO codForeignKey PROCEDEMOS A ESCRIBIRLO AQUÍ COMO UN HIDDEN
             */
            if($codForeignKey && is_array($codForeignKey) && isset($codForeignKey['field']) && isset($codForeignKey['value'])) {
                
                $model->{$codForeignKey['field']} = $codForeignKey['value'];
                echo Html::activeHiddenInput($model,"[".($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel)."]{$codForeignKey['field']}");
            }
            ?>
        </td>
        <?php
        foreach ($fields as $field):
            $nameField = $field['type'];
        ?>
            <td>
                <?php
                if($trToRepite) $models[0]->{$field['name']} = null;
                
                /*
                 * SI EXISTE UN ID LO COLOCAMOS AÑADIENDO LA POSICION EN EL FORM
                 */
                $field['options']['id'] = isset($field['options']) && isset($field['options']['id']) ? $field['options']['id'].= '-'.($trToRepite ? 'change-position-widget-formset-jogerq-'.$rand : $posModel.'-'.$rand) : "{$field['name']}-".($trToRepite ? 'change-position-widget-formset-jogerq-'.$rand : $posModel.'-'.$rand);
                $field['options']['pos'] = $trToRepite ? 'change-position-widget-formset-jogerq' : $posModel;

                if(is_array($nameField))
                {
                    $name = isset($nameField[0]) ? $nameField[0] : '';
                    $content = isset($nameField[1]) && $nameField[1] ? $nameField[1] : $model->{$field['name']};
                    if(isset($field['number_format']) && $field['number_format']) $content = number_format($content,$number_format['decimals'],$number_format['dec_point'],$number_format['thousands_sep']);
                    echo Html::tag( $name,$content,$field['options']);
                }
                elseif(isset($field['isWidget']) && $field['isWidget'] == true) {
                    if(isset($field['number_format']) && $field['number_format']) $model->{$field['name']} = number_format($model->{$field['name']},$number_format['decimals'],$number_format['dec_point'],$number_format['thousands_sep']);
                    echo $nameField::widget([
                        'model' => $model, 
                        'attribute' => "[".($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel)."]{$field['name']}",
                        'options' => $field['options'],
                        'pluginOptions' => $field['pluginOptions']
                    ]);
                }
                elseif(strpos($nameField,'DropDownList') !== false) {
                    if(isset($field['number_format']) && $field['number_format']) $model->{$field['name']} = number_format($model->{$field['name']},$number_format['decimals'],$number_format['dec_point'],$number_format['thousands_sep']);
                    echo Html::$nameField($model,"[".($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel)."]{$field['name']}",$field['optionsValue'],$field['options']);
                }
                else {
                    if(isset($field['number_format']) && $field['number_format']) $model->{$field['name']} = number_format($model->{$field['name']},$number_format['decimals'],$number_format['dec_point'],$number_format['thousands_sep']);
                    echo Html::$nameField($model,"[".($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel)."]{$field['name']}",$field['options']);
                }
                ?>
                <?php
                if(isset($model->errors[$field['name']])  ): ?>
                    <div class="error-display">
                        <?= $model->errors[$field['name']][0] ?>
                    </div>
                <?php
                endif;
                ?>
            </td>
        <?php
            // SUMAMOS LOS CAMPOS QUE SE TENGAN QUE SUMAR
            if(!$trToRepite && isset($field['sum']) && $field['sum'] === true) {

                if(isset($sums[$field['name']])) {
                    $sums[$field['name']] +=  $model->{$field['name']};
                }
                else {
                    $sums[$field['name']] = $model->{$field['name']};
                }

                //echo $sums[$field['name']];
                //echo "***".$value."***";
                //exit;
            }
            
        endforeach;
        ?>
        <td class="text-center">
            <!-- <a href="#" title="Ver" aria-label="Ver" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> -->
            <a href="javascript:;" class="delete-item-widget-formset-jogerq" pos="<?= ($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel) ?>" title="Eliminar" aria-label="Eliminar" data-pjax="0"><span class="<?= (isset($icons) && isset($icons['trash'])) ? $icons["trash"] : "glyphicon glyphicon-trash" ?>"></span></a>
        </td>
        <td class="text-center">
            <input class="td-check" pos="<?= ($trToRepite ? 'change-position-widget-formset-jogerq' : $posModel) ?>" type="checkbox">
        </td>
    </tr>
<?php
endforeach;
/* VERIFICAMOS SI SE DEBE SUMAR LOS CAMPOS DE ALGUNA COLUMNA */
if($models && $totals) {
    $sums[$field['name']] = number_format($sums[$field['name']],$number_format['decimals'],$number_format['dec_point'],$number_format['thousands_sep']);
    foreach ($fields AS $field) {
        $totals = str_replace('{'.$field['name'].'}', (isset($sums[$field['name']]) ? $sums[$field['name']] : 0) , $totals);            
    }
    
    Yii::$app->params['totalesWidgetFormSetJogerq'] = $totals;
}
?>