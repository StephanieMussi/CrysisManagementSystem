<html>
<body  onload="startTime()">
<!----------Top Navigation Bar-------->
<section id = "nav-bar">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="cmo_main.php">Unknown Crysis</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!--------Logout---------->
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="..\script\logout.php">Logout</a>
        </div> <div id="txt" style="color:white"></div>
        </div>
    </nav>
</section>
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="../script/notification/push.js"></script>
<script>
    function getIncidentNotification($notificationId){
        url = "view_incident_notification.php?view=true&notificationId="+$notificationId;
        window.location.assign(url);
    }

    function startTime() {

        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        //check time for email
        if(s==0 && (m%5==0)){
            generateReport();
        }
        if(s==0 && (m==1||m==6||m==11||m==16||m==21||m==26||m==31||m==36||m==41||m==46||m==51||m==56)){
            sendEmail();
        }
        //check time for jsongeneration
        if(s%10==0) {
             generateJson();
        }

        //check for notification
        if(s%6==0) {
            notify();
        }

        s = checkTime(s);
        document.getElementById('txt').innerHTML =
            h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 1000);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }

    function generateReport(){

        $.ajax({

            url : 'cmo_generate_report.php',
            type : 'POST',
            success : function (result) {
               console.log("Report generated");
                // console.log (result); // Here, you need to use response by PHP file.
            },
            error : function () {
                console.log ('error');
            }

        });


    }

    function sendEmail(){

        //not functioning here yet as PHPMailer not in this directory
        $.ajax({

            url : 'cronEmail.php',
            type : 'POST',
            success : function (result) {
                console.log("Email sent!"); // Here, you need to use response by PHP file.
            },
            error : function () {
                console.log('error');
            }

        });

    }

    function notify(){

        var arr;
        var att = "";
        var id3="";
        $.ajax({

            url : 'notify_new_incident.php',
            type : 'POST',
            dataType: "html",
            async: false,
            cache: false,
            success : function (result) {
                console.log (result); // Here, you need to use response by PHP file.
                if(result != "NIL") {
                    arr = JSON.parse(result);
                    att = arr[0]["emergencyType"];
                    id3 = arr[0]["incidentId"];
                    console.log(att);
                }

            },
            error : function () {
                console.log ('error');
            }

        });

        if(att!="") {
            Push.create("New Emergency", {
                body: att,
                icon: '../script/notification/sgsecure.png',
                timeout: 10000,
                onClick: function () {
                    window.open("http://localhost:8080/htdocs/cms/cmo/cmo_view_more.php?viewMore=true&incidentId=" + id3,'_self');
                    this.close();
                }
            });
        }

        //ajax call to update notify status

        var att2 = "";
        var id2 = "";
        $.ajax({

            url : 'notify_pmo_response.php',
            type : 'POST',
            dataType: "html",
            async: false,
            cache: false,
            success : function (result) {
                console.log (result); // Here, you need to use response by PHP file.
                if(result != "NIL") {
                    arr = JSON.parse(result);
                    att2 = arr[0]["emergencyType"];
                    id2 = arr[0]["incidentId"]
                    console.log(att2);
                }

            },
            error : function () {
                console.log ('error');
            }

        });

        if(att2!="") {
            Push.create("Response from PMO", {
                body: att,
                icon: '../script/notification/sgsecure.png',
                timeout: 10000,
                onClick: function () {
                    window.open("http://localhost:8080/htdocs/cms/cmo/cmo_view_more.php?viewMore=true&incidentId="+id2,'_self');
                    this.close();
                }
            });
        }

        //ajax call to update notify status
        var up = "";
        var id = 0;
        $.ajax({

            url : 'notify_agency_update.php',
            type : 'POST',
            dataType: "html",
            async: false,
            cache: false,
            success : function (result) {
                console.log (result); // Here, you need to use response by PHP file.
                if(result != "NIL") {
                    arr = JSON.parse(result);
                    id = arr[0]["incidentId"];
                    up = arr[0]["msg"];
                    console.log(att2);
                }

            },
            error : function () {
                console.log ('error');
            }

        });

        if(up!="") {
            Push.create("Update on incident ID:" + id, {
                body: up,
                icon: '../script/notification/sgsecure.png',
                timeout: 10000,
                onClick: function () {
                    window.open("http://localhost:8080/htdocs/cms/cmo/cmo_view_more.php?viewMore=true&incidentId="+id,'_self');
                    this.close();
                }
            });
        }


    }

    function generateJson(){

        //not functioning here yet as PHPMailer not in this directory
        $.ajax({

            url : 'generatejson.php',
            type : 'POST',
            success : function (result) {
                //console.log (result); // Here, you need to use response by PHP file.
            },
            error : function () {
                console.log ('error');
            }

        });

    }



</script>