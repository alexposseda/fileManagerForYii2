<?php
    namespace yii\alexposseda\fileManager\models;

    use yii\alexposseda\fileManager\FileManager;
    use yii\base\Model;

    /**
     * Class FileManagerModel
     * @package alexposseda\filemanager\models
     *
     */
    abstract class FileManagerModel extends Model{
        /**
         * @var \yii\web\UploadedFile
         */
        public $file;
        public $savePath;
        public $validationRules;
        public $type = 'image';

        public function init(){
            parent::init();
            $this->validationRules = [
                array_merge(
                    [[FileManager::getInstance()->getAttributeName()]],
                    FileManager::getInstance()->getBaseValidationRules(),
                    $this->validationRules
                )
            ];
        }
        /**
         * @param $directory
         *
         * @return FileManagerModel
         */
        abstract public function uploadFile($directory);
    }