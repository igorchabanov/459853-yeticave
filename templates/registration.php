<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($category['title']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php
    $classname = isset($errors) ? 'form--invalid' : '';
?>

<form class="form container <?= $classname; ?>" action="/registration.php" method="post"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>

    <?php
    $value = isset($new_user['email']) ? $new_user['email'] : '';
    $classname = isset($errors['email']) ? 'form__item--invalid' : '';
    $error = isset($errors['email']) ? $errors['email'] : '';
    ?>

    <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" value="<?= $value; ?>" placeholder="Введите e-mail">
        <span class="form__error"><?= $error ?></span>
    </div>

    <?php
    $classname = isset($errors['password']) ? 'form__item--invalid' : '';
    $error = isset($errors['password']) ? $errors['password'] : '';
    ?>

    <div class="form__item <?= $classname; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $error; ?></span>
    </div>

    <?php
    $value = isset($new_user['name']) ? $new_user['name'] : '';
    $classname = isset($errors['name']) ? 'form__item--invalid' : '';
    $error = isset($errors['name']) ? $errors['name'] : '';
    ?>

    <div class="form__item <?= $classname; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" value="<?= $value; ?>" placeholder="Введите имя">
        <span class="form__error"><?= $error; ?></span>
    </div>

    <?php
    $value = isset($new_user['message']) ? $new_user['message'] : '';
    $classname = isset($errors['message']) ? 'form__item--invalid' : '';
    $error = isset($errors['message']) ? $errors['message'] : '';
    ?>

    <div class="form__item <?= $classname; ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= $value; ?></textarea>
        <span class="form__error"><?= $error; ?></span>
    </div>
    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
