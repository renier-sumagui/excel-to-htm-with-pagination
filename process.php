<?php

ini_set("auto_detect_line_endings", true);
require_once("./new-connection.php");

if (isset($_POST["action"]) && $_POST["action"] === "uploadFile") {
  
  if ($_FILES["fileToUpload"]["size"] > 0) {
    $baseName = basename($_FILES["fileToUpload"]["name"]);

    $record = fetch_record("
      SELECT file_name
      FROM excel_files
      WHERE file_name = ?;
    ", "s", [$baseName]);

    if ($record !== null) {
      // Inform user that file exists
    } else {
      // Upload file
      $uploadDir = "public/uploads/";
      $uploadFile = $uploadDir . $baseName;
    
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadFile)) {
        echo "File is valid, and was successfully uploaded";
        $query = "
          INSERT INTO excel_files
            (file_name, created_at, updated_at)
          VALUES
            (?, NOW(), NOW());
        ";
        $values = [$baseName];
        $types = "s";

        try {
          $result = run_mysql_query($query, $types, $values);
          redirectToIndex();
        } catch (ErrorException $error) {
          redirectToIndex();
        }
      };
    }
  }
}

if (isset($_GET["path"])) {

  $path = "public/uploads/" . $_GET["path"];

  $stream = fopen($path, "r");
  
  $rows = [];

  if ($stream) {
    if ($_GET["page"]) {
      fseek($stream, $_GET["page"]);
    } else {

    }

    $counter = 1;
    while (($data = fgetcsv($stream, null, ","))) {
      if ($counter === 50) {
        break;
      }

      $rows[] = $data;

      $counter++;
    }

    var_dump($rows);
  }
}


function redirectToIndex() {
  header("Location: index.php");
  exit();
}

?>