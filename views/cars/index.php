<h2>Tableau de bord - Voitures d'occasion</h2>
<table>
    <thead>
        <tr>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Prix</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cars as $car): ?>
        <tr>
            <td><?= htmlspecialchars($car['brand']) ?></td>
            <td><?= htmlspecialchars($car['model']) ?></td>
            <td><?= htmlspecialchars($car['year']) ?></td>
            <td><?= number_format($car['price'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>