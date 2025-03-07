<?php
    include_once ('incident.php');
    include_once ('severeIncident.php');
    class pmoController
    {

        protected $accesstoken;
        protected $scriptname;

        function __construct() {
            $this->scriptname =  "../script/pmo_dbh.php";
        }

        /*public function setAccessToken($token)
        {
            $this->accesstoken = $token;
            switch ($this->accesstoken) {
                case "cco":
                    {
                        $this->scriptname = "../script/cco_dbh.php";
                        break;
                    }

                case "pmo":
                    {
                        $this->scriptname =  "../script/pmo_dbh.php";
                        break;
                    }

                case "agency":
                    {
                        $this->scriptname =  "../script/agency_dbh.php";
                        break;
                    }

                case "cmo":
                    {
                        $this->scriptname =  "../script/cmo_dbh.php";
                        break;
                    }
                default:
                    echo "Invalid Session Token found";
            }

        }
        */

        //other methods
        public function getSpecificIncident($incidentId)
        {
            include ($this->scriptname);
            //query to get all incidents
            $sql = "SELECT * FROM incident WHERE incidentId=?;";

            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                return "sqlError";
            }

            mysqli_stmt_prepare($stmt, $sql);

            mysqli_stmt_bind_param($stmt, "s", $incidentId);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            if ($row != NULL) { //there is such incidentId
                $incident = new incident();

                $incident->setIncidentId($row["incidentId"]);
                $incident->setName($row["name"]);
                $incident->setMobileNo($row["mobileNo"]);
                $incident->setLocation($row["location"]);
                $incident->setEmergencyType($row["emergencyType"]);
                $incident->setTypeOfAssistance($row["typeOfAssistance"]);
                $incident->setRemarks($row["remarks"]);
                $incident->setFileDateTime($row["fileDateTime"]);
                $incident->setCcoUsername($row["cco_username"]);
                $incident->setStatus($row["status"]);
                $incident->setStatusDateTime($row["statusDateTime"]);
                $incident->setIsSevere($row["IsSevere"]);
                $incident->setUnitNo($row["unitNo"]);
                return $incident; //return incident obj
            } else {
                return "viewSentRequestError";
            }
        }

        public function getAllIncidentsByEmergencyType($emergencyType)
        {
            include ($this->scriptname);

            $sql = "SELECT * FROM incident WHERE status = 'open' AND emergencyType = ?;";

            $stmt = mysqli_stmt_init($conn);

            mysqli_stmt_prepare($stmt, $sql);

            mysqli_stmt_bind_param($stmt, "s", $emergencyType);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            //stores number of rows in results
            //$resultCheck = mysqli_num_rows($result);

            return $result;
        }

        public function getOtherIncidents()
        {
            //query to get all other open incidents
            include ($this->scriptname);

            $sql = "SELECT * FROM incident WHERE emergencyType != 'Terrorist Attack' AND emergencyType != 'Natural Disaster' AND emergencyType != 'Fire'AND emergencyType != 'Traffic Accident' AND emergencyType != 'Gas Leak';";

            //run query
            $result = mysqli_query($conn, $sql);
            return $result;
        }

        public function getAllClosedIncidents()
        {
            //query to get all closed incidents
            include ($this->scriptname);

            $sql = "SELECT * FROM incident WHERE status = 'closed';";

            //run query
            $result = mysqli_query($conn, $sql);
            return $result;
        }


        public function getAllOpenIncidents(){ 
            //query to get all open incidents
            include ($this->scriptname);

            $sql = "SELECT * FROM incident WHERE status = 'open';";
        
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
            return $result;
        }

        public function getAllIncidents(){ 
            //query to get all incidents
            include ($this->scriptname);

            $sql = "SELECT * FROM incident;";
        
            //run query
            $result = mysqli_query($conn, $sql);
            return $result;
        }

        public function closeIncident($incident){
            include($this->scriptname);

            $sql = "UPDATE incident SET statusDateTime=CURRENT_TIMESTAMP,status='closed' WHERE incidentId=?";
            
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }
    
            mysqli_stmt_bind_param($stmt, "s", $incident->getIncidentId());
            
            $result = mysqli_stmt_execute($stmt);
            if($result == true){ //update success
                return "success";
            }
            else{
                return "updateError";
            }
        }

        public function updateSeverity($incidentId){
            include($this->scriptname);

            $sql = "UPDATE incident SET IsSevere='true' WHERE incidentId=?";
            
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }
    
            mysqli_stmt_bind_param($stmt, "s", $incidentId);
            
            $result = mysqli_stmt_execute($stmt);
            if($result == true){ //update success
                return "success";
            }
            else{
                return "updateError";
            }
        }

        public function insertDetails($incident){ //pass incident obj
            include($this->scriptname);
            //query to insert record into incident db

            if ($incident->getIncidentId() != null) {
                $sql = "INSERT INTO incident(incidentId,name,mobileNo,location,emergencyType,typeOfAssistance,remarks,fileDateTime,cco_username,IsSevere,unitNo) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    return "sqlError";
                }

                mysqli_stmt_bind_param($stmt, "sssssssssss", $incident->getIncidentId(), $incident->getName(), $incident->getMobileNo(), $incident->getLocation(), $incident->getEmergencyType(), $incident->getTypeOfAssistance(), $incident->getRemarks(), $incident->getFileDateTime(), $incident->getCcoUsername(), $incident->getIsSevere(), $incident->getUnitNo());

                $result = mysqli_stmt_execute($stmt);
                if ($result == true) { //insert success
                    return "success";
                    //return mysqli_insert_id($conn);
                } 
                else {
                    return "insertError";
                }
            } 
            else {
                $sql = "INSERT INTO incident(name,mobileNo,location,emergencyType,typeOfAssistance,remarks,cco_username,unitNo) VALUES(?,?,?,?,?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    return "sqlError";
                }

                mysqli_stmt_bind_param($stmt, "ssssssss", $incident->getName(), $incident->getMobileNo(), $incident->getLocation(), $incident->getEmergencyType(), $incident->getTypeOfAssistance(), $incident->getRemarks(), $incident->getCcoUsername(), $incident->getUnitNo());

                $result = mysqli_stmt_execute($stmt);
                if ($result == true) { //insert success
                    return mysqli_insert_id($conn);
                } 
                else {
                    return "insertError";
                }
            }
        }

        public function getSpecificIncidentLatLng($incidentId){
            //include('../script/cmo_dbh.php');
            include ($this->scriptname);
            //query to get all incidents
            $sql = "SELECT * FROM location WHERE incidentId=?;";

            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }

            mysqli_stmt_prepare($stmt,$sql);

            mysqli_stmt_bind_param($stmt, "s", $incidentId);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            if($row != NULL){ //there is such incidentId

                $incident = new incident();

                $incident->setIncidentId($row["incidentId"]);
                $incident->setLatitude($row["latitude"]);
                $incident->setLongitude($row["longitude"]);
                return $incident; //return location obj
            }
            else{
                return "viewError";
            }
        }

        public function getAllIncidentsLatLng(){
            //query to get all incidents
            //include('../script/cmo_dbh.php');
            include($this->scriptname);

            $sql = "SELECT * FROM location;";

            //run query
            $result = mysqli_query($conn, $sql);

            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);

            //$locationArray = array();

            $m = 0;

            /*if($result->num_rows> 0){
                /*while($rows = mysqli_fetch_row($result)){
                /* From location db table
                0: incidentId, 1: latitude, 2: longitude
                    $locationArray[$m] = array();
                    for($i=0; $i< 3; $i++){
                        $locationArray[$m][$i] = $rows[$i];
                    }
                    $m++;
                }
            }*/
            return $result;
        }

        public function insertLatLngDetails($incident)
        { //pass location obj
            //include('../script/cmo_dbh.php');
            include ($this->scriptname);

            //query to insert record into location db
            $sql = "INSERT INTO location(incidentId,latitude,longitude) VALUES(?,?,?)";


            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }

            mysqli_stmt_bind_param($stmt, "sss", $incident->getIncidentId(), $incident->getLatitude(), $incident->getLongitude());

            $result = mysqli_stmt_execute($stmt);
            if($result == true)
            { //insert success
                return "Success";
            }
            else
            {
                return "insertError";
            }
        }

        public function getSpecificSevereCase($incidentId){
            include($this->scriptname);
            //query to get all incidents
            $sql = "SELECT * FROM severeincident WHERE incidentId=?;";

            $stmt = mysqli_stmt_init($conn);

            mysqli_stmt_prepare($stmt,$sql);

            mysqli_stmt_bind_param($stmt, "s", $incidentId);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            if($row != NULL){ //there is such incidentId that is considered severe

                $severeCase = new severeIncident();

                $severeCase->setIncidentId($row["incidentId"]);
                $severeCase->setSuggestionOnAction($row["suggestionOnAction"]);
                $severeCase->setProposedSuggestion($row["proposedSuggestion"]);
                $severeCase->setSeverityLevel($row["severityLevel"]);
                $severeCase->setPmoUsername($row["pmo_username"]);
                $severeCase->setCmoUsername($row["cmo_username"]);
                return $severeCase; //return severeCase obj
            }
            else{
                return false;
            }
        }

        public function isSevere($incidentId){
            include($this->scriptname);
            //query to get all incidents
            $sql = "SELECT * FROM severeincident WHERE incidentId=?;";

            $stmt = mysqli_stmt_init($conn);

            mysqli_stmt_prepare($stmt,$sql);

            mysqli_stmt_bind_param($stmt, "s", $incidentId);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            if($row != NULL){ //i.e.: it is severe
                return true;
            }
            else{
                return false;
            }
        }

        public function hasPmoResponded($incidentId){
            include($this->scriptname);
            //query to get all incidents
            $sql = "SELECT pmo_username FROM severeincident WHERE incidentId=?;";

            $stmt = mysqli_stmt_init($conn);

            mysqli_stmt_prepare($stmt,$sql);

            mysqli_stmt_bind_param($stmt, "s", $incidentId);

            //run query
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            if($row['pmo_username'] != NULL){ //i.e.: it has responded
                return true;
            }
            else{
                return false;
            }
        }

        public function updatePmoAccept($severeCase){
            include($this->scriptname);
            //query to insert record into incident db
            $sql = "UPDATE severeincident SET pmo_username=? WHERE incidentId=?";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }

            mysqli_stmt_bind_param($stmt, "ss", $severeCase->getPmoUsername(), $severeCase->getIncidentId());

            $result = mysqli_stmt_execute($stmt);
            if($result == true){ //insert success
                return "success";
            }
            else{
                return "insertError";
            }
        }

        public function updatePmoReject($severeCase){
            include($this->scriptname);
            //query to insert record into incident db
            $sql = "UPDATE severeincident SET pmo_username=?, proposedSuggestion=? WHERE incidentId=?";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }

            mysqli_stmt_bind_param($stmt, "sss", $severeCase->getPmoUsername(), $severeCase->getProposedSuggestion(), $severeCase->getIncidentId() );

            $result = mysqli_stmt_execute($stmt);
            if($result == true){ //insert success
                return "success";
            }
            else{
                return "insertError";
            }
        }

        public function insertSevereIncidentDetails($incident){ //pass severeCase obj as parameter
            include($this->scriptname);
            //query to insert record into incident db
            $sql = "INSERT INTO severeincident(incidentId,suggestionOnAction,severityLevel,cmo_username) VALUES(?,?,?,?)";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                return "sqlError";
            }

            mysqli_stmt_bind_param($stmt, "ssss", $incident->getIncidentId(), $incident->getSuggestionOnAction(), $incident->getSeverityLevel(), $incident->getCmoUsername());

            $result = mysqli_stmt_execute($stmt);
            if($result == true){ //insert success
                return "success";
            }
            else{
                return "insertError";
            }
        }

        public function getNotifySevereIncident()
        {
            include($this->scriptname);
            $sql = "SELECT * FROM severeincident WHERE HasNotify LIKE 'false%';";
            $result2 = mysqli_query($conn, $sql);

            $dbdata = array();
            $dbdata2 = array();


            while ($row = $result2->fetch_assoc()) {
                $dbdata[] = $row;
            }

            $resultCheck = mysqli_num_rows($result2);

            if ($resultCheck != 0) {
                $sql = "SELECT * FROM incident WHERE incidentId =" . $dbdata[0]['incidentId'] . ";";
                $result1 = mysqli_query($conn, $sql);

                $dbdata2 = array();

                while ($row = $result1->fetch_assoc()) {
                    $dbdata2[] = $row;
                }

                $sql2 = "UPDATE severeincident SET HasNotify = 'true' WHERE incidentId =".$dbdata[0]['incidentId'].";";
                $result2 = mysqli_query($conn, $sql2);
            }
            return $dbdata2;
        }

        //for PMO dashboard:
        public function getNumberOfFire(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType = 'Fire'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $fireCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $fireCount = $resultCheck['COUNT(*)'];
            }
            return $fireCount;
        }
            
        public function getNumberOfOthers(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType != 'Terrorist Attack' AND emergencyType != 'Natural Disaster' AND emergencyType != 'Fire'AND emergencyType != 'Traffic Accident' AND emergencyType != 'Gas Leak'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $otherCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $otherCount = $resultCheck['COUNT(*)'];
            }
            return $otherCount;
        }
            
        public function getNumberOfGasLeak(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType = 'Gas Leak'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $gasCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $gasCount = $resultCheck['COUNT(*)'];
            }
            return $gasCount;
        }
            
        public function getNumberOfTerroristAttack(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType = 'Terrorist Attack'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $terroristCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $terroristCount = $resultCheck['COUNT(*)'];
            }
            return $terroristCount;
        }
            
        public function getNumberOfNaturalDisaster(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType = 'Natural Disaster'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $naturalCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $naturalCount = $resultCheck['COUNT(*)'];
            }
            return $naturalCount;
        }
            
        public function getNumberOfTrafficAccident(){
            include($this->scriptname);
            $sql = "SELECT COUNT(*) FROM incident WHERE emergencyType = 'Traffic Accident'";
            //run query
            $result = mysqli_query($conn, $sql);
    
            //stores number of rows in results
            $resultCheck = mysqli_num_rows($result);
    
            $trafficCount = 0; 

            while ($resultCheck = mysqli_fetch_assoc($result)) {
                $trafficCount = $resultCheck['COUNT(*)'];
            }
            return $trafficCount;
        }
            
        //Get the total number of incidents in a month
        public function getNumberOfIncident($month){

            include($this->scriptname);

            $count = 0; 
            $sql = "SELECT COUNT(*) FROM incident WHERE MONTHNAME(fileDateTime) = ?";

            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                return "sqlError";
            }

            mysqli_stmt_prepare($stmt, $sql);

            mysqli_stmt_bind_param($stmt, "s", $month);

            //run query
            mysqli_stmt_execute($stmt);
            //get the query result
            $result = mysqli_stmt_get_result($stmt);
            //put the result into an associative array 
            $row = mysqli_fetch_assoc($result);

            if ($row != NULL) {
                $count = $row['COUNT(*)'];
            }
            return $count;
        }
    }
?>