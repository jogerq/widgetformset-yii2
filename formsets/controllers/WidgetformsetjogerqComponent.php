<?php
namespace app\widgets\formsets\controllers;

use yii; 
use yii\base\Component;

class WidgetformsetjogerqComponent extends Component {
	

	public function getModels($model,array $foreignkeys = []) {
		
		$postFacturaEvaluar = [];
		$name = explode("\\", $model);
        $className = end($name);
		if(!Yii::$app->request->isPost) { //Yii::$app->request->post($className)) {
            $modelsFacturacion = $model::find()->where($foreignkeys)->all();
            if(!$modelsFacturacion) $modelsFacturacion = [new $model()];
        }
        else {

        	$modeDB =  new $model();
        	$primaryKey = isset($modeDB->tableSchema->primaryKey[0]) ? $modeDB->tableSchema->primaryKey[0] : null;
        	$i = 0;
            $modelsFacturacion = [];
            foreach(Yii::$app->request->post($className,[]) AS $post) {
                if(isset($post[$primaryKey]) && $post[$primaryKey]) {
                    $models[$i] = $model::findOne($post[$primaryKey]);
                    unset($post[$primaryKey]);
                    $postFacturaEvaluar[$className][] = $post;
                    $modelsFacturacion[$i] = new $model();
                }
                else {
                    $modelsFacturacion[$i] = new $model();
                    $postFacturaEvaluar[$className][] = $post;
                }
                $i++;
            }
        }

        return [$modelsFacturacion,$postFacturaEvaluar];
	}


	public function saveModels($modelsFacturacion,$table,$campo,$valorForeingKey,$primaryKey='id') {
		$ids = [];
        foreach ($modelsFacturacion as $model) {
            $model->save(false);
            $ids[] = $model->id;
        }
        //we delete the records
        $and = '';
        $and = $valorForeingKey || $campo ? " WHERE $campo=$valorForeingKey " : '';
        $and = ($ids && $and) ? $and." AND $primaryKey NOT IN (".implode(',',$ids).")" : ((!$and && $ids) ? " WHERE $primaryKey NOT IN (".implode(',',$ids).")" : '');
        Yii::$app->db->createCommand("DELETE FROM $table $and")->execute();
	}
}

