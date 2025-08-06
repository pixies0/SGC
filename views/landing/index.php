<style>
    .landing-container {
        position: relative;
        width: 100%;
        min-height: calc(100vh - 190px); /* Gambiarra, frontend não é meu forte */
        background-image: url('/assets/images/background.png');
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .landing-title {
        color: white;
        font-size: 3rem;
        font-weight: bold;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        text-align: center;
    }

    @media (max-width: 768px) {
        .landing-title {
            font-size: 2rem;
        }
    }
</style>

<div class="landing-container">
    <div class="landing-title">
        Sistema Gerenciador de Clientes
    </div>
</div>
