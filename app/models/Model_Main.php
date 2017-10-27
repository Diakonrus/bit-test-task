<?php

class Model_Main extends Model
{
    public $userID;
    public $errorLog = [];

    const TABLE_NAME = 'finances';

    const CURRENCY_RUB    = 0;
    const CURRENCY_EUR    = 1;
    const CURRENCY_DOLLAR = 2;

    public static $currencyLists = [
        self::CURRENCY_RUB    => '&#8381;',
        self::CURRENCY_EUR    => '&euro;',
        self::CURRENCY_DOLLAR => '&#36;'
    ];


    public function getFinances()
    {
        return $this->find('finances', [
            'user_id' => $this->userID,
        ]);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function updateFinance(array $params)
    {
        $this->logger->info('Поступил запрос на списание средств', [
            'userID' => $this->userID,
            'params' => $params
        ]);

        $this->processingFinanceParam($params);
        if (! empty($this->errorLog)) {
            return false;
        }

        if (! $this->updateFinances(self::TABLE_NAME, $params)) {
            $this->errorLog[] = 'Ошибка при выполнении списания средств! Операция отменена, средства не списаны.';
            $this->logger->error('Ошибка транзакции при попытке списания средств.', [
                'userID' => $this->userID,
                'params' => $params
            ]);
            return false;
        }

        $this->logger->info('Средства успешно списаны', [
            'userID' => $this->userID,
            'params' => $params
        ]);

        return true;
    }

    /**
     * @param array $params
     * @return array
     */
    private function processingFinanceParam(array &$params)
    {
        $errorLog = [];
        foreach ($params as $id => $value) {
            $sum    = trim($value['sum']);
            $result = $this->find(self::TABLE_NAME, [
                'id'      => $id,
                'user_id' => $this->userID,
            ]);
            if (empty($result)) {
                $msg        = "Счет $id не найден в БД, либо закреплен за другим пользователем!";
                $errorLog[] = $msg;
                $this->logger->error('Ошибка при попытке списания средств', [
                    'userID' => $this->userID,
                    'msg'    => $msg,
                    'params' => $params
                ]);
                continue;
            }

            $accountSum = current($result);
            $accountSum = $accountSum->sum;

            if (! ctype_digit($sum) || $sum < 0 || $accountSum - $sum < 0) {
                $msg        = "Для счета $id указана неверная сумма списания!";
                $errorLog[] = $msg;
                $this->logger->error('Ошибка при попытке списания средств', [
                    'userID' => $this->userID,
                    'msg'    => $msg,
                    'params' => $params
                ]);
                continue;
            }
            $params[$id] = (int)$sum;
        }

        $this->errorLog = $errorLog;
        return $params;
    }
}
