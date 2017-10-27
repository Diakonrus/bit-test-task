<?php

Class Controller_Login extends Controller
{
    public function action_index()
    {
        if (! empty($_POST['User'])) {
            $model = new Model_Login();
            $model->setEmail($_POST['User']['email']);
            $model->setPassword($_POST['User']['password']);
            if ($model->auth()) {
                header("Location: /");
            }
        }

        $this->view->render('login.php', 'main_template.php');
    }
}
