<?php
/**
 * Board post classfile. 
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
 * @subpackage Board
 * @version 1.0.0
 **/

/**
 * BoardPost
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class BoardPost extends ActiveTable
{
    protected $table_name = 'board_thread_post';
    protected $primary_key = 'board_thread_post_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'user_id',
            'foreign_table' => 'user',
            'foreign_key' => 'user_id',
            'join_type' => 'inner',
        ),
        array(
            'local_table' => 'user',
            'local_key' => 'avatar_id',
            'foreign_table' => 'avatar',
            'foreign_key' => 'avatar_id',
            'join_type' => 'left',
        ),
    );

    /**
     * Create a post and increment the user's postcount. 
     * 
     * @param array $POST 
     * @return bool 
     **/
    public function create($POST)
    {
        $result = parent::create($POST);
        
        $user = new User($this->db);
        $user = $user->findOneByUserId($POST['user_id']);
        
        if($user != null)
        {
            $user->setPostCount(($user->getPostCount() + 1));
            $user->setDatetimeLastPost($user->sysdate());
            $user->save();
        }

        return $result;
    } // end create

    /**
     * Delete a post and remove one from the user's postcount.
     * 
     * Since this is called in a loop from BoardThead#destroy(),
     * I wrote the update SQL so it only has to do one query 
     * instead of four.
     *
     * @ihatemysql
     * @return bool 
     **/
    public function destroy()
    {
        $result = $this->db->query('UPDATE user SET post_count = post_count - 1 WHERE user_id = ?',array($this->getUserId())); 
        
        return parent::destroy();
    } // end destroy

    /**
     * If the poster has selected an avatar, return the URL to the image.
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

} // end BoardThread

?>
