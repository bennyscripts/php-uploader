<?php 

    /*
    * Import the config and uploads json files.
    * Make sure your uploads json file has 777 permissions
    * otherwise logging uploads will not work.
    */

    $config = json_decode(file_get_contents("protected/config.json"), true);
    $uploads = json_decode(file_get_contents("protected/uploads.json"), true);


    /*
    * Getting the values from the config into php variables.
    */

    $cfgPassword = $config["password"];
    $cfgDirectory = $config["directory"];
    $cfgDomain = $config["domain"];

    
    /*
    * Defining upload times to be later added to the upload log.
    * These values can be later used to format your
    * upload date time in the index page.
    *
    * $upTime is the hour, second, millisecond the file was uploaded at.
    *
    * $upTimeDayNum is the day number.                    example: 1.
    * $upTimeDayName is the short day name.               example: Mon.
    * $upTimeDayNameFull is the full day name.            example: Monday.
    * 
    * $upTimeMonthNum is the month number.                example: 1.
    * $upTimeMonthName is the short month name.           example: Jan.
    * $upTimeMonthNameFull is the full month name.        example: January.
    *
    * $upTimeYear is the full year number.                example: 1999.
    * $upTimeYearShort is teh short year number.          example: 99.
    */

    $date = new DateTime();

    $upTime = $date->format("h:i:s A");

    $upTimeDayNum = $date->format("d");
    $upTimeDayName = $date->format("D");
    $upTimeDayNameFull = $date->format("l");

    $upTimeMonthNum = $date->format("n");
    $upTimeMonthName = $date->format("M");
    $upTimeMonthNameFull = $date->format("F");

    $upTimeYear = $date->format("Y");
    $upTimeYearShort = $date->format("y");


    /*
    * Defining default variables used later.
    * 
    * $hash is used for the new filename.
    * $password is the posted password.
    * $protocol is checking if website has ssl.
    */

    $password = $_POST["password"];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http" . "://";
    $hash = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 8); 


    /*
    * Checking if the posted password is identical to the config password.
    * If its not identical it will die with an error code.
    */

    if (!isset($password) || $password !== $cfgPassword) {
        die("Error 401, bad password.");
    }


    /*
    * The actual upload.
    * 
    * First it checks if the file is empty, if it is
    * it will continue with the upload.
    * 
    * After checking the file it will get the file
    * extension from the name and define the new location and url.
    * 
    * Finally it will upload the file to the new path (uploads/),
    * it will also log the file by added a new array to the upload logs. 
    */

    if (!empty($_FILES["file"])) {
        $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $fileurl = $protocol . $cfgDomain . $cfgDirectory . "?f=$hash.$extension";
        $filelocation = __DIR__ . "/uploads/$hash.$extension";

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filelocation)) {
            $uploads["$hash.$extension"]["time"]["time"] = $upTime;

            $uploads["$hash.$extension"]["time"]["dayNum"] = $upTimeDayNum;
            $uploads["$hash.$extension"]["time"]["dayName"] = $upTimeDayName;
            $uploads["$hash.$extension"]["time"]["dayNameFull"] = $upTimeDayNameFull;

            $uploads["$hash.$extension"]["time"]["monthNum"] = $upTimeMonthNum;
            $uploads["$hash.$extension"]["time"]["monthName"] = $upTimeMonthName;
            $uploads["$hash.$extension"]["time"]["monthNameFull"] = $upTimeMonthNameFull;
            
            $uploads["$hash.$extension"]["time"]["year"] = $upTimeYear;
            $uploads["$hash.$extension"]["time"]["yearShort"] = $upTimeYearShort;

            $uploads["$hash.$extension"]["path"] = "uploads/$hash.$extension";

            file_put_contents('protected/uploads.json', json_encode($uploads, JSON_PRETTY_PRINT));

            die($fileurl);
        } else {
            die("Error 502, failure to upload file.");
        }        
    }

?>