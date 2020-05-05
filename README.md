<h1 align="center">
    <br>
    widgetformset-yii2
    <hr>
</h1>

This widget allows creating a tabular form with the functionalities of entering, consulting, modifying and deleting records. Includes the necessary javascript and storage functions to be called from the controller.

## Installation

The preferred way to install this widget is by downloading it and placing the downloaded directory inside the widgets directory found in the basic application template.

Once the downloaded directory is located inside app \ widgets we proceed to make the following configuration within the config / web.php file in the components section:

'components' => [
    ...
    'formsetjogerq' => [
        'class' => 'app\widgets\formsets\controllers\WidgetformsetjogerqComponent',
    ],
    ...
 ]

 That is all to install

## Usage

In the file of the view where you want to call the widget you can use it in the following way:
```php
use app\widgets\formsets\FormsetsWidget;

// Normal select with ActiveForm & model
$form = ActiveForm::begin();

echo FormsetsWidget::widget([
    'models' => $modelsFacturacion, // Array of models to be in the form
    'titulo' => 'Facturación', // Title if you want to put
    'instanceof' => '\app\models\Facturacion', //Name of instance with name space
    'headers' => ['Fecha','Referencia','Monto Crédito','Monto Débito', 'Descripción'], // Names of Fields that you want to show
    'icons' => [
        'trash' => 'glyphicon glyphicon-trash', // Icon for image trash
    ],
    'fields' => [ // An array with all fields that you want to show
        [
            'name' => 'fecha', // Name of field
            'type' => '\kartik\date\DatePicker', // Type of field
            'isWidget' => true, // If is a widget
            'options' => [ // Options for the tag
                'placeholder' => 'Ingrese fecha','readonly'=>'readonly'
            ], 
            'pluginOptions' => [ // the plugin options
                'format' => 'yyyy-mm-dd',
                'autoclose' => true,
            ]
        ],
        ['name' => 'referencia', 'type' => 'activeTextInput','options' => ['class'=>'form-control text-right']], // You can use active helpers
        ['name' => 'monto_credito', 'type' => 'activeTextInput', 'options' => ['class'=>'form-control text-right']],
        ['name' => 'monto_debito', 'type' => 'activeTextInput','options' => ['class'=>'form-control text-right']],
        ['name' => 'descripcion', 'type' => 'activeTextArea','options' => ['class'=>'form-control']],
    ],
    'codForeignKey' => [
        'field' => 'cod_proyecto', // If in your table there is a foreing key you have to put here the name of field in the database
        'value' => 1 // You has tu put the value of that foreign key
    ],
]);

ActiveForm::end(); 

```
## License

**yii2-widget-select2** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.