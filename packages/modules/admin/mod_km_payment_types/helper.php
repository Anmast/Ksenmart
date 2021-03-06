<?php defined('_JEXEC') or die('Restricted access');

class ModKMPaymentTypesHelper {

    public static function getPaymentTypes()
	{
		$app = JFactory::getApplication();
        $db = JFactory::getDBO();
		$view=JRequest::getVar('view','payments');
		$context = 'com_ksenmart.'.$view;
		if ($layout = JRequest::getVar('layout','default')) {
			$context.='.'.$layout;
		}		

		$selected_types=$app->getUserStateFromRequest($context . '.types', 'types', array());	
		$query=$db->getQuery(true);
		$query->select('name,element')->from('#__extensions')->where('folder="kmpayment"')->where('enabled=1');
		$db->setQuery($query);
		$types=$db->loadObjectList('element');
		foreach($types as &$type)
			if (in_array($type->element,$selected_types))
				$type->selected=true;
			else	
				$type->selected=false;			
        
        return $types;
    }
}