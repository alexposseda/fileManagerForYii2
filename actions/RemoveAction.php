<?php
    namespace alexposseda\filemanager\actions;
    use Yii;
    use yii\alexposseda\fileManager\FileManager;
    use yii\base\Action;

    class RemoveAction extends Action{
        public function run(){
            return FileManager::getInstance()->removeFile(Yii::$app->request->post('path'));
        }
    }