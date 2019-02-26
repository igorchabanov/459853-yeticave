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

<form class="form form--add-lot container <?= $classname ?>" action="add.php" method="post"
      enctype="multipart/form-data">
    <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">

        <?php
        $value = isset($new_lot['lot-name']) ? $new_lot['lot-name'] : '';
        $classname = isset($errors['lot-name']) ? 'form__item--invalid' : '';
        $error = isset($errors['lot-name']) ? $errors['lot-name'] : '';
        ?>

        <div class="form__item <?= $classname; ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" value="<?= htmlspecialchars($value); ?>"
                   placeholder="Введите наименование лота">
            <span class="form__error"><?= $error; ?></span>
        </div>

        <?php
        $classname = isset($errors['category']) ? 'form__item--invalid' : '';
        $error = isset($errors['category']) ? $errors['category'] : '';
        ?>
        <div class="form__item <?= $classname; ?>">
            <label for="category">Категория</label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>" <?php (isset($new_lot['category']) && $new_lot['category'] === $category['id'])? print 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $error; ?></span>
        </div>
    </div>

    <?php
    $value = isset($new_lot['message']) ? $new_lot['message'] : '';
    $classname = isset($errors['message']) ? 'form__item--invalid' : '';
    $error = isset($errors['message']) ? $errors['message'] : '';
    ?>

    <div class="form__item form__item--wide <?= $classname; ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= htmlspecialchars($value); ?></textarea>
        <span class="form__error"><?= $error; ?></span>
    </div>


    <?php
    $value = isset($new_lot['file']) ? $new_lot['message'] : '';
    $classname = isset($errors['file']) ? 'form__item--invalid' : '';
    $error = isset($errors['file']) ? $errors['file'] : '';
    ?>
    <div class="form__item form__item--file <?= $classname; ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?php isset($new_lot['img_path']) ? print htmlspecialchars($new_lot['img_path']) : 'img/avatar.jpg' ?>"
                     width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot-img" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
            <span class="form__error"><?= $error; ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <?php
        $value = isset($new_lot['lot-rate']) ? $new_lot['lot-rate'] : '';
        $classname = isset($errors['lot-rate']) ? 'form__item--invalid' : '';
        $error = isset($errors['lot-rate']) ? $errors['lot-rate'] : '';
        ?>

        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" value="<?= htmlspecialchars($value); ?>" placeholder="0">
            <span class="form__error"><?= $error; ?></span>
        </div>

        <?php
        $value = isset($new_lot['lot-step']) ? $new_lot['lot-step'] : '';
        $classname = isset($errors['lot-step']) ? 'form__item--invalid' : '';
        $error = isset($errors['lot-step']) ? $errors['lot-step'] : '';
        ?>
        <div class="form__item form__item--small <?= $classname; ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" value="<?= htmlspecialchars($value); ?>" placeholder="0">
            <span class="form__error"><?= $error; ?></span>
        </div>

        <?php
        $value = isset($new_lot['lot-date']) ? $new_lot['lot-date'] : '';
        $classname = isset($errors['lot-date']) ? 'form__item--invalid' : '';
        $error = isset($errors['lot-date']) ? $errors['lot-date'] : '';
        ?>
        <div class="form__item <?= $classname; ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" value="<?= htmlspecialchars($value); ?>" name="lot-date">
            <span class="form__error"><?= $error; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
