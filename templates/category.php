<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= htmlspecialchars($cat_name); ?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($items as $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($item['img_path']); ?>" width="350" height="260"
                             alt="<?= htmlspecialchars($item['title']); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $item['cat_name']; ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= strip_tags(format_price($item['price']),
                                        'b'); ?></span>
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

    <?php if (!empty($pages)): ?>
        <?= $pagination; ?>
    <?php endif; ?>
</div>
