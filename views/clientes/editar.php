<h1 class="h3 mb-4 text-gray-800">Editar Cliente</h1>

<!-- Alertas de erro/sucesso -->
<?php if (!empty($_GET['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars($_GET['erro']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($_GET['sucesso']) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = '/clientes';
        }, 1250);
    </script>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" action="/clientes/editar/<?= $cliente->getId() ?>">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" class="form-control" id="nome" name="nome"
                    value="<?= htmlspecialchars($cliente->getNome()) ?>" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF *</label>
                <input type="text" class="form-control" id="cpf" name="cpf"
                    value="<?= htmlspecialchars($cliente->getCpf()) ?>"
                    pattern="\d{11}" title="Somente números (11 dígitos)" required>
                <small class="form-text text-muted">Somente números (11 dígitos)</small>
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento *</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                    value="<?= $cliente->getDataNascimento()->format('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="renda_familiar">Renda Familiar</label>
                <input type="number" step="0.01" class="form-control" id="renda_familiar" name="renda_familiar"
                    value="<?= $cliente->getRendaFamiliar() ?? '' ?>">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/clientes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para garantir apenas números no CPF -->
<script>
    document.getElementById('cpf').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
    });
</script>