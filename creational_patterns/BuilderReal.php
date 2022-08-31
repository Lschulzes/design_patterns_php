<?php

interface SQLQueryBuilder
{
  public function select(string $table, array $fields): SQLQueryBuilder;
  public function where(string $field, string $operator = '=', string $value): SQLQueryBuilder;
  public function limit(int $start, int $offset): SQLQueryBuilder;
  public function getSQL(): string;
}

class MySQLQueryBuilder implements SQLQueryBuilder
{
  protected $query;

  protected function reset(): void
  {
    $this->query = new \stdClass();
  }

  public function select(string $table, array $fields): SQLQueryBuilder
  {
    $this->reset();
    $this->query->base = "SELECT " . implode(", ", $fields) . " FROM " . $table;
    $this->query->type = 'select';
    return $this;
  }

  public function where(string $field, string $operator = '=', string $value): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select', 'update', 'delete'])) {
      throw new \Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
    }
    $this->query->where[] = "$field $operator '$value'";

    return $this;
  }

  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select'])) throw new Exception("LIMIT can only be added to SELECT");

    $this->query->limit = " LIMIT " . $start . ", " . $offset;
    return $this;
  }

  public function getSQL(): string
  {
    $query = $this->query;
    $sql = $query->base;
    if (!empty($query->where)) $sql .= " WHERE " . implode(" AND ", $query->where);

    if (isset($query->limit)) $sql .= $query->limit;
    $sql .= ";";
    return $sql;
  }
}

class PostgresQueryBuilder extends MySQLQueryBuilder
{
  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    parent::limit($start, $offset);
    $this->query->limit = " LIMIT " . $start . " OFFSET " . $offset;
    return $this;
  }
}

function clientCode(SQLQueryBuilder $queryBuilder)
{
  $query = $queryBuilder->select("users", ["name", "email", "password"])
    ->where("age", ">", 18)
    ->where("age", "<", 30)
    ->limit(10, 20)
    ->getSQL();

  echo "<h1>$query</h1>";
}


echo "Testing MySQL <br/>";
clientCode(new MySQLQueryBuilder());
echo "<br/><br/>Testing Postgres <br/>";
clientCode(new PostgresQueryBuilder());
