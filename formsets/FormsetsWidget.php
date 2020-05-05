<?php
namespace app\widgets\formsets;

use yii\base\Widget;
use yii\helpers\Html;

class FormsetsWidget extends Widget
{
    public $models;
    public $titulo = null;
    public $fields = null;
    public $codForeignKey;
    public $headers = null;
    public $instanceof = null;
    public $icons = null;
    public $optionsValue = null;
    public $totals = false;
    public $number_format = ['decimals' => 2 , 'dec_point' => "." , 'thousands_sep' => "," ];

    public function init()
    {
        parent::init();
        if ($this->models === null || !$this->fields) {
            return 'no existen parámetros';
        }
    }

    public function run()
    {
        return $this->render('formsets',[
            'models' => $this->models,
            'titulo' => $this->titulo,
            'fields' => $this->fields, 
            'codForeignKey' => $this->codForeignKey,
            'headers' => $this->headers,
            'icons' => $this->icons,
            'instanceof' => $this->instanceof,
            'optionsValue' => $this->optionsValue,
            'totals' => $this->totals,
            'number_format' => $this->number_format,
            'widget' => $this, 
        ]);
    }
}
?>
