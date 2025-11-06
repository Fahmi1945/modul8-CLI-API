<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    protected $format = 'json';
    protected $modelName = UserModel::class;


    // ===== REGISTER USER =====
    public function register()
    {
        $data = $this->request->getJSON(true);
        $userModel = new UserModel();

        // Cek username atau email sudah terdaftar
        if (
            $userModel->where('username', $data['username'])->first() ||
            $userModel->where('email', $data['email'])->first()
        ) {
            return $this->respond(['message' => 'Username atau email sudah terdaftar'], 400);
        }

        // Enkripsi password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // Simpan user baru
        if ($userModel->registerUser($data)) {
            return $this->respondCreated(['message' => 'User berhasil didaftarkan']);
        }

        return $this->respond(['message' => 'Gagal mendaftarkan user'], 400);
    }

    // ===== LOGIN USER =====
    public function login()
    {
        $data = $this->request->getJSON(true);
        $userModel = new UserModel();

        $user = $userModel->loginUser($data['username'], $data['password']);

        if ($user) {
            return $this->respond([
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ]
            ], 200);
        }

        return $this->respond(['message' => 'Username atau password salah'], 401);
    }
}