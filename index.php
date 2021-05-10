<?php

    /*
    * Import the config and uploads json files.
    */

    $config = json_decode(file_get_contents("protected/config.json"), true);
    $uploads = json_decode(file_get_contents("protected/uploads.json"), true);


    /*
    * Getting the values from the config into php variables.
    */

    $cfgDirectory = $config["directory"];
    $cfgDomain = $config["domain"];


    /*
    * Functions that are needed.
    * 
    * human_filesize returns a human readable filesize, found somewhere on stackoverflow.
    */

    function human_filesize($bytes, $decimals) {
        $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
    } 


    /*
    * Default variables, nothing special.
    */

    $file = $_GET["f"];
    $extension = str_replace(".", "", strrchr($file, '.'));
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http" . "://";
    $stylesheet = __DIR__ . "assets/style.css";
    if (isset($file)) {
        $fileSize = human_filesize(filesize("uploads/$file"), 2);
    }


    /*
    * Getting the values from the upload log into php variables.
    * Read the upload.php file or documentation for more information about the upTimes.
    */

    $upPath = $uploads[$file]["path"];

    $upTime = $uploads[$file]["time"]["time"];

    $upTimeDayNum = $uploads[$file]["time"]["dayNum"];
    $upTimeDayName = $uploads[$file]["time"]["dayName"];
    $upTimeDayNameFull = $uploads[$file]["time"]["dayNameFull"];

    $upTimeMonthNum = $uploads[$file]["time"]["monthNum"];
    $upTimeMonthName = $uploads[$file]["time"]["monthName"];
    $upTimeMonthNameFull = $uploads[$file]["time"]["monthNameFull"];
    
    $upTimeYear = $uploads[$file]["time"]["year"];
    $upTimeYearShort = $uploads[$file]["time"]["yearShort"];

    $uploadTime = "$upTimeDayNum $upTimeMonthNameFull $upTimeYear - $upTime";


    /*
    * Loading embed data from the config file.
    */

    $embedEnabled = $config["embed"]["enabled"];
    $embedAuthor = $config["embed"]["author"];
    $embedTitle = $config["embed"]["title"];
    $embedDescription = $config["embed"]["description"];
    $embedColour = $config["embed"]["colour"];


    /*
    * Add placeholders by replacing strings with what is needed.
    */

    $embedTitle = str_replace("{domain}", $cfgDomain, $embedTitle);
    $embedTitle = str_replace("{filesize}", $fileSize, $embedTitle);
    $embedTitle = str_replace("{filename}", $file, $embedTitle);
    $embedTitle = str_replace("{uploaddate}", $uploadTime, $embedTitle);

    $embedDescription = str_replace("{domain}", $cfgDomain, $embedDescription);
    $embedDescription = str_replace("{filesize}", $fileSize, $embedDescription);
    $embedDescription = str_replace("{filename}", $file, $embedDescription);
    $embedDescription = str_replace("{uploaddate}", $uploadTime, $embedDescription);

    $embedAuthor = str_replace("{domain}", $cfgDomain, $embedAuthor);
    $embedAuthor = str_replace("{filesize}", $fileSize, $embedAuthor);
    $embedAuthor = str_replace("{filename}", $file, $embedAuthor);
    $embedAuthor = str_replace("{uploaddate}", $uploadTime, $embedAuthor);


    /*
    * Make an array for all extension types used in if statements in
    * our HTML code to check what to display for the type of file uploaded.
    */

    $imageExtensions = array("png", "jpg", "jpeg", "gif");
    $videoExtensions = array("mp4", "webm", "mov");
    $audioExtensions = array("mp3");
    $otherExtensions = array("exe");   

?>

<!-- 
    The HTML/webpage.
    Were we display the image with html.
-->

<html>
    <head>
        <?php 
            if (isset($file)) {
                echo "<title>$file</title>";

                if ($embedEnabled == true) {
                    $fileurl = $protocol . $cfgDomain . $cfgDirectory . "uploads/$file";

                    echo "<meta name='og:site_name' content='$embedAuthor'>";

                    foreach ($imageExtensions as $ext) {
                        if ($extension == $ext) {
                            echo "
                                <meta name='twitter:card' content='summary_large_image'>
                                <meta name='twitter:title' content='$embedTitle'>
                                <meta name='twitter:image' content='$fileurl'>
                            ";
                        }
                    }
                    foreach ($videoExtensions as $ext) {
                        if ($extension == $ext) {
                            echo "
                                <meta name='twitter:card' content='player'>
                                <meta name='twitter:title' content='$embedTitle'>
                                <meta name='twitter:image' content='$fileurl'>
                                <meta name='twitter:player:width' content='1280'>
                                <meta name='twitter:player:height' content='720'>                            
                            ";
                        }
                    }
                    foreach ($audioExtensions as $ext) {
                        if ($extension == $ext) {
                            echo "
                                <meta name='twitter:card' content='summary_large_image'>
                                <meta name='twitter:title' content='$embedTitle'>
                            ";
                        }
                    }
                    foreach ($otherExtensions as $ext) {
                        if ($extension == $ext) {
                            echo "
                                <meta name='twitter:card' content='summary_large_image'>
                                <meta name='twitter:title' content='$embedTitle'>
                            ";                                
                        }
                    }

                    echo "
                        <meta name='theme-color' content='$embedColour'>
                        <meta name='twitter:description' content='$embedDescription'>
                    ";
                }
            } else {
                echo "<title>$cfgDomain</title>";
            }
        ?>
        <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php 
            if (isset($file)) {
                foreach (scandir("uploads/") as $upload) {
                    if ($file == $upload) {
                        echo "
                        <center>
                            <div class='card'>
                                <div class='card-body'>
                                    <h1 style='padding-bottom: 0; margin-bottom: 0;'>$file ($fileSize)</h1>
                                    <p style='padding-top: 2px; margin-top: 2px; padding-bottom: 15px; margin-bottom: 15px;'>Uploaded at $uploadTime</p>
                        ";

                        foreach ($imageExtensions as $ext) {
                            if ($extension == $ext) {
                                echo "
                                    <img src='$upPath'></img>
                                ";
                            }
                        }
                        foreach ($videoExtensions as $ext) {
                            if ($extension == $ext) {
                                echo "
                                    <video controls>
                                        <source src='$upPath'>
                                    </video>
                                ";
                            }
                        }
                        foreach ($audioExtensions as $ext) {
                            if ($extension == $ext) {
                                echo "
                                    <audio controls>
                                        <source src='$upPath'>
                                    </audio>
                                ";
                            }
                        }
                        foreach ($otherExtensions as $ext) {
                            if ($extension == $ext) {
                                echo "
                                    <button>
                                        <a href='$upPath' download>
                                            Download
                                        </a>
                                    </button>
                                ";                                
                            }
                        }

                        echo "
                                </div>
                            </div>
                        </center>
                        ";
                    }
                }
            } else {
                echo "
                    <center>
                        <h1 style='padding-bottom: 10; margin-bottom: 10; transform: scale(2);'>$cfgDomain</h1>
                        <p style='padding-top: 0; margin-top: 0; transform: scale(2);' class='light' >Hosted by <a href='https://github.com/ilyBenny/php-uploader'>PHP Uploader</a>.</p>
                    </center>
                ";
            }
        ?>
    </body>
</html>
