<div class="directory-page">
    <div class="search-teachers">
        <div class="form-group">
            <input type="text" class="search-bar" id="searchInput" placeholder="Rechercher" />
            <label for="login">Rechercher un professeur</label><br>
        </div>
    </div>
    <p id="noResults" style="display: none; font-weight: bold; text-align: center;">Aucun professeur trouvé.</p>
    <div class="teachers">
        <?php foreach ($teachers as $teacher): ?>
            <div class="teacher">
                <h2><?= htmlspecialchars($teacher['prenom']) ?> <?= htmlspecialchars($teacher['nom']) ?></h2>
                <p><img src="public/img/mail.svg" alt=""> <a href="mailto:<?= htmlspecialchars($teacher['email']) ?>"> <?= htmlspecialchars($teacher['email']) ?></a></p>
                <p><img src="public/img/discord.svg" alt=""> <?= htmlspecialchars($teacher['discord']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>