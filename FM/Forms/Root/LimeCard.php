<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Forms_Element_File');
Zend_Loader::loadClass('Zend_Form_Element_Text');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategories');


class FM_Forms_Root_LimeCard extends Zend_Form {

	public function __construct($options = null) {
		parent::__construct($options);
		$this->addElement('hidden', 'id', array('name'=>'id', 'value'=>0));
		$pc = new FM_Models_FM_SearchPrimaryCategories ();
		$category = new Zend_Form_Element_Select(array('label'=>'category', 'name'=>'catId'));
		foreach($pc->getRootCategories() as $index=>$values) {
			$category->addMultiOption($values['id'], $values['name']);
		}
		$regions = new Zend_Form_Element_Select(array('label'=>'region', 'name'=>'region'));
		$regions->addMultiOption(0, 'Select A Region');
		$regions->addMultiOption(25, 'Staten Island');
		//$regions->addMultiOption(26, 'Ocean County');
		foreach(FM_Components_Util_Region::getAll() as $index=>$values) {
			$regions->addMultiOption($values->getId(), ucwords(strtolower($values->getName())));
		}
		$this->addElement($category) ;
		$this->addElement($regions) ;
		$this->addElement('text', 'name', array('label'=>'name :', 'name'=>'name', 'required'=>1));
		$this->addElement('text', 'address', array('label'=>'address :', 'name'=>'address', 'required'=>0));
		$this->addElement('text', 'city', array('label'=>'city :', 'name'=>'city', 'required'=>0));
		$this->addElement('text', 'state', array('label'=>'state :', 'name'=>'state', 'required'=>0));
		$this->addElement('text', 'zip', array('label'=>'zip code :', 'name'=>'zip', 'required'=>0));
		$this->addElement('text', 'phone', array('label'=>'phone :', 'name'=>'phone', 'required'=>0));
		$this->addElement('text', 'website', array('label'=>'website :', 'name'=>'website', 'required'=>0));
		$this->addElement('textarea', 'limits', array('label'=>'limits :', 'name'=>'limits', 'required'=>0));
		$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));

	}
}