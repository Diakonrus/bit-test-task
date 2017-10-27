<?php

Class Controller_Main extends Controller
{
    public function action_index()
    {
        session_start();
        $user          = $_SESSION['user'];
        session_write_close();

        $model         = new Model_Main();
        $model->userID = $user->id;
        $finances      = $model->getFinances();
        $operationInfo = [];

        if (! empty($_POST['Finances'])) {
            $operationInfo[] = 'Данные успешно сохранены';
            if (! $model->updateFinance($_POST['Finances'])) {
                $operationInfo = $model->errorLog;
            }
        }

        $this->view->render('main.php', 'main_template.php', [
            'user'          => $user,
            'finances'      => $finances,
            'operationInfo' => $operationInfo,
        ]);
    }

    public function action_404()
    {
        header("HTTP/1.0 404 Not Found");
        $this->view->render('404.php', 'main_template.php');
    }
}