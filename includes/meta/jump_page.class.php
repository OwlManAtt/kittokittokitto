<?php
/**
 *  
 **/

/**
 * JumpPage 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class JumpPage extends ActiveTable
{
	protected $table_name = 'jump_page';
    protected $primary_key = 'jump_page_id';

	/**
	 * Determine if if $position has rights to view this page.
	 *
	 * @param string (public|user|mod|admin)
	 * @return boolean
     * @todo replace this with something that isn't bullocks.
	 **/
	public function hasAccess($position)
	{
		$POSITION_MAP = array(
			'public' => 0,
			'user' => 20,
			'mod' => 40,
			'admin' => 60,
		);
		
		if($POSITION_MAP[$this->getAccessLevel()] <= $POSITION_MAP[$position])
		{
			return true;
		}
		
		return false;
	} // end hasAccess
	
} // end JumpPage

?>
