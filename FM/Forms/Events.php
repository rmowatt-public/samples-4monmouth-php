<?php

Zend_Loader::loadClass('Zend_Form');

class FM_Forms_Events extends Zend_Form
{
	public function __construct($options = array(), array $values = null)
	{
		parent::__construct($options);

		$this->setAction('/merchant')
			->setMethod('post');
		
		$datetag = $this->createElement('text', 'datetag', array(
			'label' => 'Date: ',
			'value' => (is_array($values) && array_key_exists('datetag', $values))? $values['datetag'] : ''
		));
		
		$datetag->addValidator('num')
			->addValidator('regex', false, array('/^[0-9]+/'))
			->addValidator('stringLength', false, array(8))
			->setRequired(true);
			
		$name = $this->createElement('text', 'name', array(
			'label' => 'Event Name: ',
			'value' => (is_array($values) && array_key_exists('name', $values))? $values['name'] : ''
		));
		
		$name->addValidator('alnum')
			->addValidator('regex', false, array('/^[a-z]+/'))
			->addValidator('stringLength', false, array(2, 60))
			->setRequired(true);
		
		$location = $this->createElement('text', 'location', array(
			'label' => 'Location: ',
			'value' => (is_array($values) && array_key_exists('location', $values))? $values['location'] : ''
		));
		
		$location->addValidator('alnum')
			->addValidator('regex', false, array('/^[a-z]+/'))
			->addValidator('stringLength', false, array(6, 200))
			->setRequired(true)
			->addFilter('StringToLower');
		
		$description = $this->createElement('text', 'description', array(
			'label' => 'Description: ',
			'value' => (is_array($values) && array_key_exists('description', $values))? $values['description'] : ''
		));
		
		$description->addValidator('alnum')
			->addValidator('regex', false, array('/^[a-z]+/'))
			->addValidator('stringLength', false, array(6, 500))
			->setRequired(true)
			->addFilter('StringToLower');
		
		// Add elements to form:
		$this->addElement($datetag)
			->addElement($name)
			->addElement($location)
			->addElement($description)
			->addElement('submit', 'save', array('label' => 'Save'));
	}
}
