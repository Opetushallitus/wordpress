<?php

class C_Pricelist_Item_Mapper extends C_CustomPost_DataMapper_Driver
{
	public static $_instances = array();

	function define($context=FALSE)
	{
		$object_name = 'ngg_pricelist_item';

		// Add the object name to the context of the object as well
		// This allows us to adapt the driver itself, if required
		if (!is_array($context)) $context = array($context);
		array_push($context, $object_name);
		parent::define(NULL, $context);

		$this->set_model_factory_method($object_name);

		// Define columns
		$this->define_column('pricelist_id', 'BIGINT', 0);
		$this->define_column('amount', 'DECIMAL', 0.00);
	}

	function initialize($context=FALSE)
	{
		parent::initialize('ngg_pricelist_item');
	}
}