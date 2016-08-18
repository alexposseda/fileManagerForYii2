<?php
    namespace yii\alexposseda\fileManager\actions;

    use yii\alexposseda\fileManager\FileManager;
    use yii\base\Action;
    use yii\web\UploadedFile;

    class UploadAction extends Action{
        public $uploadPath;
        public $uploadModel;
        public $sessionEnable;

        public function run(){
            $this->uploadModel->file = UploadedFile::getInstanceByName(FileManager::getInstance()->getAttributeName());
            return FileManager::getInstance()->uploadFile($this->uploadModel, $this->uploadPath, $this->sessionEnable);
        }
    }