<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->

        <?php foreach ($adverts as $item): ?>

            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($item['img_path']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($item['category']); ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="/lot.php?id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']); ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= strip_tags(format_price($item['start_price']), 'b'); ?></span>
                        </div>
                        <div class="lot__timer timer">
                            <?= lot_time_end($item['end_date']); ?>
                        </div>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>

    </ul>
</section>
