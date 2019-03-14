<section class="rates container">
    <h2><?= $page_title; ?></h2>

    <?php if (empty($items)): ?>
        <h3>Вы пока не делали ставок</h3>

    <?php else: ?>
        <table class="rates__list">
            <?php foreach ($items as $item): ?>
                <?php $winner = ($item['winner_id'] === $user_id) ?>

                <tr class="rates__item <?php if ($winner) {
                    echo 'rates__item--win';
                } ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= htmlspecialchars($item['img_path']); ?>" width="54" height="40"
                                 alt="<?= htmlspecialchars($item['title']); ?>">
                        </div>
                        <h3 class="rates__title">
                            <a href="/lot.php?id=<?= $item['id'] ?>">
                                <?= htmlspecialchars($item['title']); ?>
                            </a>
                        </h3>
                        <p><?= htmlspecialchars($item['contact']); ?></p>
                    </td>
                    <td class="rates__category">
                        <?= $item['category'] ?>
                    </td>
                    <td class="rates__timer">

                        <?php if ($winner): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php else: ?>
                            <div class="timer timer--finishing">
                                <?= lot_time_end($item['end_date']); ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?= strip_tags(format_price($item['total']), 'b'); ?>
                    </td>
                    <td class="rates__time">
                        <?= history_time($item['created']);; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
