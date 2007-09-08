<?php
/**
 * Private message classfile. 
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
 * @subpackage Messages
 * @version 1.0.0
 **/

/**
 * Message 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Messages
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class Message extends ActiveTable
{
    protected $table_name = 'user_message';
    protected $primary_key = 'user_message_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'sender_user_id',
            'foreign_table' => 'user',
            'foreign_table_alias' => 'sender',
            'foreign_key' => 'user_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'recipient_user_id',
            'foreign_table' => 'user',
            'foreign_table_alias' => 'recipient',
            'foreign_key' => 'user_id',
            'join_type' => 'inner',
        ),
    );

    /**
     * Delete a user's messages. 
     *
     * This is implemented in an efficient manner, so when someone 
     * wants to delete a page of 25 messages, it isn't dogass-slow.
     * 
     * @param integer $user_id 
     * @param array $MESSAGES Message IDs.
     * @param object $db PEAR::DB connector.
     * @return bool
     **/
    static public function deleteBulk($user_id,$MESSAGES,$db)
    {
        $values = array($user_id);
        $values = array_merge($values,array_unique($MESSAGES));

        $holders = array_fill(0,(sizeof($MESSAGES)),'?');
        $holders = implode(',',$holders);

        $result = $db->query("
            DELETE 
            FROM user_message 
            WHERE recipient_user_id = ? 
            AND user_message_id IN ($holders)
        ",$values);
        
        if(PEAR::isError($result))
        {
            throw new SQLError($result->getDebugInfo(),$result->userinfo,10);
        }
        
        return true;
    } // end deleteBulk
    
    public function getRecipientUserId()
    {
        return $this->get('user_id','recipient');
    } // end getRecipientUserId

    public function getRecipientUserName()
    {
        return $this->get('user_name','recipient');
    } // end getRecipientUserName
    
    public function getSenderUserId()
    {
        return $this->get('user_id','sender');
    } // end getSenderUserId
    
    public function getSenderUserName()
    {
        return $this->get('user_name','sender');
    } // end getSenderUserName

    public function getSenderSignature()
    {
        return $this->get('signature','sender');
    } // end getSenderSignature

    /**
     * Returns an array of id/username pairs. 
     * 
     * @return array 'user_id' => 'user_name'
     **/
    public function getRecipientList()
    {
        $list = $this->get('recipient_list');
        $list = explode(',',$list);

        $RESULT = array();
        foreach($list as $tuple)
        {
            $tuple = explode(':',$tuple);
            $RESULT[$tuple[0]] = $tuple[1];
        } // end list loop
        
        return $RESULT;
    } // end getRecipientList

    /**
     * Serializes the recipient list for storage in the DB.
     *
     * === Implementation Note:
     * The reason I have elected to store the recipients as a
     * big string in the user_message table instead of normalizing
     * this is for performance reasons. Adding five records for each
     * of five messages sent out in a mass-mailing would quickly add
     * up to a meteric fuckton of rows in that table, making queries
     * against it sloooow. 
     *
     * If you ever need to report out on details of PMs like that, you
     * will need to normalize this. However, since I'm just intending to
     * use it for display purposes, I am content. 
     * 
     * @param array $list ( 'user_id' => 'user_name', )
     * @return bool
     **/
    public function setRecipientList($list)
    {
        if(is_array($list) == false)
        {
            throw new ArgumentError('Recipient list must be an array.');
        }

        $compressed = array();
        foreach($list as $user_id => $user_name)
        {
            $compressed[] = "$user_id:$user_name";
        }
        $compressed = implode(',',$compressed);

        return $this->set($compressed,'recipient_list');
    } // end setRecipientList 
} // end Message

?>
