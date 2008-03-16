<?php
/**
 * Thread classfile. 
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
 * BoardThread 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 **/
class BoardThread extends ActiveTable
{
    protected $table_name = 'board_thread';
    protected $primary_key = 'board_thread_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'board_id',
            'foreign_table' => 'board',
            'foreign_key' => 'board_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'user_id',
            'foreign_table' => 'user',
            'foreign_key' => 'user_id',
            'join_type' => 'inner',
        ),
    );
    protected $RELATED = array(
        'posts' => array(
            'class' => 'BoardPost',
            'local_key' => 'board_thread_id',
            'foreign_key' => 'board_thread_id',
        ), 
    );
    
    /**
     * Determine if a thread is 'stuck'.
     * 
     * To make ORDER BY work properly for stickies (and without
     * having to do a seperate query), stickied = 0 for stuck and 
     * stickied = 1 for not stuck. Since that is pretty counter-
     * intuitive, this function translates it into stuck, true or 
     * false.
     *
     * @return bool 
     **/
    public function getStickied()
    {
        if($this->get('stickied') == 0)
        {
            return true; 
        }
        
        return false;
    } // end getStickied

    /**
     * Similarly to getStickied, this abstracts the weirdness. 
     * 
     * @param bool $value 
     * @return bool
     **/
    public function setStickied($value)
    {
        $real = null;
        if($value == true)
        {
            $real = 0;
        }   
        else
        {
            $real = 1;
        }

        return $this->set($real,'stickied');
    } // end setStickied

    /**
     * Delete a thread and its associated posts.
     * 
     * @return bool 
     **/
    public function destroy()
    {
        $posts = $this->grabPosts();
        foreach($posts as $post)
        {
            $post->destroy();  
        } 

        return parent::destroy();
    } // end destroy
} // end BoardThread

?>
