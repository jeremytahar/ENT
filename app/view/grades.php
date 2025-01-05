<main class="grades-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <span>Notes</span>
    </div>
    <h1>Mes notes</h1>
    <div class="grades-container">
        <div class="general-average">
            <div class="general-average-content">
                <span>Ma moyenne générale</span>
                <span class="general-average-grade"></span>
            </div>
        </div>
        <div class="modules">
            <?php
            $modules = [];
            foreach ($grades as $grade) {
                $modules[$grade['module_titre']][] = $grade;
            }
            // Afficher chaque module et ses notes
            foreach ($modules as $moduleTitle => $moduleGrades): ?>
                <div class="module">
                    <div class="module-container">
                        <div class="module-header">
                            <span class="arrow"><img src="public/img/arrow-down.svg" alt=""></span>
                            <span><?php echo htmlspecialchars($moduleTitle); ?></span>
                            <span class="average"></span>
                        </div>
                        <div class="module-content">
                            <table class="items-table">
                                <tbody>
                                    <?php foreach ($moduleGrades as $grade): ?>
                                        <tr>
                                            <td>
                                                <span class="title"><?php echo htmlspecialchars($grade['titre']); ?></span><br>
                                                <span class="date"><?php echo date('d/m/Y', strtotime($grade['date_note'])); ?></span>
                                            </td>
                                            <td class="score"><?php echo $grade['note'] . '/' . $grade['note_max']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>