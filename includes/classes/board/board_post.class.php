<?php
/**
 *  
 **/

/**
 * BoardPost
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Board
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v3 {@link http://www.gnu.org/licenses/gpl-3.0.txt}
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
