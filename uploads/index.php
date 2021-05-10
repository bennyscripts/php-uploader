<?php

    /*
    * Import the config and uploads json files.
    */

    $config = json_decode(file_get_contents(str_replace("uploads", "", __DIR__) . "/protected/config.json"), true);

    /*
    * Getting the values from the config into php variables.
    */

    $cfgDirectory = $config["directory"];
    $cfgDomain = $config["domain"];

?>
<html>
    <head>
        <title><?php echo $cfgDomain; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo $cfgDirectory; ?>/assets/style.css?v=10">
    </head>
    <body>
        <center>
            <video class="img-fluid" loop="" autoplay="" muted="">
                <source src="<?php echo $cfgDirectory; ?>/assets/stay-out.mp4" type="video/mp4">
            </video>
        </center>
    </body>
</html>
