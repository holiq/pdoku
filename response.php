<?php

trait ApiResponse
{
    private array $result = [
        'status' => null,
        'message' => null,
        'data' => [],
        'error' => [],
    ];

    public function response(string $msg = null, null|array|PDOException $data = null, int $code = 200): string
    {
        $this->result['status'] = in_array($code, [200, 201, 202]) ? 'success' : 'error';
        $this->result['message'] = $msg;

        if (in_array($code, [200, 201, 202])) {
            $this->result['data'] = $data;
            unset($this->result['error']);
        } else {
            $this->result['error'] = $data;
            unset($this->result['data']);
        }

        http_response_code($code);

        return json_encode($this->result);
    }
}
