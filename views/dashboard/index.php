<?php
if (!empty($_GET['erro'])): ?>
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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Relatórios
        <small class="text-muted"><?= date('F Y') ?></small>
        </h1>
        <form method="GET" class="form-inline">
            <label for="periodo" class="mr-2">Período:</label>
            <select name="periodo" id="periodo" class="form-control" onchange="this.form.submit()">
                <option value="mes" <?= ($_GET['periodo'] ?? 'mes') === 'mes' ? 'selected' : '' ?>>Mês</option>
                <option value="semana" <?= ($_GET['periodo'] ?? '') === 'semana' ? 'selected' : '' ?>>Semana</option>
                <option value="hoje" <?= ($_GET['periodo'] ?? '') === 'hoje' ? 'selected' : '' ?>>Hoje</option>
            </select>
        </form>
    </div>

    <div class="row">
        <!-- Card Total de Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total de Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['total'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Classe A -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Classe A</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['classe_a'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-a fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Classe B -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Classe B</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['classe_b'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-b fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Classe C -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Classe C</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['classe_c'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-c fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Clientes >18 com renda acima da média -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Maiores de 18 com renda > média</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['maior18_acima_media'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Média de Idade -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Média de Idade</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $dashboardData['media_idade'] ?> anos
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-birthday-cake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Média de Renda Familiar -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Média de Renda Familiar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                R$ <?= number_format($dashboardData['media_renda'], 2, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>