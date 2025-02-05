<?php

$env = parse_ini_file("./.env");

DEFINE("DB_HOST", $env["DB_HOST"]);
DEFINE("DB_USER", $env["DB_USER"]);
DEFINE("DB_PASS", $env["DB_PASS"]);
DEFINE("DB_NAME", $env["DB_NAME"]);

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($connection->errno) {
  throw new ErrorException("Failed to connect to database: {$connection->errno} $connection->error");
}

function fetch_all($query) {
  $data = array();
  global $connection;
  $result = $connection->query($query);

  if ($result === false) {
    return $result;
  }

  while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  $result->free();

  return $data;
}

function fetch_record(string $query, string $types, array $values) {
  global $connection;

  try {
    $statement = $connection->prepare($query);
    $statement->bind_param($types, ...$values);

    // $result = $connection->query($query);

    $statement->execute();

    $result = $statement->get_result();

    $data = $result->fetch_assoc();

    $statement->close();

    return $data;
  } catch (ErrorException $err) {
    throw new ErrorException("Something went wrong: " . $connection->error);
  }
}

function run_mysql_query(string $query, string $types, array $values) {
  global $connection;

  # 1. Prepare
  $statement = $connection->prepare($query);

  if (!$statement) {
    throw new Exception("Failed to prepare statement: " . $connection->error);
  }

  # 2. Bind and Execute
  $statement->bind_param($types, ...$values);

  # 3. Execute the Statement
  $result = $statement->execute();
  if ($result) {
    $insertId = $connection->insert_id;
    if ($insertId > 0) {
        return $insertId;
    }
  } else {
      throw new Exception("Query execution failed: " . $statement->error);
  }
  $statement->close();
}

function escape_this_string($string) {
  global $connection;
  return $connection->real_escape_string($string);
}

?>