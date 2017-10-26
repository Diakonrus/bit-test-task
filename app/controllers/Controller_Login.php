<?php

Class Controller_Login extends Controller
{
    public function action_index()
    {
        if (! empty($_POST['User'])) {
            $model = new Model_Login();
            $model->email = $_POST['User']['email'];
            $model->password = $_POST['User']['password'];
            if ($model->auth()) {
                header("Location: /");
            }
        }

        $this->view->render('login.php', 'main_template.php');
    }
}

?>