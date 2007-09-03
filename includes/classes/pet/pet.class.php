<?php
/**
 *  
 **/

/**
 * Pet 
 * 
 * @uses ActiveTable
 * @package Kitto_Kitto_Kitto
 * @subpackage Core 
 * @copyright 2007 Nicholas Evans
 * @author Nick 'Owl' Evans <owlmanatt@gmail> 
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class Pet extends ActiveTable
{
    protected $table_name = 'user_pet';
    protected $primary_key = 'user_pet_id';
    protected $LOOKUPS = array(
        array(
            'local_key' => 'pet_specie_id', 
            'foreign_table' => 'pet_specie',
            'foreign_key' => 'pet_specie_id',
            'join_type' => 'inner',
        ),
        array(
            'local_key' => 'pet_specie_color_id', 
            'foreign_table' => 'pet_specie_color',
            'foreign_key' => 'pet_specie_color_id',
            'join_type' => 'inner',
        ),
    );
    protected $RELATED = array(
        'user' => array(
            'class' => 'User',
            'local_key' => 'user_id',
            'foreign_key' => 'user_id',
            'one' => true,
        ),
    );

    /**
     * Get the full URL for this pet's image.
     * 
     * @return string 
     **/
    public function getImageUrl()
    {
        global $APP_CONFIG;
        
        return "{$APP_CONFIG['public_dir']}/resources/pets/{$this->getRelativeImageDir()}/{$this->getColorImg()}";
    } // end getImageUrl

    /**
     * Make this its owner's active pet.
     * 
     * @return bool 
     **/
    public function makeActive()
    {
        $user = $this->grabUser();

        // Should never happen...
        if($user == null)
        {
            return false;
        }

        $user->setActiveUserPetId($this->getUserPetId());
        $user->save();
        
        return true;
    } // end public function makeActive

    /**
     * Add the appropriate amount to the pet's hunger level. 
     * 
     * @param integer $amount 
     * @return bool 
     **/
    public function consume($amount)
    {
        $hunger = $this->getHunger() + $amount;
        if($hunger > $this->getMaxHunger())
        {
            $hunger = $this->getMaxHunger();
        }
        
        $this->setHunger($hunger);

        return $this->save();
    } // end consume

    /**
     * Add the appropriate amount to the pet's happiness level. 
     * 
     * @param integer $amount 
     * @return bool 
     **/
    public function play($amount)
    {
        $happy = $this->getHappiness() + $amount;
        if($happy > $this->getMaxHappiness())
        {
            $happy = $this->getMaxHappiness();
        }
        
        $this->setHappiness($happy);

        return $this->save();
    } // end consume

    /**
     * Hackishly return the hunger level as a string. 
     * 
     * @hack
     * @return string 
     **/
    public function getHungerText()
    {
        $hunger = $this->getHunger();

        if($hunger <= 2) return 'starving';
        elseif($hunger > 2 && $hunger <= 4) return 'pretty hungry';
        elseif($hunger > 4 && $hunger <= 6) return 'a bit hungry';
        elseif($hunger > 6 && $hunger <= 8) return 'content';
        elseif($hunger > 8 && $hunger <= 12) return 'full';
        elseif($hunger > 12) return 'gorged';
        
        return 'error!';
    } // end getHungerText

    /**
     * Hackishly return the happiness level as a string. 
     * 
     * @hack
     * @return string 
     **/
    public function getHappinessText()
    {
        $happy = $this->getHappiness();

        if($happy <= 2) return 'angry';
        elseif($happy > 2 && $happy <= 4) return 'sad';
        elseif($happy > 4 && $happy <= 6) return 'malcontent';
        elseif($happy > 6 && $happy <= 8) return 'neutral';
        elseif($happy > 8 && $happy <= 12) return 'happy';
        elseif($happy > 12) return 'elated';
        
        return 'error!';
    } // end getHungerText

    /**
     * If it's time for the pet's attribute to drop, do it. 
     * 
     * @param integer $period_length The number of seconds that equals 
     *                               one period.
     * @return bool
     **/
    public function doDecrement($period_length=3600)
    {
        // It is time.
        if($this->getUnixtimeNextDecrement() <= time())
        {
            // First time.
            if($this->getUnixtimeNextDecrement() == 0)
            {
                $interval = 1;
            }
            else
            {
                $interval = time() - $this->getUnixtimeNextDecrement();
                $interval = floor($interval / $period_length);
            }

            return $this->decrementAttributes($interval,(time() + $period_length));
        } // end time check
        
        // Nope, not time.
        return false;
    } // end doDecrement

    /**
     * Decrement a pet's hunger/happiness level by rand(1,3).
     * 
     * @param int $periods The number of periods to decrement for.
     * @param int $next_time The next time to perform decremention at.
     * @return bool
     **/
    protected function decrementAttributes($periods,$next_time)
    {
        // The DB should protected against negative values.
        $this->setHunger(($this->getHunger() - (rand(1,3) * $periods)));
        $this->setHappiness(($this->getHappiness() - (rand(1,3) * $periods)));
        $this->setUnixtimeNextDecrement($next_time);
        
        return $this->save();
    } // end decrementAttributes
    
} // end Pet

?>
