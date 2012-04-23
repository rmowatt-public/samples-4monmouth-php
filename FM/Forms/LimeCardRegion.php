<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('Zend_Form_Element_Checkbox');
Zend_Loader::loadClass('FM_Components_Util_Region');
Zend_Loader::loadClass('Zend_Form_Element_Select');

class FM_Forms_LimeCardRegion extends Zend_Form {

	public function __construct($options = null, $uname = false, $email = false) {
		$options['id'] = 'limecardForm';
		parent::__construct($options);
		//$this->addElement('text', 'email', array('label'=>'search:', 'name'=>'limecardsearch', 'style'=>'width:300px;'));
		$pc = new FM_Models_FM_SearchPrimaryCategories ();
		$category = new Zend_Form_Element_Select(array('label'=>'', 'name'=>'category', 'onchange'=>'limecardselectregion(this)'));
		$mon = array();
		$mon[0] = 'All';
		foreach(FM_Components_Util_Region::getAll() as $index=>$values) {
			//$category->addMultiOption($values->getId(), ucwords(strtolower($values->getName())));
			$mon[$values->getId()] =  ucwords(strtolower($values->getName()));
		}
		$option = array (
            'Staten Island' => array(               
                    '25'     => 'All'
            ),
            /**
            'Ocean County' => array(               
                    '26'     => 'All'
            ),
            **/
           'Monmouth County' => 
                $mon
            ,
        );
        	$category->addMultiOption('', 'Select A Region');
      $category
            ->addMultiOptions(
                $option
            )
        ; 
        
        
	
		$this->addElement($category) ;
		//$this->addElement('submit', 'submit', array('value'=>'submit', 'class'=>'sub'));
	}

}