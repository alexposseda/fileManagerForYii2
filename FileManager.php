<?php
    namespace yii\alexposseda\fileManager;
    use yii\alexposseda\fileManager\models\FileManagerModel;
    use Yii;
    use yii\base\Exception;
    use yii\base\InvalidConfigException;
    use yii\helpers\FileHelper;

    class FileManager{
        const FORMAT_JSON   = 'json';
        const FORMAT_STRING = 'string';
        const FORMAT_BASE   = 'base';
        private static $_fileManager = null;
        private        $storagePath;
        private        $storageUrl;
        private        $attributeName;
        private        $baseValidationRules;

        private function __construct(){
            $config = Yii::$app->params['fileManager'];
            if(!$config['storagePath'] or !$config['storageUrl'] or !$config['baseValidationRules'] or !$config['attributeName']){
                throw new InvalidConfigException('Check Your file manager configuration!');
            }
            $this->storagePath = $config['storagePath'];
            $this->storageUrl = $config['storageUrl'];
            $this->attributeName = $config['attributeName'];
            $this->baseValidationRules = $config['baseValidationRules'];
        }

        public static function getInstance(){
            if(is_null(self::$_fileManager)){
                self::$_fileManager = new self();
            }

            return self::$_fileManager;
        }

        public function uploadFile(FileManagerModel $model, $targetDir, $sessionEnable = false,
                                   $format = self::FORMAT_JSON){
            if($model->validate()){
                $today = date('Y-m-d');
                $directory = $targetDir.DIRECTORY_SEPARATOR.$today;
                if(!is_dir($this->storagePath.DIRECTORY_SEPARATOR.$directory)){
                    $this->createDirectory($directory);
                }
                if($model->uploadFile($directory)
                         ->hasErrors()
                ){
                    return $this->createResponse(['error' => $model->getErrors($this->attributeName)], $format);
                }
                if($sessionEnable){
                    $this->saveToSession($model->savePath);
                }

                return $this->createResponse([
                                                 'file' => [
                                                     'storageUrl' => FileManager::getInstance()->getStorageUrl(),
                                                     'path' => $model->savePath
                                                 ]
                                             ], $format);
            }

            return $this->createResponse(['error' => $model->getErrors($this->attributeName)], $format);
        }

        public function removeFile($path, $format = self::FORMAT_JSON){
            $fullPath = $this->storagePath.DIRECTORY_SEPARATOR.$path;
            if(file_exists($fullPath)){
                if(!unlink($fullPath)){
                    return $this->createResponse(['error' => ['Can not delete file']], $format);
                }
            }
            $this->removeFromSession($path);

            return $this->createResponse(['success' => ['file removed']], $format);
        }

        public function createResponse($data, $format = self::FORMAT_JSON){
            switch($format){
                case self::FORMAT_JSON:
                    $data = json_encode($data);
                    break;
                case self::FORMAT_STRING:
                    //todo преобразовать к строке
                    break;
                case self::FORMAT_BASE:
                    break;
                default:
                    throw new Exception('Wrong fileManager response format');
            }

            return $data;
        }

        public function createDirectory($newDirectory, $mod = 0777, $recursive = true){
            FileHelper::createDirectory($this->storagePath.DIRECTORY_SEPARATOR.$newDirectory, $mod, $recursive);
        }

        protected function saveToSession($path){
            $baseDir = substr($path, 0, strpos($path, DIRECTORY_SEPARATOR));
            $session = Yii::$app->session->get('uploadedFiles');
            if(!is_array($session)){
                $session = [];
            }
            $session[$baseDir][] = $path;
            Yii::$app->session->set('uploadedFiles', $session);
        }

        protected function removeFromSession($path){
            $baseDir = substr($path, 0, strpos($path, DIRECTORY_SEPARATOR));
            $session = Yii::$app->session->get('uploadedFiles');
            if(!is_array($session)){
                $session = [];
            }else{
                if(is_array($session[$baseDir])){
                    foreach($session[$baseDir] as $index => $pathToFile){
                        if($path == $pathToFile){
                            array_splice($session[$baseDir], $index, 1);
                        }
                    }
                }
            }
            Yii::$app->session->set('uploadedFiles', $session);
        }

        public function getStoragePath(){
            return $this->storagePath.DIRECTORY_SEPARATOR;
        }

        public function getStorageUrl(){
            return $this->storageUrl;
        }

        public function getAttributeName(){
            return $this->attributeName;
        }

        public function getBaseValidationRules(){
            return $this->baseValidationRules;
        }
    }