<?php
class UNL_Peoplefinder_Driver_WebService implements UNL_Peoplefinder_DriverInterface
{
    /**
     * The address to the webservice
     * 
     * @var string
     */
    public $service_url = 'http://peoplefinder.unl.edu/service.php';

    function __construct($options = array())
    {
        if (isset($options['service_url'])) {
            $this->service_url = $options['service_url'];
        }
    }
    
    function getExactMatches($query, $affiliation = null)
    {
        $results = file_get_contents($this->service_url.'?q='.urlencode($query).'&format=php&affiliation='.urlencode($affiliation).'&method=getExactMatches');
        if ($results) {
            $results = unserialize($results);
        }
        return $results;
    }
    function getAdvancedSearchMatches($query, $affliation = null)
    {
        throw new Exception('Not implemented yet');
    }
    function getLikeMatches($query, $affiliation = null)
    {
        $results = file_get_contents($this->service_url.'?q='.urlencode($query).'&format=php&affiliation='.urlencode($affiliation).'&method=getLikeMatches');
        if ($results) {
            $results = unserialize($results);
        }
        return $results;
    }

    /**
     * Get matches for a phone search
     * 
     * @param string $query       Numerical search query
     * @param string $affiliation eduPersonAffiliation, eg, student, staff, faculty
     * 
     * @return UNL_Peoplefinder_SearchResults
     */
    function getPhoneMatches($query, $affiliation = null)
    {
        $results = file_get_contents($this->service_url.'?q='.urlencode($query).'&format=php&affiliation='.urlencode($affiliation).'&method=getPhoneMatches');
        if ($results) {
            $results = unserialize($results);
        }
        return $results;
    }

    /**
     * Get an individual's record within the directory.
     * 
     * @param string $uid Unique ID for the user, eg: bbieber2
     * 
     * @return UNL_Peoplefinder_Record
     */
    function getUID($uid)
    {
        $record = file_get_contents($this->service_url.'?uid='.urlencode($uid).'&format=php');

        if (false === $record) {
            throw new Exception('Could not find that user!');
        }

        return unserialize($record);
    }
}
?>