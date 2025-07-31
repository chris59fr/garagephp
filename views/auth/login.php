<form action="/login" method="POST" class="auth-form">
    <h2>Espace Professionnel</h2>
    <?php if (isset($error)): ?><p class="error-message"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        <?php if (isset($errors['email'])): ?><p class="error-validation"><?= htmlspecialchars($errors['email'][0]) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <?php if (isset($errors['password'])): ?><p class="error-validation"><?= htmlspecialchars($errors['password'][0]) ?></p><?php endif; ?>
    </div>
    <button type="submit">Se connecter</button>
</form>