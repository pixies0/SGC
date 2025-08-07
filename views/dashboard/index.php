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
        setTimeout(function () {
            window.location.href = window.location.pathname;
        }, 1500);
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Relatórios <small class="text-muted"><?= date('F Y') ?></small>
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

    <!-- Cards existentes -->
    <div class="row">
        <!-- Total de Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total de Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['total'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classe A -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Classe A</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['classe_a'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-a fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classe B -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Classe B</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['classe_b'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-b fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classe C -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Classe C</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['classe_c'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-c fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- +18 com renda > média -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Maiores de 18 com renda > média</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['maior18_acima_media'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Média de idade -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Média de Idade</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dashboardData['media_idade'] ?> anos</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-birthday-cake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Média de Renda -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Média de Renda Familiar</div>
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

    <!-- Gráficos -->
    <div class="row">
        <!-- Bar Chart (70%) -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary">Média de Renda por Faixa Etária</h6>
                </div>
                <div class="card-body">
                    <canvas id="barChart" style="max-height:360px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut Chart (30%) -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary">Distribuição de Renda</h6>
                </div>
                <div class="card-body">
                    <canvas id="donutChart" style="max-height:360px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['18-30', '31-45', '46+'],
            datasets: [{
                label: 'R$ Média',
                backgroundColor: '#C0C0C0',
                hoverBackgroundColor: '#696969',
                data: [
                    <?= $dashboardData['media_renda_faixa_etaria']['18-30'] ?>,
                    <?= $dashboardData['media_renda_faixa_etaria']['31-45'] ?>,
                    <?= $dashboardData['media_renda_faixa_etaria']['46+'] ?>
                ]
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Acima da Média', 'Abaixo da Média'],
            datasets: [{
                data: [
                    <?= $dashboardData['clientes_renda_acima_abaixo']['acima'] ?>,
                    <?= $dashboardData['clientes_renda_acima_abaixo']['abaixo'] ?>
                ],
                backgroundColor: ['#D3D3D3', '#808080'],
                hoverBackgroundColor: ['#A9A9A9', '#696969']
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
