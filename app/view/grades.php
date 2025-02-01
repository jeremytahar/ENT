<main class="grades-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <span>Notes</span>
    </div>
    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
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
    <?php endif; ?>

    <?php if ($_SESSION['user_role'] === 'professeur'): ?>
        <?php foreach ($teacherCourses as $teacherCourse): ?>
            <h1><?= htmlspecialchars($teacherCourse['titre']) ?></h1>

            <?php if (!empty($assignmentsByCourse[$teacherCourse['id_module']])): ?>
                <?php foreach ($assignmentsByCourse[$teacherCourse['id_module']] as $assignment): ?>
                    <h2><?= htmlspecialchars($assignment['titre']) ?></h2>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nom Prénom</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $students = $studentsByAssignment[$assignment['id_devoir']];
                            foreach ($students as $student):
                                $gradeData = getGradeForAssignment($assignment['id_devoir'], $student['id']);
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['nom']) ?> <?= htmlspecialchars($student['prenom']) ?></td>
                                    <td>
                                        <form method="POST" action="index.php?action=update_grade">
                                            <input type="hidden" name="assignment_id" value="<?= htmlspecialchars($assignment['id_devoir']) ?>">
                                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
                                            <input type="hidden" name="date_note" value="<?= htmlspecialchars($grade['date_note']) ?>">
                                            <input type="number" name="grade"
                                                value="<?= htmlspecialchars($gradeData['note'] ?? '') ?>"
                                                min="0"
                                                max="<?= htmlspecialchars($gradeData['note_max'] ?? '') ?>">
                                                / <?= htmlspecialchars($gradeData['note_max'] ?? '') ?>

                                    </td>
                                    <td>
                                        <button type="submit">Modifier</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun devoir n'a été assigné pour ce module.</p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>



</main>