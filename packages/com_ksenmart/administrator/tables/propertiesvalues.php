<?php 
defined( '_JEXEC' ) or die;

if (!class_exists('KsenmartTable')){
	require JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' .DS.'ksenmart.php' ;
}

class KsenmartTablePropertiesValues extends KsenmartTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	function __construct(&$_db)
	{
		parent::__construct('#__ksenmart_property_values', 'id', $_db);
		//$date = JFactory::getDate();
		
	}


	function bind($src, $ignore=array()){
		return parent::bind($src, $ignore);
	}
	
	
	
}
