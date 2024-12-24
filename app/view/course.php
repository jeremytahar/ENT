<div class="course-page">
    <div class="course">
        <div class="course-header">
            <div class="course-infos">
                <h1><?= $course['titre']; ?></h1>
                <span><?= $course['prof_prenom'] . ' ' . $course['prof_nom'] ?></span>
            </div>
            <img src="public/img/courses/<?= $courseId ?>.jpg" alt="">
        </div>

        <div class="course-deposit">
            <h2>Zones de dépot</h2>
            <?php if (!empty($courseHomeworks)): ?>
                <?php foreach ($courseHomeworks as $homework): ?>
                    <div class="homework-item">
                        <?php $formattedDate = date("d/m/Y, H:i", strtotime($homework['date'])); ?>
                        <h3><?= $homework['titre'] ?></h3>
                        <span class="date">Pour le <?= $formattedDate ?></span>
                        <div class="homework-deposit">
                            <div class="deposit-header">
                                <img src="public/img/folder-blue.svg" alt="">
                                <span>Poids maximal: 1 Go</span>
                            </div>
                            <div class="deposit-zone">
                                <div class="drop-icon"><img src="public/img/upload.svg" alt=""></div>
                                <p>Vous pouvez glisser des fichiers ici pour les ajouter</p>
                                <input type="file" id="file-input" class="file-input" multiple hidden>
                            </div>
                            <div class="file-list" id="file-list"></div>
                        </div>
                        <button>Enregistrer</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun devoir à rendre.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="course-files">
        <?php if (!empty($courseFiles)): ?>
        <div class="files">
            <h2>Cours et TP</h2>
            <?php foreach ($courseFiles as $file): ?>
                <div class="file">
                    <img src="public/img/file.svg" alt="">
                    <a href="public/uploads/courses-files/<?= $courseId ?>/<?= $file['nom_fichier'] ?>" download><?= $file['nom_fichier'] ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>Aucun fichier disponible.</p>
        <?php endif; ?>
    </div>

    <script>
        const fileDropZone = document.querySelector('.deposit-zone');
        const fileInput = document.querySelector('.file-input');
        const fileList = document.getElementById('file-list');

        function displayFiles(files) {
            fileList.innerHTML = ''; // Vider la liste
            const ul = document.createElement('ul');
            for (const file of files) {
                const li = document.createElement('li');
                li.textContent = `${file.name} (${(file.size / 1024).toFixed(2)} Ko)`;
                ul.appendChild(li);
            }
            fileList.appendChild(ul);
        }

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
            displayFiles(files);
        });

        fileDropZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            displayFiles(files);
        });
    </script>