<section class="lot-item container">
    <h2><?= htmlspecialchars($lot['title']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['img_path'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['cat']); ?></span></p>
            <p class="lot-item__description">
                <?= htmlspecialchars($lot['description']); ?>
            </p>
        </div>
        <div class="lot-item__right">

                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?= lot_time_end($lot['end_date']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= $lot['price']; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= $lot['next_rate']; ?></span>
                        </div>
                    </div>
                    <?php if ($is_auth && !$exsist_rate && !$author_lot) : ?>
                    <?php
                    $classname = isset($errors['cost']) ? 'form__item--invalid' : '';
                    $error = isset($errors['cost']) ? $errors['cost'] : '';
                    ?>
                    <form class="lot-item__form" action="/lot.php?id=<?= $lot['id'] ?>" method="post">
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost"
                                   placeholder="<?= $lot['next_rate']; ?>">
                            <span class="form__error"><?= $error; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>


            <div class="history">
                <?php if ($rates): ?>

                    <h3>История ставок (<span><?= count($rates); ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($rates as $rate): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($rate['name']); ?></td>
                                <td class="history__price"><?= $rate['amount']; ?> р</td>
                                <td class="history__time"><?= history_time($rate['created']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                <?php endif; ?>
            </div>
        </div>
    </div>
    </tr>
</section>
