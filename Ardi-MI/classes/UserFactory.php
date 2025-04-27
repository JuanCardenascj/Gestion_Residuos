<?php
abstract class UserFactory {
    abstract public function createUser($data);
}

class RegularUserFactory extends UserFactory {
    public function createUser($data) {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 'user',
            'phone' => $data['phone'],
            'points' => 0,
            'whatsapp_notifications' => $data['whatsapp'] ?? false,
            'status' => 'active'
        ];
    }
}

class CompanyUserFactory extends UserFactory {
    public function createUser($data) {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 'company',
            'phone' => $data['phone'],
            'whatsapp_notifications' => $data['whatsapp'] ?? false,
            'status' => 'active'
        ];
    }
}

class AdminUserFactory extends UserFactory {
    public function createUser($data) {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 'admin',
            'phone' => $data['phone'],
            'whatsapp_notifications' => $data['whatsapp'] ?? true,
            'status' => 'active'
        ];
    }
}
?>