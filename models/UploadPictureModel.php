<?php
    namespace yii\alexposseda\fileManager\models;

    use yii\alexposseda\fileManager\FileManager;

    class UploadPictureModel extends FileManagerModel{
        public function rules(){
            return $this->validationRules;
        }

        /**
         * @param $directory
         *
         * @return $this
         */
        public function uploadFile($directory){
            $fileName = uniqid(time(), true);
            $this->savePath = $directory.DIRECTORY_SEPARATOR.$fileName.'.'.$this->file->extension;
            if(!$this->file->saveAs(FileManager::getInstance()->getStoragePath().$this->savePath)){
                $this->addError(FileManager::getInstance()->getAttributeName(), 'Upload failed');
            }

            return $this;
        }
    }