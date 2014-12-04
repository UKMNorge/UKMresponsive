<?php
require_once('UKM/sql.class.php');
require_once('UKM/monstring.class.php');

/*
 * DinMonstringController
 */
class DinMonstringController {
    
    public $lat = 0;
    public $lng = 0;
    
    public function __construct() {
        if(isset($_GET['lat']) && isset($_GET['lng'])) {
            $this->lat = floatval($_GET['lat']);
            $this->lng = floatval($_GET['lng']);
        }
    }
    
    public function renderAction()
    {      
        if($this->lat > 0 && $this->lng > 0) {
            try {
                $placeId = $this->getPlaceId();
                $municipalId = $this->getMunicipalId($placeId);
                $plId = $this->getPlId($municipalId);
            }
            catch(Exception $e) {
                $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/din_monstring/?couldnotfind');
            }

            $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/pl' . $plId . '/');  
        }
        
        $md = new Mobile_Detect();       
        if ($md->isMobile() === true && !isset($_GET['couldnotfind']))
            $data['findme'] = true;
        else 
            $data['findme'] = false;

        $data['findme'] = false;
        
        return $data;
    }
    
    public function getPlaceId()
    {
        $query = 'SELECT poststedID, SQRT(POW((69.1 * (zip_codes.lat - #lat)) , 2 ) + ' .
                 'POW((53 * (zip_codes.lon - #lng)), 2)) AS distance ' .
                 'FROM `zip_codes` ' .
                 'ORDER BY distance ASC ' .
                 'LIMIT 1';
                 
        $sql = new SQL($query, array('lat' => $this->lat, 'lng' => $this->lng));
                
        $result = $sql->run('field', 'poststedID');

        if(!isset($result)) {
            throw new Exception('Could not fetch place_id');
        }
        
        return $result;
    }
    
    public function getMunicipalId($placeId) 
    {
        $query = 'SELECT municipal.kommuneID FROM `municipal` ' .
                 'INNER JOIN zip_places ON zip_places.kommuneID = municipal.kommuneID ' .
                 'WHERE poststedID = "#placeId"';
        
        $sql = new SQL($query, array('placeId' => $placeId));
        
        $result = $sql->run('field', 'kommuneID');
        
        if(!isset($result)) {
            throw new Exception('Could not fetch municipal_id');
        }
        
        return $result;
    }
    
    public function getPlId($municipalId) 
    {
        $monstring = new kommune_monstring($municipalId, get_option('season'));
        $monstring = $monstring->monstring_get();
        
        $pl_id = $monstring->get('pl_id');
        
        if (!is_numeric($pl_id)) 
            throw new Exception ('Could not fetch pl_id');
        
        return $pl_id;
    }
    
    public function redirect($url) 
    {
        header("Location:" . $url);
        die();
    }
}

$controller = new DinMonstringController();
$DATA = array_merge($DATA, $controller->renderAction());



$controller = new DinMonstringController();
$DATA = array_merge($DATA, $controller->renderAction());

require_once('UKM/statistikk.class.php');

$stat = new statistikk();
$stat->setLand();
$total = $stat->getTotal(get_option('season'));

$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= get_option('season');

$DATA['stat_pameldte'] = $stat;