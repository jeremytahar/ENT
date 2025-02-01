<main class="profile-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <span>Profil</span>
    </div>
    <div class="profile-page-container">
        <div class="col1">
            <div class="profile-picture-container">
                <div class="logo">
                    <span class="logo-title">
                        <img src="public/img/logo-title.svg">
                        <span>Gustave Eiffel</span>
                    </span>
                </div>
                <div class="picture-container">
                    <form action="index.php?action=updateProfilePicture" method="post" enctype="multipart/form-data" id="profile-form">
                        <?php
                        $role = $user['role'] === 'teacher' ? 'teachers' : 'students';
                        $basePath = "public/uploads/profile-pictures/$role/" . $_SESSION['user_id'];
                        $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];
                        $profilePicturePath = null;
                        foreach ($allowedExtensions as $extension) {
                            $filePath = "$basePath.$extension";
                            if (file_exists($filePath)) {
                                $profilePicturePath = $filePath;
                                break;
                            }
                        }
                        if ($profilePicturePath !== null && file_exists($profilePicturePath)) {
                            echo "<img src='$profilePicturePath' class='profile-picture' alt='Profile Picture'>";
                        } else {
                            echo "<img src='public/img/default-picture.svg' class='profile-picture' alt='Default Profile Picture'>";
                        }
                        ?>
                        <label for="profile-picture" class="profile-picture-label">
                            <div class="edit-icon">
                                <img src="public/img/pen.svg" alt="Modifier photo de profil">
                            </div>
                        </label>
                        <input type="file" name="profile-picture" id="profile-picture" style="display: none;" accept="image/jpeg, image/png, image/jpg, image/webp, image/heic, image/heif">
                    </form>
                </div>
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <p><?= htmlspecialchars($_GET['error']) ?></p>
                    </div>
                <?php endif; ?>
                <div class="quick-info">
                    <span class="name"><?= $user['prenom'] . ' ' . $user['nom'] ?></span>
                    <div class="line"></div>
                    <div class="formation-info">
                        <span><?= $user['formation'] ?></span> - <span><?= $user['parcours'] ?></span>
                    </div>
                </div>
            </div>
            <div class="info-container">
                <h2>Informations</h2>
                <hr>
                <div class="info">
                    <ul>
                        <li>
                            <span class="info-label">Identifiant</span> <br>
                            <span class="info-value"><?= $user['login'] ?></span>
                        </li>
                        <li>
                            <span class="info-label">Adresse mail</span> <br>
                            <span class="info-value"><?= $user['email'] ?></span>
                        </li>
                        <li>
                            <span class="info-label">Prénom</span> <br>
                            <span class="info-value"></span><?= $user['prenom'] ?></span>
                        </li>
                        <li>
                            <span class="info-label">Nom</span> <br>
                            <span class="info-value"></span><?= $user['nom'] ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="absences-container">
            <h2>Récapitulatif des absences</h2>
            <hr>
            <?php
            $totalAbsences = 0;
            foreach ($absences as $absence) {
                $timeParts = explode(':', $absence['duree']);
                $seconds = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + ($timeParts[2] ?? 0);
                $totalAbsences += $seconds;
            }
            $totalHours = floor($totalAbsences / 3600);
            $totalMinutes = floor(($totalAbsences % 3600) / 60);
            ?>
            <div class="total-absences">
                <span>Total d'heures d'absences:</span>
                <span class="absence-count"><?= $totalHours . 'h' . str_pad($totalMinutes, 2, '0', STR_PAD_LEFT) ?></span>
            </div>
            <ul>
                <?php foreach ($absences as $absence): ?>
                    <?php
                    $formatter = new IntlDateFormatter('fr_FR');
                    $formatter->setPattern('EEEE dd MMMM YYYY');
                    $dateFormatted = $formatter->format(new DateTime($absence['date_absence']));
                    $dateFormatted = mb_convert_case($dateFormatted, MB_CASE_TITLE, 'UTF-8');
                    $heureDebut = date('H\hi', strtotime($absence['heure_debut']));
                    $heureFin = date('H\hi', strtotime($absence['heure_fin']));
                    ?>
                    <li class="absence">
                        <span class="absence-date"><?= $dateFormatted ?></span> <br>
                        <span class="absence-hours">De <?= $heureDebut . ' à ' . $heureFin . ' - ' . date('H\hi', strtotime($absence['duree'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>