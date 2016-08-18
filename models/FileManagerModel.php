<?php
    namespace alexposseda\filemanager\models;
    use Yii;
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

        public function init(){
            parent::init();
            $this->validationRules = [
                array_merge(
                    [[Yii::$app->fileManager->getInputName()]],
                    Yii::$app->fileManager->validationRules,
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