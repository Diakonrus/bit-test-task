<?php

class Model_Login extends Model
{
    const TABLE_NAME = 'users';

    public $email;
    public $password;

    /**
     * @return bool
     */
    public function auth()
    {
        $this->email    = trim($this->email);
        $this->password = md5($this->password);
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

?>
