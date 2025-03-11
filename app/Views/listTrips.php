<?php include __DIR__ . '/header.php'; ?>

<h2>Список поездок</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th>
        <th>Курьер</th>
        <th>Регион</th>
        <th>Дата выезда</th>
        <th>Дата прибытия</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($trips)): ?>
        <?php
        /** @var \App\Model\Trip $trip */
        foreach ($trips as $trip): ?>
            <tr>
                <td><?= htmlspecialchars($trip->getId()) ?></td>
                <td><?= htmlspecialchars($trip->getCourier()->getFullName() ?? '') ?></td>
                <td><?= htmlspecialchars($trip->getRegion()->getName()) ?></td>
                <td><?= htmlspecialchars($trip->getDepartureDate()) ?></td>
                <td><?= htmlspecialchars($trip->getArrivalDate()) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Нет данных для отображения</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
