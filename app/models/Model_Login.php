<?php

class Model_Login extends Model
{
    const TABLE_NAME = 'users';

    public $email;
    public $password;

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    /**
     * @return bool
     */
    public function auth()
    {
        $paramAuth      = [
            'email'    => $this->email,
            'password' => $this->password,
        ];
        $result         = $this->find(self::TABLE_NAME, $paramAuth);
        if (empty($result)) {
            $this->logger->error('Ошибка авторизации пользователя.', $paramAuth);
            return false;
        }

        session_start();
        $result           = current($result);
        $_SESSION['user'] = $result;
        $this->logger->info('Пользователь авторизован.', [
            'id'    => $result->id,
            'email' => $result->email,
        ]);
        session_write_close();


        return true;
    }
}
