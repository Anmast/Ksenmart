<?php defined('_JEXEC') or die;

class ModKMCurenciesRatesHelper {
	
	public static function getCurrencies() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__ksenmart_currencies')->where('`default`=0');
		$db->setQuery($query);
		$currencies = $db->loadObjectList();
		
		return $currencies;
	}
}