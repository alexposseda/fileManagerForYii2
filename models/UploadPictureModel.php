<?php
    namespace alexposseda\filemanager\models;
    use Yii;

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
            $this->savePath = $directory.$fileName.'.'.$this->file->extension;
            if(!$this->file->saveAs(Yii::$app->fileManager->storagePath.$this->savePath)){
                $this->addError('file', 'Upload failed');
            }

            return $this;
        }
    }