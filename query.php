<?php

require_once 'connection.php';
require_once 'response.php';

class Query extends Connection
{
    use ApiResponse;

    protected $sql = null;

    protected $params = [];

    protected $data = null;

    protected $code = 200;

    protected $msg = null;

    public function exec()
    {
        try {
            $pdo = $this->pdo->prepare($this->sql);
            $check = $pdo->execute($this->params);
            $result = $pdo->fetchAll();

            if ($check) {
                $this->data = $result;
                $this->msg = 'success';
            } else {
                $this->msg = 'failed';
                $this->code = 422;
            }
        } catch (PDOException $e) {
            $this->msg = $e->getMessage();
            $this->code = 409;
        }

        return $this;
    }

    public function toJson(): string
    {
        return $this->response($this->msg, $this->data, $this->code);
    }

    public function result(): string
    {
        return json_encode($this->data);
    }

    public function get(string $table): self
    {
        $this->sql = "SELECT * FROM $table";

        return $this;

    }

    public function where(array $options): self
    {
        $opts = implode(' AND ', array_map(function ($key) {
            return "$key=?";
        }, array_keys($options)));

        $this->sql .= " WHERE $opts";
        $this->params = array_merge($this->params, array_values($options));

        return $this;

    }

    public function orWhere(array $options): self
    {
        $opts = implode(' AND ', array_map(function ($key) {
            return "$key=?";
        }, array_keys($options)));

        $this->sql .= " OR $opts";
        $this->params = array_merge($this->params, array_values($options));

        return $this;
    }

    public function store(string $table, array $data): self
    {
        $keys = implode(',', array_keys($data));
        $values = str_repeat('?,', count($data) - 1).'?';
        $result = "($keys) VALUES ($values)";

        $this->sql = "INSERT INTO $table $result";
        $this->params = array_values($data);

        return $this;
    }

    public function update(string $table, array $data, int $id): self
    {
        $result = implode(',', array_map(function ($key) {
            return "$key=?";
        }, array_keys($data)));

        $this->sql = "UPDATE $table SET $result";
        $this->params = array_values($data);
        $this->where(['id' => $id]);

        return $this;
    }

    public function delete(string $table, int $id): self
    {
        $this->sql = "DELETE FROM $table";
        $this->where(['id' => $id]);

        return $this;
    }

    public function raw(string $query): self
    {
        $this->sql = $query;

        return $this;
    }

    public function select(array $columns): self
    {
        $keys = implode(',', array_values($columns));

        $this->sql = "SELECT $keys";

        return $this;
    }

    public function from(string $table): self
    {
        $this->sql .= " FROM $table";

        return $this;
    }
}
