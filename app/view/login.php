<div class="connexion_page">
    <h1><span class="logo_title"><img src="public/img/logo-title.svg"><span>GUSTAVE EIFFEL</span></span></h1>

    <form class="form_connexion" action="?action=checkLogin" method="POST">
        <h2><span class="logo_title"><img src="public/img/logo-title.svg"><span>Connexion</span></span></h2>


        <div class="form-inputs">
            <div class="form-group">
                <input type="text" name="login" id="login" placeholder="Identifiant">
                <label for="login">Identifiant </label><br>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="login-password" placeholder="Mot de passe">
                <label for="login-password">Mot de passe </label><br>
                <span id="toggle-login-password" class="eye-icon">
                    <img src="public/img/eye.svg" alt="Afficher mot de passe" />
                </span>
            </div>
        </div>

        <input type="submit" name="submit" value="Se connecter">
        <a href="?action=forgot_password">Mot de passe oublié ?</a>
    </form>

</div>