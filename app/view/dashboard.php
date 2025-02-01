<main class="dashboard-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <span>Tableau de bord</span>
    </div>
    </div>
    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
    <div class="homework-tests">
        <div class="homework">
            <h2>Devoirs à rendre</h2>
            <hr>

            <div class="homework-items">
                <?php
                if (!empty($homeworks)) {
                    foreach ($homeworks as $homework) {
                        echo '<div class="homework-item">';
                        echo '<a href="?action=course&course_id=' . $homework['id_module'] . '"><img src="public/img/folder-blue.svg">' . $homework['titre'] . '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucun devoir à rendre pour le moment.</p>';
                }
                ?>
            </div>
        </div>
        <div class="tests">
            <h2>Évaluations</h2>
            <hr>
            <div class="test-items">
                <?php
                if (!empty($tests)) {
                    foreach ($tests as $test) {
                        echo '<div class="test-item">';
                        $formattedDate = date("d/m/Y", strtotime($test['date']));
                        echo '<p>' . $test['titre'] . ' - ' . $test['module_titre'] . '<br>' . $formattedDate . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucune évaluation pour le moment.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="search-courses">
        <div class="form-group">
            <input type="text" class="search-bar" id="searchInput" placeholder="Rechercher" />
            <label for="searchInput">Rechercher un module</label><br>
        </div>
    </div>
    <p class="noResults" style="display: none; font-weight: bold; text-align: center;">Aucun module trouvé.</p>
    <div class="courses">
        <?php foreach ($courses as $course): ?>
        <div class="course">
            <div class="course-header">
                <h2><?= htmlspecialchars($course['titre']) ?></h2>
            </div>
            <div class="course-img">
                <a href="?action=course&course_id=<?= htmlspecialchars($course['id_module']) ?>"><img src="public/img/courses/<?= htmlspecialchars($course['id_module']) ?>.jpg" alt="Aller au module <?= htmlspecialchars($course['titre']) ?>"></a>
                <a href="?action=course&course_id=<?= htmlspecialchars($course['id_module']) ?>" class="seemore">Voir plus</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>