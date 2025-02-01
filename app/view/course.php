<main class="course-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <a href="?action=dashboard">Tableau de bord</a> > <span><?= $course['titre'] ?></span>
    </div>
    <div class="course">
        <div class="course-header">
            <div class="course-infos">
                <h1><?= $course['titre']; ?></h1>
                <span><?= $course['prof_prenom'] . ' ' . $course['prof_nom'] ?></span>
            </div>
            <img src="public/img/courses/<?= $courseId ?>.jpg" alt="">
        </div>

        <?php
        $uploadDir = "public/uploads/students-homework/";
        ?>

        <div class="course-deposit">
            <h2>Devoirs</h2>

            <div class="homework-items">
                <?php if (!empty($courseHomeworks)): ?>
                    <?php foreach ($courseHomeworks as $homework): ?>
                        <div class="homework-item">
                            <?php $formattedDate = date("d/m/Y, H:i", strtotime($homework['date'])); ?>
                            <h3><?= $homework['titre'] ?></h3>
                            <span class="date">Pour le <?= $formattedDate ?></span>

                            <?php
                            $homeworkDir = $uploadDir . "{$homework['id_devoir']}/";
                            $userDirs = glob($homeworkDir . "*", GLOB_ONLYDIR);
                            $uploadedFiles = glob($homeworkDir . "*");
                            ?>

                            <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                                <form action="?action=homework_upload" method="POST" enctype="multipart/form-data">
                                    <div class="homework-deposit">
                                        <div class="deposit-header">
                                            <img src="public/img/folder-blue.svg" alt="">
                                            <span>Poids maximal: 1 Go</span>
                                        </div>

                                        <?php
                                        $userHomeworkDir = $homeworkDir . $_SESSION['user_id'] . "/";
                                        $uploadedFiles = glob($userHomeworkDir . "*");
                                        ?>

                                        <div class="deposit-zone" <?php if (!empty($uploadedFiles)) echo 'style="pointer-events: none; background-color: #f0f0f0;"'; ?>>
                                            <div class="drop-icon"><img src="public/img/upload.svg" alt=""></div>
                                            <p>Vous pouvez glisser un fichier ici pour l'ajouter.</p>
                                            <input type="file" name="homework_file" id="file-input" class="file-input" hidden <?php if (!empty($uploadedFiles)) echo 'disabled'; ?>>
                                        </div>

                                        <div class="file-list" id="file-list">
                                            <?php if (!empty($uploadedFiles)): ?>
                                                <ul>
                                                    <?php foreach ($uploadedFiles as $file): ?>
                                                        <?php $fileName = basename($file); ?>
                                                        <li><?= $fileName ?>
                                                        <a href="?action=delete_file&file=<?= urlencode($fileName) ?>&homework_id=<?= $homework['id_devoir'] ?>&course_id=<?= $courseId ?>&user_id=<?= $_SESSION['user_id'] ?>">Supprimer</a>

                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>

                                        <input type="hidden" name="homework_id" value="<?= $homework['id_devoir']; ?>">
                                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                                        <input type="hidden" name="course_id" value="<?= $_GET['course_id']; ?>">
                                    </div>
                                    <button type="submit" id="submit-homework" <?php if (!empty($uploadedFiles)) echo 'disabled'; ?>>Enregistrer</button>
                                </form>

                            <?php else: ?>
                                <div class="file-list">
                                    <h4>Fichiers déposés :</h4>
                                    <?php if (!empty($userDirs)): ?>
                                        <ul>
                                            <?php foreach ($userDirs as $userDir): ?>
                                                <?php
                                                $userId = basename($userDir);
                                                $userInfo = getUserById($userId);
                                                $prenomNom = htmlspecialchars($userInfo['prenom'] . ' ' . $userInfo['nom']);
                                                $userFiles = glob($userDir . "/*");
                                                foreach ($userFiles as $file):
                                                    $fileName = basename($file);
                                                ?>
                                                    <li>
                                                        <strong><?= $prenomNom ?>:</strong>
                                                        <a href="<?= htmlspecialchars($file) ?>" download><?= htmlspecialchars($fileName) ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Aucun fichier déposé.</p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun devoir à rendre.</p>
                <?php endif; ?>
            </div>

            <?php if ($_SESSION['user_role'] === 'professeur'): ?>
    <div class="add-homework">
        <h4>Ajouter un devoir</h4>
        <form action="?action=addHomework" method="POST">
            <div class="form-group">
                <label for="homework-title">Titre du devoir</label>
                <input type="text" name="title" id="homework-title" required>
            </div>
            <div class="form-group">
                <label for="homework-deadline">Date limite</label>
                <input type="datetime-local" name="date" id="homework-deadline" required>
            </div>
            <div class="form-group">
                <label for="homework-max-grade">Note maximum</label>
                <input type="number" name="max_grade" id="homework-max-grade" required>
            </div>
            <div class="form-group">
                <label for="homework-type">Type de devoir</label>
                <select name="type" id="homework-type" required>
                    <option value="depot">depot</option>
                    <option value="evaluation">evaluation</option>
                </select>
            </div>
            <input type="hidden" name="course_id" value="<?= $_GET['course_id']; ?>">
            <button type="submit">Créer</button>
        </form>
    </div>
<?php endif; ?>


        </div>

    </div>

    <div class="course-files">
        <?php if (!empty($courseFiles)): ?>
            <div class="files">
                <h3>Cours et TP</h3>
                <?php foreach ($courseFiles as $file): ?>
                    <div class="file">
                        <img src="public/img/file.svg" alt="">
                        <a href="public/uploads/courses-files/<?= $courseId ?>/<?= $file['nom_fichier'] ?>" download><?= $file['nom_fichier'] ?></a>

                        <?php if ($_SESSION['user_role'] === 'professeur'): ?>
                            <form action="?action=delete_course_file" method="POST" class="delete-file-form">
                                <input type="hidden" name="file_name" value="<?= $file['nom_fichier'] ?>">
                                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                <button type="submit" class="delete-button">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun fichier disponible.</p>
        <?php endif; ?>
        <?php if ($_SESSION['user_role'] === 'professeur'): ?>
            <div class="add-files">
                <h4>Ajouter un fichier</h4>
                <form action="?action=upload_course_file" method="POST" enctype="multipart/form-data">
                    <div class="file-upload">
                        <input type="file" name="course_file" required>
                    </div>
                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                    <button type="submit">Téléverser</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>


<script>
    const fileDropZones = document.querySelectorAll('.deposit-zone');
    const fileInputs = document.querySelectorAll('.file-input');
    const fileLists = document.querySelectorAll('.file-list');

    function displayFiles(files, fileList) {
        fileList.innerHTML = '';
        const ul = document.createElement('ul');
        for (const file of files) {
            const li = document.createElement('li');
            li.textContent = `${file.name} (${(file.size / 1024).toFixed(2)} Ko)`;
            ul.appendChild(li);
        }
        fileList.appendChild(ul);
    }

    fileDropZones.forEach((fileDropZone, index) => {
        const fileInput = fileInputs[index];
        const fileList = fileLists[index];

        fileDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileDropZone.style.backgroundColor = '#e6f7ff';
        });

        fileDropZone.addEventListener('dragleave', () => {
            fileDropZone.style.backgroundColor = '#f9f9f9';
        });

        fileDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileDropZone.style.backgroundColor = '#f9f9f9';
            const files = e.dataTransfer.files;
            displayFiles(files, fileList);
        });

        fileDropZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            displayFiles(files, fileList);
        });
    });
</script>