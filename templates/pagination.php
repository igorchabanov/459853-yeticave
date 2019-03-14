<ul class="pagination-list">

    <?php if ($cur_page > 1): ?>
        <li class="pagination-item pagination-item-prev">
            <a href='/<?= $link ?>&page=<?= ($cur_page - 1); ?>'>Назад</a>
        </li>
    <?php endif; ?>

    <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?php ($page === $cur_page) ? print 'pagination-item-active' : ''; ?>">
            <a <?php ($cur_page !== $page) ? print "href='$link&page=$page'" : '' ?>><?= $page; ?></a>
        </li>
    <?php endforeach; ?>

    <?php if ($cur_page !== end($pages)): ?>
        <li class="pagination-item pagination-item-next">
            <a href='/<?= $link; ?>&page=<?= ($cur_page + 1); ?>'>Вперед</a>
        </li>
    <?php endif; ?>

</ul>
