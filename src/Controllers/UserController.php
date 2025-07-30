<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Core\Validators\UserValidator;

class UserController

{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->all();

        if (!$users) {
            echo json_encode(['data' => []]);
        } else {
            echo json_encode(['data' => $users]);
        }
    }

    public function show($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            echo json_encode(['data' => []]);
        } else {
            echo json_encode(['data' => $user]);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            $data = $_POST;
        }

        $validator = new UserValidator();

        if (!$validator->validate($data, true)) {
            http_response_code(422);
            echo json_encode(['errors' => $validator->getErrors()]);
            return;
        }

        $validatedData = $validator->validatedData($data);

        $result = $this->user->update($id, $validatedData);

        if (isset($result['success']) && $result['success'] === true) {
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => $result['error'] ?? 'Failed to update user']);
        }
    }
}
