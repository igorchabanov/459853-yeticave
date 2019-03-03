<nav class="nav">
    <ul class="nav__list container">

        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= $category['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2>Нет доступа к странице</h2>
    <p>Зарегистрируйтесь или войдите в аккаунт, для того размещения лота.</p>
</section>
