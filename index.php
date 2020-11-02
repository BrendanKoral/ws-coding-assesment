<?php
require_once './upload/UploadHandler.php';

use Upload\UploadHandler;

$csv = null;
if ($_FILES) {
    $UploadHandler = new UploadHandler();
    $UploadHandler->verify_upload_info();
    if ($UploadHandler->get_file() !== null) {
        $csv = $UploadHandler->get_file();
    }
}


if ($csv) {
    //Sort CSV rows by run number
    usort($csv, function ($item1, $item2) {
        return $item1[2] <=> $item2[2];
    });

    $headers = current($csv);
    $entries_minus_headers = array_slice($csv, 1, count($csv));
}

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Chicago Trains</title>
    </head>
    <body>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php if (!$csv) { ?>
            <h3>No CSV file uploaded! Please upload a CSV.</h3>
        <?php } ?>
        <div>
            <span>Upload a File:</span>
            <input type="file" name="uploadedFile"/>
        </div>
        <input type="submit" name="uploadBtn" value="Upload"/>

        <h1>Chicago Train Lines</h1>
        <table>
            <tr>
                <?php
                if ($headers) {
                    foreach ($headers as $header) {
                        echo "<th>${header}</th>";
                    }
                }
                ?>
            </tr>
            <?php
            if ($entries_minus_headers) {
                foreach ($entries_minus_headers as $entry) {
                    echo "<tr>";
                    foreach ($entry as $cell) {
                        echo "<td>${cell}</td>";
                    }
                    echo "</tr>";
                }
            } ?>
        </table>

    </form>
    </body>
    </html>


<?php

