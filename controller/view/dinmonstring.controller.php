<?php
require_once('UKM/sql.class.php');

/*
 * DinMonstringController
 */
class DinMonstringController {
    
    public $lat;
    public $lng;
    
    public function __construct($lat, $lng) {
        $this->lat = floatval($lat);
        $this->lng = floatval($lng);
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

        if(!$result) {
            throw new \Exception('Could not fetch place_id');
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
        
        if(!$result) {
            throw new \Exception('Could not fetch municipal_id');
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
        
        if(!$result) {
            throw new \Exception('Could not fetch pl_id');
        }
        
        return $result;      
    }
    
    public function redirect($url) 
    {
        header("location: " . $url);
        die();
    }
}


// Check if lat & lng is set
if(isset($_GET['lat']) && isset($_GET['lng'])) {
    
    // Decide where to redirect
    
    $controller = new DinMonstringController($_GET['lat'], $_GET['lng']);
    
    try {
        $placeId = $controller->getPlaceId();
        $municipalId = $controller->getMunicipalId($placeId);
        $plId = $controller->getPlId($municipalId);
    }
    catch(\Exception $e) {
        $controller->redirect('http://ukm.no/din_monstring/');
    }
    
    $controller->redirect('http://ukm.no/pl' . $plId . '/');
}
// Render view for map
else {
    // @TODO: ADD CHECK FOR IF NOT DESKTOP
    $DATA['findme'] = true;
}