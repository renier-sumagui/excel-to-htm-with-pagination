<?php

require_once("./new-connection.php");

try {
  $files = fetch_all("
    SELECT file_name
    FROM excel_files;
  ");

  var_dump($files);
} catch (ErrorException $error) {
  // Optional logic
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="./public/style.css">
  <title>Excel to HTML with Pagination</title>
</head>
<body>
  <div class="container-fluid p-0">
    <div class="wrapper">
      <div class="container p-0 mt-5">
        <form action="process.php" method="POST" enctype="multipart/form-data" class="d-flex gap-3">
          <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv,.xlsx" />
          <input type="hidden" name="action" value="uploadFile" />
          <input type="submit" value="Upload" />
        </form>
      </div>
      <div class="container p-0 mt-3">
        <p class="fs-5 fw-medium">Uploaded Files:</p>
<?php
      if (count($files) > 0) {
?>
        <ol class="ps-3">
<?php
        foreach ($files as $file) {
?>
          <li><a href="process.php?path=<?= $file['file_name'] ?>"><?= pathinfo($file['file_name'])['filename'] ?></a></li>
<?php
        }
?>
        </ol>
<?php
      }
?>
        <ol class="ps-3">
          <li><a href="process.php?fileName=">Applicants</a></li>
          <li><a href="#">US-500</a></li>
          <li><a href="#">Graduates Batch-1</a></li>
          <li><a href="#">1000 Sales Record</a></li>
          <li><a href="#">New Hires</a></li>
        </ol>
      </div>
    </div>
  </div>
</body>
</html>