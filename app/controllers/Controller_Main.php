<?php

Class Controller_Main extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }


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
}

?>