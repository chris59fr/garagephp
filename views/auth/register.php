<form action="/register" method="POST" class="auth-form">
    <h2>Créer un compte</h2>
    <?php if (isset($error)): ?><p class="error-message"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
    <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
        <?php if (isset($errors['username'])): ?><p class="error-validation"><?= htmlspecialchars($errors['username'][0]) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        <?php if (isset($errors['email'])): ?><p class="error-validation"><?= htmlspecialchars($errors['email'][0]) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?><p class="error-validation"><?= htmlspecialchars($errors['password'][0]) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label for="password_confirm">Confirmer le mot de passe</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
        <?php if (isset($errors['password_confirm'])): ?><p class="error-validation"><?= htmlspecialchars($errors['password_confirm'][0]) ?></p><?php endif; ?>
    </div>
    <button type="submit">S'inscrire</button>
</form>