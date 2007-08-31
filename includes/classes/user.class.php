<?php
/**
 *  
 **/

/**
 * User 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class User extends ActiveTable
{
	protected $table_name = 'user';
    protected $primary_key = 'user_id';
    protected $RELATED = array(
        'pets' => array(
            'class' => 'Pet',
            'local_table' => 'user',
            'local_key' => 'user_id',
            'foreign_table' => 'user_pet',
            'foreign_key' => 'user_id',
            'foreign_primary_key' => 'user_pet_id',
        ),
        'active_pet' => array(
            'class' => 'Pet',
            'local_table' => 'user',
            'local_key' => 'active_user_pet_id',
            'foreign_table' => 'user_pet',
            'foreign_key' => 'user_pet_id',
            'foreign_primary_key' => 'user_pet_id',
            'one' => true,
        ),
        'inventory' => array(
            'class' => 'Item',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
        ),
    );

    /**
	 * Set the password for a user by passing in plaintext.
	 *
	 * The plaintext password is automagically converted to the
	 * appropriate format and stored. The plaintext is discarded
	 * when this function completes.
     * 
     * @param mixed $password 
     * @return void
     **/
	public function setPassword($password)
	{
		// Doing this twice makes the passwords a mite harder to 
		// brute-force. I do not labour under the idiotic delusion
		// that this actually improves security; quite the contrary,
		// my only goal is to keep some faggot from looking the
		// chucksum up in a rainbow table.
		$this->setPasswordHash(md5(md5($password)));
	} // end setPassword
	
	/**
	 * Log in as this user.
	 *
	 * @param integer The duration the cookie should exist for.
	 * @return void
	 **/
	public function login($cookie_duration=2592000)
	{
		global $APP_CONFIG;

		if($cookie_duration > 0)
		{
			$username = $this->getUserName();
			$password = $this->getPasswordHash();
			$time = time() + $cookie_duration;
		}
		else
		{
			$username = null;
			$password = null;
			$time = $_COOKIE[$APP_CONFIG['cookie_prefix'].'time'];
		}
		
		setcookie("{$APP_CONFIG['cookie_prefix']}username",$username,$time,'/');
		setcookie("{$APP_CONFIG['cookie_prefix']}hash",$password,$time,'/');
		setcookie("{$APP_CONFIG['cookie_prefix']}time",$time,$time,'/');
		
		return null;
	} // end login
	
	/**
	 * Tear down a users' session.
	 *
	 * @return void
	 **/
	public function logout()
	{
		$this->login(-1);
	} // end logout
	
    /**
     * Format and localize a timestamp for displaying to this user.
     * 
     * @param mixed $datetime 
     * @return string The localized date/time.
     * @todo replace it with something that isn't bullocks.
     **/
	public function formatDate($datetime)
	{
		return $datetime;
	} // end formatdate
	
    /**
     * Determine if this user has a permission. 
     * 
     * @param string $permission The permission's name.
     * @return bool
     * @todo replace it with something that isn't bollocks.
     **/
	public function hasPermission($permission)
	{
		$has = false;
		
		switch($permission)
		{
			case 'forum_mod':
			{
				if(in_array($this->getAccessLevel(),array('mod','admin')) == true)
				{
					$has = true;
				}
				
				break;
			} // end forum_mod
		} // end permission switch
		
		return $has;
	} // end hasPermission

    /**
     * Overrides the standard grabInventory and performs the factory call.
     * 
     * This is (by far) the least-effective method in this application. 
     * It loads up all of the Item objects in the efficient manner that
     * ActiveTable does such things, then completely throws that benefit 
     * to the wind by re-creating the Items one-by-one from scratch.
     *
     * I may be able to do something with ActiveTable#setUp() to make
     * it more efficient, but I really do not think it is worth my time.
     * This is a template app, so since I'll never seriously run it, I'll
     * let this little issue of scalability be your problem! Neener-neener!
     *
     * Hopefully, paginating any pages showing the whole inventory will
     * mitigate the problem. But, this is still SLOW (~150 items = 5s, 
     * p3 833mhz w/ 128mb RAM & no APC).
     *
     * == Notes on the parms...
     * These are optional. Specify neither or both. They will put LIMITs
     * on the recordset, so this can be whittled down to just a slice of 
     * the user's whole inventory. Very good for pagination.
     *
     * @param integer $start The beginning of a slice in the recordset.
     * @param integer $end The end of a slice in the recordset.
     * @return array A list of *_Item instances. 
     **/
    public function grabInventory($start=null,$end=null)
    {
        if(($start === null && $end === null) == false && 
            ($start !== null && $end !== null) == false
        )
        {
            throw ArgumentError('Must specify either no arguments or both arguments.');
        } // end problem w/ args.
        
        // Translate into start,# to fetch.
        $total = $end - $start;
        $order_by = "LIMIT $start,$total";
        
        $PROPER_INVENTORY = array();
        $inventory = $this->grab('inventory',$order_by);
        
        foreach($inventory as $item)
        {
            $PROPER_INVENTORY[] = Item::factory($item->getUserItemId(),$this->db);
        } // end inventory loop
        
        return $PROPER_INVENTORY;
    } // end grabInventory

    public function grabInventorySize()
    {
        // Some aliases to try and keep the query from looking like total
        // shit.
        $l_key = $this->RELATED['inventory']['local_key'];
        $f_table = $this->RELATED['inventory']['foreign_table'];
        $f_fk = $this->RELATED['inventory']['foreign_key'];
        
        $result = $this->db->getOne("
            SELECT 
                count(*) 
            FROM $f_table 
            INNER JOIN {$this->table_name} ON {$f_table}.{$f_fk} = {$this->table_name}.{$l_key}
            WHERE {$this->table_name}.user_id = ?
        ",array($this->getUserId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,101);
        }
        
        return $result;
    } // end grabInventorySize

    /**
     * Add some money to the user. 
     * 
     * @param integer $amount The amount to add.
     * @return bool 
     **/
    public function addCurrency($amount) 
    {
        // Force-cast to an int, just to be safe.
        $amount = (integer)$amount;
        $this->setCurrency(($this->getCurrency() + $amount));

        return $this->save();
    } // end addCurrency

    /**
     * Remove some money from the user.
     *
     * The user.currency field is an unsigned int, sothe database
     * should prevent the amount from going negative. As such, the
     * code will not worry about it.
     * 
     * @param integer $amount The amount to remove.
     * @return bool
     **/
    public function subtractCurrency($amount)
    {
        // Force-cast to an int, just to be safe.
        $amount = (integer)$amount;
        
        return $this->addCurrency("-$amount");
    } // end subtractCurrency
    
} // end User 

?>
