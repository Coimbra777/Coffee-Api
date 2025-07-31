<?php

namespace Src\Controllers;

use Core\Response;
use Src\Models\User;
use Src\Core\Validators\UserValidator;

class UserController
{
    private User $user;
    private Response $response;

    public function __construct()
    {
        $this->user = new User();
        $this->response = new Response();
    }

    public function index()
    {
        $users = $this->user->all();

        if (!$users) {
            return $this->response->success('Nenhum usuário encontrado.', []);
        }

        return $this->response->success('Usuários recuperados com sucesso.', $users);
    }

    public function show($id)
    {
        $authUser = $_REQUEST['authenticated_user']['user'] ?? null;
        $user = $this->user->find($id);

        if (!$user) {
            return $this->response->notFound('Usuário não encontrado.');
        }

        if (!$authUser || $authUser->id != $id) {
            return $this->response->unauthorized("Você não tem permissão para acessar este usuário.");
        }

        return $this->response->success('Usuário recuperado com sucesso.', $user);
    }

    public function update($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return $this->response->notFound('Usuário não encontrado.');
        }

        $authUser = $_REQUEST['authenticated_user']['user'] ?? null;

        if (!$authUser || $authUser->id != $id) {
            return $this->response->unauthorized("Você não tem permissão para atualizar este usuário.");
        }

        $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

        $validator = new UserValidator();

        if (!$validator->validate($data, true)) {
            return $this->response->unprocessableEntity($validator->getErrors());
        }

        $validatedData = $validator->validatedData($data);

        $result = $this->user->update($id, $validatedData);

        if (isset($result['success']) && $result['success'] === true) {
            return $this->response->success('Usuário atualizado com sucesso.', $result['user']);
        } else {
            return $this->response->error($result['error'] ?? 'Erro ao atualizar usuário');
        }
    }

    public function delete($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return $this->response->notFound('Usuário não encontrado.');
        }

        $authUser = $_REQUEST['authenticated_user']['user'] ?? null;

        if (!$authUser || $authUser->id != $id) {
            return $this->response->unauthorized("Você não tem permissão para atualizar este usuário.");
        }

        $result = $this->user->delete($id);

        if (isset($result['success']) && $result['success'] === true) {
            return $this->response->success('Usuário excluído com sucesso.');
        } else {
            return $this->response->error($result['error'] ?? 'Erro ao excluir usuário');
        }
    }
}
