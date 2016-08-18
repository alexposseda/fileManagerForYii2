<?php
    namespace alexposseda\filemanager\actions;
    use Yii;
    use yii\base\Action;

    class RemoveAction extends Action{
        public function run(){
            return Yii::$app->fileManager->removeFile(Yii::$app->request->post('path'));
        }
    }