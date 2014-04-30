<?php

class C_Pricelist extends C_DataMapper_Model
{
	var $_mapper_interface = 'I_Pricelist_Mapper';

	function define($mapper, $properties, $context=FALSE)
	{
		parent::define($mapper, $properties, $context);
		$this->implement('I_Pricelist');
	}

	function get_items()
	{
		$mapper = C_Pricelist_Item_Mapper::get_instance();
		return $mapper->find_all(array("pricelist_id = %d", $this->object->id()));
	}
}