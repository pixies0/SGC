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
            <div class="row align-items-center">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <h6 class="m-0 font-weight-bold text-secondary text-center text-md-left">
                        Clientes Cadastrados
                    </h6>
                </div>

                <div class="col-12 col-md-8">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-stretch">
                        <!-- Campo de pesquisa -->
                        <form method="GET" action="/clientes"
                            class="mb-2 mb-md-0 mr-md-2 w-100 w-md-auto">
                            <div class="input-group input-group-sm">
                                <input type="text" name="busca" class="form-control"
                                    placeholder="Pesquisar por nome..."
                                    value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Botão Novo Cliente -->
                        <a href="/clientes/cadastrar"
                            class="btn btn-secondary btn-sm w-100 w-md-auto">
                            <i class="fas fa-plus"></i> Novo Cliente
                        </a>
                    </div>
                </div>
            </div>


        </div>

        <div class="card-body">
            <?php if (!empty($_GET['busca'])): ?>
                <div class="mb-3">
                    <a href="/clientes" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Limpar pesquisa
                    </a>
                    Mostrando resultados para: <strong><?= htmlspecialchars($_GET['busca']) ?></strong>
                </div>
            <?php endif; ?>

            <!-- Versão Desktop -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered">
                    <colgroup>
                        <col style="width: 60%">
                        <col style="width: 30%">
                        <col style="width: 10%">
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
                                <td class="text-center">
                                    <?php if ($cliente->getRendaFamiliar() !== null): ?>
                                        <?php
                                        $renda = $cliente->getRendaFamiliar();
                                        $classe = $cliente->getClasseRenda();
                                        $styles = [
                                            'badge-classe-a' => 'background-color: #dc3545; color: white;',
                                            'badge-classe-b' => 'background-color: #ffc107; color: #212529;',
                                            'badge-classe-c' => 'background-color: #28a745; color: white;'
                                        ];
                                        ?>
                                        <span class="renda-badge" style="display: inline-block;
                                            padding: 0.4em 0.7em;
                                            border-radius: 0.5rem;
                                            font-weight: bold;
                                            min-width: 90px;
                                            <?= $styles[$classe] ?? '' ?>">
                                            R$ <?= number_format($renda, 2, ',', '.') ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="/clientes/editar/<?= $cliente->getId() ?>"
                                        class="btn btn-sm btn-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/clientes/deletar/<?= $cliente->getId() ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir este cliente?');"
                                        title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Versão Mobile -->
            <div class="d-block d-md-none">
                <?php foreach ($clientes as $cliente): ?>
                    <?php
                    $renda = $cliente->getRendaFamiliar();
                    $classe = $cliente->getClasseRenda();
                    $styles = [
                        'badge-classe-a' => 'background-color: #dc3545; color: white;',
                        'badge-classe-b' => 'background-color: #ffc107; color: #212529;',
                        'badge-classe-c' => 'background-color: #28a745; color: white;'
                    ];
                    ?>
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body p-2">
                            <h6 class="mb-1"><?= htmlspecialchars($cliente->getNome()) ?></h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="renda-badge" style="display: inline-block;
                                    padding: 0.4em 0.7em;
                                    border-radius: 0.5rem;
                                    font-weight: bold;
                                    <?= $styles[$classe] ?? '' ?>">
                                    R$ <?= number_format($renda, 2, ',', '.') ?>
                                </span>
                                <div>
                                    <a href="/clientes/editar/<?= $cliente->getId() ?>"
                                        class="btn btn-sm btn-secondary mr-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/clientes/deletar/<?= $cliente->getId() ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir este cliente?');"
                                        title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>