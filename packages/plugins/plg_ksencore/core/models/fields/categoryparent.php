<?php 
defined( '_JEXEC' ) or die;

JFormHelper::loadFieldClass('radio'); 
class JFormFieldCategoryParent extends JFormFieldRadio{

	protected $type = 'CategoryParent';
    private $tree=array();
	private $menu=array();
	
	public function getInput(){
		$this->buildCategoriesTree();
		if ($this->menu)
			$this->makeCategoriesTree($this->menu[0]);
		$path=$this->getPath();
		$html = '<ul>';
		if (count($this->tree)>0)
		{
			foreach($this->tree as $category){
				$checked = '';
				if ($category->selected) { 
					$checked = ' checked="checked" ';
				}
				$html.='<li class="'.$category->class.'">';
				$html.='	<label>'.$category->title;
				$html.='	 	<input type="radio" '.$checked.' value="'.$category->id.'" name="'.$this->name.'" onclick="setActiveOne(this);" />';
				if ( $category->deeper)
					$html.='	 <a href="#" class="sh '.(in_array($category->id, $path)?'hides':'show').'"></a>';
				$html.='	</label>';
				if ($category->deeper){
					$html.='<ul class="'.(in_array($category->id, $path)?'opened':'').'">';
				}	
				elseif ($category->shallower) {
					$html.='</li>';
					$html.=str_repeat('</ul></li>', $category->level_diff);
				}
				else {
					$html.='</li>';
				}
			}
		}
		else
		{
			$html.='<li>';
			$html.='<label>'.JText::_('ksm_catalog_no_categories').'</label>';
			$html.='</li>';		
		}
		$html.='</ul>';

		return $html;
	}
	
	function buildCategoriesTree() {
        $db = JFactory::getDBO();
		$cat_id=JRequest::getVar('id',0);
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ksenmart_categories')->order('ordering');
		if (!empty($cat_id))
			$query->where('id!='.$cat_id);
        $db->setQuery($query);
        $categories = $db->loadObjectList('id');
        $top_parent = (object)array(
       		'id'=>0,
       		'children'=>array()
		);
        $menu = array(0=>$top_parent);
        foreach ($categories as $id=>$category){
			if ($id==$this->value)
				$category->selected=true;
			else	
				$category->selected=false;
			if (isset($menu[$id]))
				$category->children =$menu[$id]->children;
			else
				$category->children = array();
        	$menu[$id] = $category;
			if (!isset($menu[$category->parent_id]))
			{
				$menu[$category->parent_id]=new stdClass();
				$menu[$category->parent_id]->children = array();
			}
        	$menu[$category->parent_id]->children[] = $category;
        }
        $this->menu = $menu;
    }
	
	function getPath(){
		$path=array();
		$get_path=false;
		$level=false;		
		for($k=count($this->tree)-1;$k>=0;$k--)
		{
			if ($get_path && ($this->tree[$k]->level<$level || !$level))
			{
				$path[]=$this->tree[$k]->id;
				$level=$this->tree[$k]->level;
			}
			if ($this->tree[$k]->id==$this->value)
			{
				$get_path=true;	
				$level=$this->tree[$k]->level;
			}	
			if ($level==1)
				$get_path=false;
		}
		
		return $path;
	}	

    function makeCategoriesTree($category,$level=1) {
    	if(isset($category->children) && !empty($category->children)) {
    		foreach($category->children as $child) {
				$child->level=$level;
				$child->deeper = false;
				$child->shallower = false;
				$child->level_diff = 0;	
				$child->class =$child->id==$this->value?' active':'';
				if (isset($this->tree[count($this->tree)-1])) {
					$this->tree[count($this->tree)-1]->deeper		= ($child->level > $this->tree[count($this->tree)-1]->level);
					$this->tree[count($this->tree)-1]->shallower	= ($child->level < $this->tree[count($this->tree)-1]->level);				
					$this->tree[count($this->tree)-1]->level_diff	= ($this->tree[count($this->tree)-1]->level - $child->level);
				}	
				$this->tree[]=$child;
				if (isset($this->tree[count($this->tree)-1])) {
					$this->tree[count($this->tree)-1]->deeper		= (1 > $this->tree[count($this->tree)-1]->level);
					$this->tree[count($this->tree)-1]->shallower	= (1 < $this->tree[count($this->tree)-1]->level);				
					$this->tree[count($this->tree)-1]->level_diff	= ($this->tree[count($this->tree)-1]->level - 1);
				}				
    			$this->makeCategoriesTree($this->menu[$child->id],$level+1);
    		}
    	}
    }	
	
}