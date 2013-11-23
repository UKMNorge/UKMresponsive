<?php
require_once('UKM/sql.class.php');

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
                $this->redirect('http://ukm.no/din_monstring/');
            }

            $this->redirect('http://ukm.no/pl' . $plId . '/');  
        }
        // @TODO: ADD CHECK FOR IF NOT DESKTOP
        $data['findme'] = true;
        return $data;
    }
    
    public function getPlaceId()
    {
        $query = 'SELECT place_id, SQRT(POW((69.1 * (zip_codes.lat - #lat)) , 2 ) +' .
                 'POW((53 * (zip_codes.lon - #lng)), 2)) AS distance' .
                 'FROM `zip_codes`' .
                 'ORDER BY distance ASC' .
                 'LIMIT 1';
                 
        $sql = new SQL($query, array('lat' => $this->lat, 'lng' => $this->lng));
        
        $result = $sql->run('field', 'place_id');

        if(!isset($result)) {
            throw new Exception('Could not fetch place_id');
        }
        
        return $result;
    }
    
    public function getMunicipalId($placeId) 
    {
        $query = 'SELECT municipal.municipal_id FROM `municipal`' .
                 'INNER JOIN zip_places ON zip_places.municipal_id = municipal.municipal_id' .
                 'WHERE place_id = "#placeId"';
                 
        $sql = new SQL($query, array('placeId' => $placeId));
        
        $result = $sql->run('field', 'municipal.municipal_id');
        
        if(!isset($result)) {
            throw new Exception('Could not fetch municipal_id');
        }
        
        return $result;
    }
    
    public function getPlId($municipalId) 
    {
        $query = 'SELECT `pl_id`' .
                 'FROM `smartukm_rel_pl_k`' .
                 'WHERE `k_id` = "#kid"' .
                 'AND `season` = "#season"';
        
        $sql = new SQL($query, array('kid' => intval($municipalId), 'season' => get_option('season')));
        
        $result = $sql->run('field', 'pl_id');   
        
        if(!isset($result)) {
            throw new Exception('Could not fetch pl_id');
        }
        
        return $result;      
    }
    
    public function redirect($url) 
    {
        header("location: " . $url);
        die();
    }
}

$controller = new DinMonstringController();
$DATA = array_merge($DATA, $controller->renderAction());