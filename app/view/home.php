<main class="homepage">
    <div class="header-welcome">
        bonjour, <?= $_SESSION['user_firstname'] ?>
    </div>
    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
        <div class="homework-absences">
            <div class="homework">
                <h2>Devoirs à rendre</h2>
                <hr>

                <div class="homework-items">
                    <?php if (!empty($homeworks)): ?>
                        <?php foreach ($homeworks as $homework): ?>
                            <div class="homework-item">
                                <a href="?action=course&course_id=<?= $homework['id_module'] ?>">
                                    <img src="public/img/folder.svg">
                                    <?= htmlspecialchars($homework['titre']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun devoir à rendre pour le moment.</p>
                    <?php endif; ?>

                </div>
                <a class="seemore-btn" href="?action=dashboard">Voir plus</a>
            </div>
            <div class="absences">
                <h2>Absences</h2>
                <hr>
                <?php if (!empty($absences)): ?>
                    <?php foreach ($absences as $absence): ?>
                        <div class="absence-item">
                            <?= date('d/m/Y', strtotime($absence['date_absence'])) ?> de
                            <?= date('H:s', strtotime($absence['heure_debut'])) ?> à <?= date('H:s', strtotime($absence['heure_fin'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucune absence.</p>
                <?php endif; ?>
                <a class="seemore-btn" href="?action=profile">Voir plus</a>
            </div>
        </div>
    <?php endif; ?>
    <img src="public/img/calendars/calendar-mobile.svg" alt="" class="calendar-img calendar-mobile">
    <img src="public/img/calendars/calendar-tablet.svg" alt="" class="calendar-img calendar-tablet">
    <img src="public/img/calendars/calendar-desktop.svg" alt="" class="calendar-img calendar-desktop">
    <?php if($_SESSION['user_role'] === 'etudiant'): ?>
    <div class="homepage-grades">
        <h2>Dernières notes</h2>
        <table>
            <thead>
                <tr>
                    <th>Modules</th>
                    <th>Évaluations</th>
                    <th>Dates</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($grades)): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($grade['module_titre']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($grade['titre']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(date("d/m/Y", strtotime($grade['date_note']))) ?>
                            </td>
                            <td>
                                <strong> <?= htmlspecialchars($grade['note']) ?> / <?= htmlspecialchars($grade['note_max']) ?> </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune note disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="?action=grades" class="view-more">Voir plus</a>
    </div>
    <?php endif; ?>

</main>