<?php if (! empty($operationInfo)) { ?>
<fieldset>
    <legend>Результат выполнения операции:</legend>
    <div class="operation-info">
        <ul>
            <?php foreach ($operationInfo as $info) { ?>
                <li><?=$info;?></li>
            <?php } ?>
        </ul>
    </div>
</fieldset>
<?php } ?>

<form action="/" method="post" id="finances-form">
    <fieldset>
        <legend>Средства аккаунта пользователя <?=$user->email?></legend>

        <?php foreach ($finances as $finance) { ?>

            <label>
                Счет #<?=$finance->id;?>. Доступно <b>
                    <?=$finance->sum;?> <?=Model_Main::$currencyLists[$finance->currency_id];?>
                </b>:
            </label>
            <input name="Finances[<?=$finance->id;?>][sum]" type="text" placeholder="Введите сумму для списания" value="0">
            <span class="help-block">
                Доступно для списания
                <?=$finance->sum;?> <?=Model_Main::$currencyLists[$finance->currency_id];?>
            </span>


        <?php } ?>

        <button type="submit" class="btn">Подтвердить</button>
    </fieldset>
</form>