<div class="directory-page">
    <div class="breadcrumb">
        <a href="?action=home">Accueil</a> > <span>Annuaire</span>
    </div>
    <div class="search-teachers">
        <div class="form-group">
            <input type="text" class="search-bar" id="searchInput" placeholder="Rechercher" />
            <label for="searchInput">Rechercher un professeur</label><br>
        </div>
    </div>
    <p class="noResults" style="display: none; font-weight: bold; text-align: center;">Aucun professeur trouvé.</p>
    <div class="teachers">
        <?php foreach ($teachers as $teacher): ?>
            <div class="teacher">
                <h2><?= htmlspecialchars($teacher['prenom']) ?> <?= htmlspecialchars($teacher['nom']) ?></h2>
                <p><img src="public/img/mail.svg" alt=""> <a href="mailto:<?= htmlspecialchars($teacher['email']) ?>"> <?= htmlspecialchars($teacher['email']) ?></a></p>
                <p><img src="public/img/discord.svg" alt=""> <span><?= htmlspecialchars($teacher['discord']) ?></span></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="directory-form">
        <form action="">
            <h2>Contact</h2>
            <div class="form-group">
                <input type="text" name="recipient" id="recipient" placeholder="Destinataire" />
                <label for="recipient">Destinataire</label><br>
            </div>
            <div class="form-group">
                <input type="text" name="subject" id="subject" placeholder="Objet" />
                <label for="subject">Objet</label><br>
            </div>
            <div class="form-group">
                <textarea name="message" id="message" placeholder="Message"></textarea>
                <label for="message">Message</label><br>
            </div>
            <input type="submit" name="submit" value="Envoyer le message" class="submit-btn">
        </form>
    </div>
</div>