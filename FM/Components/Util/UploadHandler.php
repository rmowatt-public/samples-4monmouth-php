<?php
class FM_Components_Util_UploadHandler {

	protected $_mediaDirectory;
	protected $_fileData;
	protected $_fileType = 'na';

	public function __construct($fileInfo) {
		if(array_key_exists('type', $fileInfo) && $this->_setFileType($fileInfo['type'])) {
			$this->_mediaDirectory = '/media/' . $this->_fileType . '/';
			$this->_fileData = $fileInfo;
		}
	}

	private function _setFileType($type) {
		if(stristr($type, 'image')) {
			$this->_fileType = 'image';
			return true;
		}
		if(stristr($type, 'video')) {
			$this->_fileType = 'video';
			return true;
		}
		if(stristr($type, 'pdf')) {
			$this->_fileType = 'pdf';
			return true;
		}
		return true;
	}

	public function setFolder($folder, $root = false) {
		$filename = ($root)?  $_SERVER['DOCUMENT_ROOT'] . '/' . $folder : $_SERVER['DOCUMENT_ROOT'] . $this->_mediaDirectory . $folder;
		if (!file_exists($filename)){
			mkdir($filename, 0777, true);
			if (!file_exists($filename)) {
				return false;
			}
		}
		$this->_mediaDirectory .= $folder;
		return ($root) ? $folder : $this->_mediaDirectory;
	}

	public function move() {
		$target = $_SERVER['DOCUMENT_ROOT'] . "$this->_mediaDirectory" . '/' . $this->_fileData['name'];
		//print $target;exit;
		if(move_uploaded_file($this->_fileData['tmp_name'], $target)){
			
			return true;
		}
		return false;
	}
	
	public function getPath() {
		return "$this->_mediaDirectory" . '/' . $this->_fileData['name'];
	}
}