<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

class Database
{
  public PDO $connection;
  public PDOStatement $stmt;
  public array $results = [];

  public function __construct(string $driver, array $config, string $username, string $password)
  {
    $config = http_build_query(data: $config, arg_separator: ';');
    $dsn = "{$driver}:{$config}";

    try {
      $this->connection = new PDO($dsn, $username, $password, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch (PDOException $e) {
      die("Unable to connect database.");
    }
  }

  public function query(string $query, array $params = []): Database
  {
    $this->stmt = $this->connection->prepare($query);

    $this->stmt->execute($params);

    return $this;
  }

  public function count()
  {
    return $this->stmt->fetchColumn();
  }

  public function find()
  {
    return $this->stmt->fetch();
  }

  public function id()
  {
    return $this->connection->lastInsertId();
  }

  public function fetchAllResults(): Database
  {
    $this->results = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

    return $this;
  }

  public function findAll()
  {
    return $this->stmt->fetchAll();
  }
}
