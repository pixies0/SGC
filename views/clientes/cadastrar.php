<h1 class="h3 mb-4 text-gray-800">Cadastrar Novo Cliente</h1>

<?php if (!empty($_GET['erro'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_GET['erro']) ?>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sucesso'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_GET['sucesso']) ?>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = window.location.pathname;
        }, 1250 );
    </script>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="/clientes/cadastrar" method="POST">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome"
                    maxlength="150" required
                    value="<?= htmlspecialchars($formData['nome'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="cpf">CPF *</label>
                <input type="text" class="form-control" id="cpf" name="cpf"
                    maxlength="11"
                    pattern="\d{11}"
                    placeholder="Somente números (11 dígitos)"
                    required
                    title="Digite 11 números do CPF (sem pontos ou traço)"
                    value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento *</label>
                <input type="date" class="form-control" id="data_nascimento"
                    name="data_nascimento" max="<?= date('Y-m-d') ?>" required
                    value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="renda_familiar">Renda Familiar</label>
                <input type="number" class="form-control" id="renda_familiar"
                    name="renda_familiar" min="0" step="0.01"
                    value="<?= htmlspecialchars($_POST['renda_familiar'] ?? '0') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="/clientes" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>