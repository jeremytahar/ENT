<div class="homepage">
    <div class="header-welcome">
        bonjour, <?= $_SESSION['user_firstname'] ?>
    </div>
    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
        <div class="homework-absences">
            <div class="homework">
                <h2>Devoirs à rendre</h2>
                <hr>

                <div class="homework-items">
                    <?php
                    if (!empty($homeworks)) {
                        foreach ($homeworks as $homework) {
                            echo '<div class="homework-item">';
                            echo '<a href="?action=course&course_id=' . $homework['id_module'] . '"><img src="public/img/folder.svg">' . $homework['titre'] . '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Aucun devoir à rendre pour le moment.</p>';
                    }
                    ?>
                </div>
                <a class="seemore-btn" href="?action=dashboard">Voir plus</a>
            </div>
            <div class="absences">
                <h2>Absences</h2>
                <hr>
                <?php
                if (!empty($absences)) {
                    foreach ($absences as $absence) {
                        echo '<div class="absence-item">';
                        echo '<p>' . date('d/m/Y', strtotime($absence['date_absence'])) . '</p>';
                        echo '<p>' . $absence['heure_debut'] . ' - ' . $absence['heure_fin'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucune absence.</p>';
                }
                ?>
                <a class="seemore-btn" href="?action=profile">Voir plus</a>
            </div>
        </div>
    <?php endif; ?>
    <!-- <div class="homepage-calendar"> -->
    <img src="public/img/calendar.svg" alt="" class="calendar-img">
    <!-- </div> -->
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
                <?php
                if (!empty($grades)) {
                    foreach ($grades as $grade) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($grade['module_titre']) . '</td>';
                        echo '<td>' . htmlspecialchars($grade['titre']) . '</td>';
                        echo '<td>' . htmlspecialchars(date("d/m/Y", strtotime($grade['date_note']))) . '</td>';
                        echo '<td><strong>' . htmlspecialchars($grade['note']) . '/' . htmlspecialchars($grade['note_max']) . '</strong></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Aucune note disponible.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <button class="view-more">Voir plus</button>
    </div>

</div>