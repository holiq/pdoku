<?php

require_once 'connection.php';
require_once 'response.php';

class Query extends Connection
{
    use ApiResponse;

    protected $sql = null;

    protected $data = [];

    protected $code = 200;

    public function exec(): string
    {
        try {
            $pdo = $this->pdo->prepare($this->sql);
            $check = $pdo->execute($this->data);
            $result = $pdo->fetchAll();

            if ($check) {
                return $this->response('success', $result, $this->code);
            } else {
                return $this->response('failed', null, 422);
            }
        } catch (PDOException $e) {
            return $this->response('error', $e, 409);
        }
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
        $this->data = array_merge($this->data, array_values($options));

        return $this;

    }

    public function orWhere(array $options): self
    {
        $opts = implode(' AND ', array_map(function ($key) {
            return "$key=?";
        }, array_keys($options)));

        $this->sql .= " OR $opts";
        $this->data = array_merge($this->data, array_values($options));

        return $this;
    }

    public function store(string $table, array $data): self
    {
        $keys = implode(',', array_keys($data));
        $values = str_repeat('?,', count($data) - 1).'?';
        $result = "($keys) VALUES ($values)";

        $this->sql = "INSERT INTO $table $result";
        $this->data = array_values($data);

        return $this;
    }

    public function update(string $table, array $data, int $id): self
    {
        $result = implode(',', array_map(function ($key) {
            return "$key=?";
        }, array_keys($data)));

        $this->sql = "UPDATE $table SET $result";
        $this->data = array_values($data);
        $this->where(['id' => $id]);

        return $this;
    }

    public function delete(string $table, int $id): self
    {
        $this->sql = "DELETE FROM $table";
        $this->where(['id' => $id]);

        return $this;
    }
}
