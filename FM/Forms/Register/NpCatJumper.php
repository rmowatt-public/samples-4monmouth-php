<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Models_FM_SearchPrimaryCategoriesOrgs');

class FM_Forms_Register_NpCatJumper extends Zend_Form {

	public function __construct($options = array()) {
		//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
		parent::__construct($options);

		$pc = new FM_Models_FM_SearchPrimaryCategoriesOrgs ();
		$category = new Zend_Form_Element_Select(array('label'=>'category', 'name'=>'categoryjumper', 'required'=>1,  'onChange'=>"MM_jumpMenu('parent',this,1)"));
		$category->addMultiOption('/root/addnp/0', 'select a category*');
		$category->addMultiOption('/root/addnp/0', 'All');
		foreach($pc->getRootCategories() as $index=>$values) {
			$category->addMultiOption('/root/addnp/'.$values['id'], strtolower($values['name']));
		}
		$this->addElement($category) ;
	}
}