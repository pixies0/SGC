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
            window.location.href = window.location.pathname;
        }, 1500);
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-secondary">Clientes Cadastrados</h6>
                <a href="/clientes/cadastrar" class="btn btn-secondary btn-sm">
                    <i class="fas fa-plus"></i> Novo Cliente
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <colgroup>
                        <col style="width: 60%"> <!-- Coluna Nome -->
                        <col style="width: 30%"> <!-- Coluna Renda -->
                        <col style="width: 10%"> <!-- Coluna Ações -->
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Renda Familiar</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente->getNome()) ?></td>
                                <td>
                                    <?= $cliente->getRendaFamiliar() !== null
                                        ? 'R$ ' . number_format($cliente->getRendaFamiliar(), 2, ',', '.')
                                        : '-' ?>
                                </td>
                                <td class="text-center p-1">
                                    <div class="action-buttons"> <!-- Aumentei o gap para 2 -->
                                        <!-- Botão Editar -->
                                        <a href="/clientes/editar/<?= $cliente->getId() ?>"
                                            class="btn btn-sm btn-secondary py-1 px-2"
                                            title="Editar">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>

                                        <!-- Botão Deletar -->
                                        <a href="/clientes/deletar/<?= $cliente->getId() ?>"
                                            class="btn btn-sm btn-danger py-1 px-2"
                                            title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                                            <i class="fas fa-trash-alt fa-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>