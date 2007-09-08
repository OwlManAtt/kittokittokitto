<?php
/**
 * User classfile. 
 *
 * This file is part of 'Kitto_Kitto_Kitto'.
 *
 * 'Kitto_Kitto_Kitto' is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 3 of the License,
 * or (at your option) any later version.
 * 
 * 'Kitto_Kitto_Kitto' is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General
 * Public License along with 'Kitto_Kitto_Kitto'; if not,
 * write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author Nicholas 'Owl' Evans <owlmanatt@gmail.com>
 * @copyright Nicolas Evans, 2007
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package Kitto_Kitto_Kitto
 * @subpackage Core
 * @version 1.0.0
 **/

/**
 * User class.
 *
 * This provides all user-related attributes and has RELATED sets defined
 * for most things a user owns. 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class User extends ActiveTable
{
	protected $table_name = 'user';
    protected $primary_key = 'user_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'avatar_id',
            'foreign_table' => 'avatar',
            'foreign_key' => 'avatar_id',
            'join_type' => 'left',
        ),
        array(
            'local_key' => 'timezone_id',
            'foreign_table' => 'timezone',
            'foreign_key' => 'timezone_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'datetime_format_id',
            'foreign_table' => 'datetime_format',
            'foreign_key' => 'datetime_format_id',
            'join_type' => 'inner',
        ),
    );
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
        'notifications' => array(
            'class' => 'Notification',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
        ),
        'messages' => array(
            'class' => 'Message',
            'local_key' => 'user_id',
            'foreign_key' => 'recipient_user_id',
        ),
        'staff_groups' => array(
            'class' => 'User_StaffGroup',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
        ),
    );

    /**
     * Caches loaded permissions so another SQL lookup doesn't need to be done. 
     * 
     * @var array
     **/
    protected $permission_cache = array();

    /**
	 * Set the password for a user by passing in plaintext.
	 *
	 * The plaintext password is automagically converted to the
	 * appropriate format and stored. The plaintext is discarded
	 * when this function completes.
     * 
     * @param string $password 
     * @return void
     **/
	public function setPassword($password)
	{
        /*
		* Doing this twice makes the passwords a mite harder to 
		* brute-force. I do not labour under the idiotic delusion
		* that this actually improves security; quite the contrary,
		* my only goal is to keep some assclown from looking the
		* chucksum up in a rainbow table and getting 80% of the users'
        * plaintexts.
        *
        * Because you know 8/10 people chose 'kittokitto' as their password.
        *
        * :-(
        */
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
     * @param string|int $datetime A UNIX timestamp. A non-int will be
     *                      converted with strototime().
     * @return string The localized date/time.
     **/
	public function formatDate($datetime)
	{
        if(is_string($datetime)) $datetime = strtotime($datetime);
        $gmt_unix = gmdate('U',$datetime); 

        $offset = ($this->getTimezoneOffset() * (60 * 60));
        $local_unix = $gmt_unix + $offset;

        return date($this->getDatetimeFormat(),$gmt_unix);
	} // end formatdate

    /**
     * Determine if this user has a permission. 
     *
     * This works by checking a the object's cache for a permission. If it
     * is cached, a lookup is done to grab it from the DB (slow, many joins).
     * The permission is added to the cache and the cached value is returned.
     * 
     * @param string $permission The permission's name.
     * @return bool
     **/
	public function hasPermission($permission)
	{
        // Fucktarded developer protection.
        $permission = strtolower($permission);
        
        if(array_key_exists($permission,$this->permission_cache) == false)
        {
            $result = $this->db->getOne('
                SELECT 
                    count(*) AS has_permission
                FROM user
                INNER JOIN user_staff_group ON user.user_id = user_staff_group.user_id
                INNER JOIN staff_group_staff_permission ON user_staff_group.staff_group_id = staff_group_staff_permission.staff_group_id
                INNER JOIN staff_permission ON staff_group_staff_permission.staff_permission_id = staff_permission.staff_permission_id
                WHERE staff_permission.api_name = ?
                AND user.user_id = ?
            ',array($permission,$this->getUserId()));

            if(PEAR::isError($result))
            {
                throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
            }

            $this->permission_cache[$permission] = (bool)$result;
        } // end do load
        
        return $this->permission_cache[$permission];
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
        $limit = "LIMIT $start,$total";
        
        $PROPER_INVENTORY = array();
        $inventory = $this->grab('inventory','ORDER BY user_item_id',$limit);
        
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

    /**
     * Slap a notice onto a user.
     * 
     * @param string $message The message.
     * @param string $url URL fragment (slug/args/) to link to.
     * @return bool
     **/
    public function notify($message,$url)
    {
        $notice = new Notification($this->db);
        $notice->setUserId($this->getUserId());
        $notice->setNotificationDatetime($notice->sysdate());
        $notice->setNotificationText($message);
        $notice->setNotificationUrl($url);
        
        return $notice->save();
    } // end notify

    /**
     * Empty out the user's notification history in an efficient manner. 
     * 
     * Note that the SQL query *should* work on all major RDBMSes.
     *
     * @return bool 
     **/
    public function clearNotifications()
    {
        $this->db->query('DELETE FROM user_notification WHERE user_id = ?',array($this->getUserId()));

        return true;
    } // end clearNotifications

    /**
     * Return the total number of messages a user has. 
     *
     * Useful for pagination. 
     * 
     * @return integer 
     **/
    public function grabMessagesSize()
    {
        $result = $this->db->getOne("
            SELECT 
                count(*) 
            FROM user_message
            WHERE recipient_user_id = ?
        ",array($this->getUserId()));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        return $result;
    } // end grabMessagesSize
    
    /**
     * Grab all of the user's messages, or a slice of their messages. 
     * 
     * @param integer $start 
     * @param integer $end 
     * @return array An array of Message instances.
     **/
    public function grabMessages($start=null,$end=null)
    {
        if(($start === null && $end === null) == false && 
            ($start !== null && $end !== null) == false
        )
        {
            throw ArgumentError('Must specify either no arguments or both arguments.');
        } // end problem w/ args.
        
        // Translate into start,# to fetch.
        $total = $end - $start;
        $limit = "LIMIT $start,$total";
        
        return $this->grab('messages','ORDER BY user_message.sent_at DESC',$limit);
    } // end grabMessages

    /**
     * Return the URL to the image.
     * 
     * @return null|string  
     **/
    public function getAvatarUrl()
    {
        global $APP_CONFIG;

        // It's a LEFT JOIN, mind you - it could come up with nothing.
        if($this->getAvatarId() == 0)
        {
            return null;
        }
        
        return "{$APP_CONFIG['public_dir']}/resources/avatars/{$this->getAvatarImage()}";
    } // end getAvatarUrl

    /**
     * Grab the list of groups.
     *
     * This method is kinda slow. To retain its agnosticism, I didn't
     * write a query to go directly from user => group. The actual RELATED
     * entry grabs rows from user_staff_group and then loads the group for
     * each of those rows.
     * 
     * @return array An array of UserGroups. 
     **/
    public function grabStaffGroups()
    {
        $groups = $this->grab('staff_groups');

        $REAL = array();
        foreach($groups as $group)
        {
            $REAL[] = $group->grabGroup();
        } // end grouploop

        return $REAL;
    } // end grabGroups
} // end User 

?>
