<?php

define('NGG_PRICE_LIST_TYPE_MANUAL', 'manual');

class C_Pricelist_Mapper extends C_CustomPost_DataMapper_Driver
{
	public static $_instances = array();

	static function get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			$klass = get_class();
			self::$_instances[$context] = new $klass($context);
		}
		return self::$_instances[$context];
	}

	function define($context=FALSE)
	{
		$object_name = 'ngg_pricelist';

		// Add the object name to the context of the object as well
		// This allows us to adapt the driver itself, if required
		if (!is_array($context)) $context = array($context);
		array_push($context, $object_name);
		parent::define(NULL, $context);

		$this->set_model_factory_method($object_name);

		// Define columns
		$this->define_column('pricelist_type', 'VARCHAR(255)', NGG_PRICE_LIST_TYPE_MANUAL);
	}

	function initialize($context=FALSE)
	{
		parent::initialize('ngg_pricelist');
	}

	function find_for_gallery($id, $model=TRUE)
	{
		$retval = NULL;

		if (is_object($id)) {
			$id = $id->{$id->id_field};
		}

		$mapper = C_Gallery_Mapper::get_instance();
		if (($gallery = $mapper->find($id))) {
			$retval = $this->object->find($gallery->pricelist_id, $model);
		}
		return $retval;
	}

	function find_for_image($id, $model)
	{
		$retval = NULL;

		if (is_object($id)) {
			$id = $id->{$id->id_field};
		}

		$mapper = C_Image_Mapper::get_instance();
		if (($image = $mapper->find($id))) {
			if ($image->pricelist_id) {
				$retval = $this->object->find($image->pricelist_id, $model);
			}
			else $retval = $this->find_for_gallery($image->galleryid, $model);
		}

		return $retval;
	}
}