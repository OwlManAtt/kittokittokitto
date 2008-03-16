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
        'notification' => array( // Use with an ORDER BY to get only one. Used for index.
            'class' => 'Notification',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
            'one' => true,
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
     * The password hash is built using a random salt + the plaintext.
     * The salt is the md5 of random data + the current time + a string 
     * that (hopefully) has *at least* one symbol that wasn't factored 
     * into a rainbow table.
     * 
     * @param string $password 
     * @return void
     **/
	public function setPassword($password)
	{
     	$salt = md5(rand(10000000,90000000).time().'isumi*+!@#|=');
        $this->setPasswordHashSalt($salt);

        $this->setPasswordHash(md5(md5($password.$salt)));
	} // end setPassword
    
    /**
     * Determine if plaintext matches the hashed password. 
     * 
     * @param string $plaintext 
     * @return bool 
     **/
    public function checkPlaintextPassword($plaintext)
    {
        $base = md5(md5($plaintext.$this->getPasswordHashSalt()));
        
        if($base == $this->getPasswordHash())
        {
            return true;
        }

        return false;
    } // end checkPassword
	
    /**
     * Determines if the salted hash sent to the client is correct. 
     * 
     * @param string $session_password 
     * @return bool 
     **/
    public function checkSessionPassword($session_password)
    {
        $base = $this->getPasswordHash();
        $salt = $this->getCurrentSalt();
        
        // If the salt is expired, tear the user down.
        if(strtotime($this->getCurrentSaltExpiration()) < time())
        {
            $this->logout();

            return false;
        } // end logout if salt expired
        
        if(md5($base.$salt) == $session_password)
        {
            return true;
        }
        
        return false;
    } // end checkSessionPassword
    
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
        
            // Generate and store the salt we're using for this session.
            $salt = md5($_SERVER['REMOTE_ADDR'].(rand(1,100000) * rand(1,1000)));
            $this->setCurrentSalt($salt);
            $this->setCurrentSaltExpiration(date('Y-m-d H:i:s',$time));
            $this->save();

            // Better password hash.
            $password = md5($password.$salt);
		} // end logging in
		else
		{
			$username = null;
			$password = null;
			$time = $_COOKIE[$APP_CONFIG['cookie_prefix'].'time'];
            
            // Rip the salt down.
            $this->setCurrentSalt('');
            $this->setCurrentSaltExpiration(0);
            $this->save();
		} // end zeroing
		
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
        
        $PROPER_INVENTORY = array();
        $inventory = $this->grab('inventory','ORDER BY user_item_id',false,$start,$end);
        
        foreach($inventory as $item)
        {
            $PROPER_INVENTORY[] = Item::factory($item->getUserItemId(),$this->db);
        } // end inventory loop
        
        return $PROPER_INVENTORY;
    } // end grabInventory

    public function grabInventorySize()
    {
        $result = new Item($this->db);
        $result = $result->findByUserId($this->getUserId(),null,true);
        
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

    /**
     * Update group permissions for the user. 
     *
     * This will delete all of the existing group mappings and re-add everything from
     * scratch. 
     * 
     * @rdbms-specific 'INSERT IGNORE INTO' probably doesn't run on Oracle stuff.
     * @param array $group_ids The array of staff_group_ids to put this user into.
     * @return bool 
     **/
    public function updateGroups($group_ids)
    {
        // Delete all is a little different.
        if(sizeof($group_ids) == 0)
        {
            $result = $this->db->query('DELETE FROM user_staff_group WHERE user_id = ?',array($this->getUserId()));
                  
            if(PEAR::isError($result))
            {
                throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
            }

            return true;
        } // end handle delete all
        
        // Handle the delete or add *some*.
        $holders = array_fill(0,(sizeof($group_ids)),'?');
        $holders = implode(',',$holders);
        
        $result = $this->db->query("
            DELETE FROM user_staff_group 
            WHERE staff_group_id NOT IN ($holders) 
            AND user_id = ?
        ",array_merge($group_ids,array($this->getUserId())));
        
        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        $result = $this->db->query("
            INSERT IGNORE INTO user_staff_group 
            (staff_group_id,user_id) 
            SELECT 
                staff_group_id, 
                ? AS user_id 
            FROM staff_group
            WHERE staff_group_id IN ($holders)
        ",array_merge(array($this->getUserId()),$group_ids));

        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        return true;
    } // end updateGroups
} // end User 

?>
