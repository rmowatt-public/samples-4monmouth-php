<?php
Zend_Loader::loadClass('Zend_Form');
Zend_Loader::loadClass('Zend_Form_Element_Select');
Zend_Loader::loadClass('FM_Components_Util_State');
Zend_Loader::loadClass('FM_Components_Util_Town');
Zend_Loader::loadClass('FM_Forms_Element_File');
Zend_Loader::loadClass('FM_Components_Util_Region');
Zend_Loader::loadClass('Zend_Form_Element_Multiselect');
//Zend_Loader::loadClass('FM_Forms_Element_Textarea');
//Zend_Loader::loadClass('FM_Forms_Element_Hidden');

class FM_Forms_Register_Base extends Zend_Form {

	public function __construct($options = null, $users = array()) {
		parent::__construct($options);
		$this->addElementPrefixPath('FM', 'FM/');
		$states = FM_Components_Util_State::getAll();
		$towns = FM_Components_Util_Town::getAll();
		$allRegions = FM_Components_Util_Region::getAll();

		$allUsers = new Zend_Form_Element_Select(array('label'=>'Administrator :', 'name'=>'admin', 'required'=>1));
		$allUsers->addMultiOption(35, ' Carmine (minodef@aol.com)');
		foreach($users as $user) {
			if($user->getId() != 35) {
				$allUsers->addMultiOption($user->getId(), $user->getUserName() . ' (' . $user->getEmail() . ')');
			}
		}
		$allUsers->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/users.phtml',
		'class'      => 'form element'
		))));
		$allUsers->setRegisterInArrayValidator(false);
		$this->addElement($allUsers);

		$this->addElement('text', 'name', array('label'=>'name :', 'name'=>'name', 'required'=>1));
		$this->addElement('text', 'website', array('label'=>'website :', 'name'=>'website', 'required'=>0));
		$this->addElement('text', 'address1', array('label'=>'address 1 :', 'name'=>'address1', 'required'=>0));
		$this->addElement('text', 'address2', array('label'=>'address 2 :', 'name'=>'address2'));
		$this->addElement('text', 'city', array('label'=>'city :', 'name'=>'city', 'required'=>0));
		$state = new Zend_Form_Element_Select(array('label'=>'state :', 'name'=>'state', 'required'=>1));
		foreach($states as $key=>$stateObj) {
			$state->addMultiOption($stateObj->getAbbr(), $stateObj->getState());
		}
		$state->setValue('NJ');
		$this->addElement($state);

		$town = new Zend_Form_Element_Multiselect(array('label'=>'town :', 'name'=>'town', 'required'=>1, 'onchange'=>"FM.Utilities.setRegion(this.value)"));
		foreach($towns as $key=>$townObj) {
			$town->addMultiOption($townObj->getId(), $townObj->getName());
		}
		$this->addElement($town);

		$regions = new Zend_Form_Element_Select(array('label'=>'Region :', 'name'=>'region'));
		//foreach($users as $user) {
		//$regions->addMultiOption($user->getId(), $user->getUserName() . ' (' . $user->getEmail() . ')');
		//}
		$regions->setDecorators(array(array('ViewScript', array(
		'viewScript' => 'form/regions.phtml',
		'class'      => 'form element',
		'regions' =>$allRegions
		))));
		$regions->setRegisterInArrayValidator(false);
		$this->addElement($regions);

		$this->addElement('text', 'zip', array('label'=>'zip :', 'name'=>'zip', 'required'=>0));
		$this->addElement('text', 'phone', array('label'=>'phone :', 'name'=>'phone'));
		$this->addElement('text', 'email', array('label'=>'email :', 'name'=>'email', 'required'=>0));
		$maillist = new Zend_Form_Element_Select(array('label'=>'mailing list? :', 'name'=>'maillist'));
		$maillist->addMultiOption('1', ' Yes! ');
		$maillist->addMultiOption('0', ' No ');
		$this->addElement($maillist);
		
		$limeCard = new Zend_Form_Element_Select(array('label'=>'lime card? :', 'name'=>'limeCard'));
		$limeCard->addMultiOption('0', ' No ');
		$limeCard->addMultiOption('1', ' Yes! ');
		$this->addElement($limeCard);
		
		$this->addElement('text', 'slug', array('label'=>'direct link : ', 'name'=>'slug', 'onblur'=>'FM.Client.checkSlug(this)'));
		$this->addElement('textarea', 'description', array('label'=>'description :', 'name'=>'description', 'id'=>'descriptionr', 'rows'=>3));

		$this->addDisplayGroup(array('admin', 'slug', 'limeCard'), 'admingroup');
		$this->addDisplayGroup(array('name','website','address1', 'address2'), 'bizinfo');
		$this->addDisplayGroup(array('city','state','town', 'region', 'zip'), 'locationinfo');
		$this->addDisplayGroup(array('phone','email', 'maillist'), 'contactinfo');

		$admingroup = $this->getDisplayGroup('admingroup');
		$bizinfo = $this->getDisplayGroup('bizinfo');
		$locationinfo = $this->getDisplayGroup('locationinfo');
		$contactinfo = $this->getDisplayGroup('contactinfo');

		$admingroup->setDecorators(array(
		'FormElements',
		array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
		'Fieldset'
		));
		$bizinfo->setDecorators(array(
		'FormElements',
		array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
		'Fieldset'
		));
		$contactinfo->setDecorators(array(
		'FormElements',
		array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
		'Fieldset'
		));
		$locationinfo->setDecorators(array(
		'FormElements',
		array('HtmlTag',array('tag'=>'h4','placement' => 'prepend')),
		'Fieldset'
		));
	}
	//$options['onsubmit'] = 'FM.AskUs.submitRequest();return false;';
}