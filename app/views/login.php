<form action="/login" method="post">
    <fieldset>
        <legend>Автризация</legend>
        <label>E-Mail</label>
        <input name="User[email]" type="text" placeholder="Введите Ваш E-Mail">
        <span class="help-block">Пользователь создан в БД spn@mail.ru</span>

        <label>Пароль</label>
        <input name="User[password]" type="password" placeholder="Введите пароль">
        <span class="help-block">Пароль для пользователя spn@mail.ru: Qq123456</span>

        <button type="submit" class="btn">Войти</button>
    </fieldset>
</form>