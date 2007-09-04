<?php
/**
 *  
 **/

class Timezone extends ActiveTable
{
    protected $table_name = 'timezone';
    protected $primary_key = 'timezone_id';

    public function getFormattedOffset()
    {
        $offset = $this->getTimezoneOffset();
        
        $broken = explode('.',$offset);
        if($broken[1] == 0)
        {
            $offset = $broken[0];
        }
        else
        {
            $offset = $broken[0].':30'; 
        }
       
        if(substr($offset,0,1) != '-')
        {
            $offset = '+'.$offset;
        }

        return $offset;
    } // end getFormattedOffset

    public function getTimezoneName()
    {
        if($this->getTimezoneShortName() != 'UTC')
        {
            return "{$this->getTimezoneContinent()}/{$this->getTimezoneShortName()} ({$this->getFormattedOffset()})";
        }

        return 'UTC (0)';
    } // end getTimezoneName
    
} // end Timezone

?>
