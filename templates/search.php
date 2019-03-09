<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= htmlspecialchars($category['title']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search_phrase; ?></span>»</h2>
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

    <?php if (count($pagination)): ?>
        <ul class="pagination-list">

            <?php if ($cur_page > 1): ?>
                <li class="pagination-item pagination-item-prev">
                    <a href='/search.php?search=<?= $search_phrase; ?>&page=<?= ($cur_page - 1); ?>'>Назад</a>
                </li>
            <?php endif; ?>

            <?php foreach ($pagination as $page): ?>
                <li class="pagination-item <?php ($page === $cur_page) ? print 'pagination-item-active' : ''; ?>">
                    <a <?php ($cur_page !== $page) ? print "href='/search.php?search=$search_phrase&page=$page'" : '' ?>><?= $page; ?></a>
                </li>
            <?php endforeach; ?>

            <?php if ($cur_page !== end($pagination)): ?>
                <li class="pagination-item pagination-item-next">
                    <a href='/search.php?search=<?= $search_phrase; ?>&page=<?= ($cur_page + 1); ?>'>Вперед</a>
                </li>
            <?php endif; ?>

        </ul>
    <?php endif; ?>

</div>
