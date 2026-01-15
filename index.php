<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        $_POST['email'] === 'admin@goat.it' &&
        $_POST['password'] === 'passwordadmin26'
    ) {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Credenziali errate';
}
?>
<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>GOAT-Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6f7f9, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            padding: 28px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .login-title {
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .btn-goat {
            background: #212529;
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-goat:hover {
            background: #000;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <h3 class="login-title mb-1">GOAT</h3>
        <div class="login-subtitle">Admin dashboard</div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <input class="form-control" name="email" type="email" placeholder="Email" required>
            </div>

            <div class="mb-3">
                <input class="form-control" type="password" name="password" placeholder="Password" required>
            </div>

            <button class="btn btn-goat w-100">
                Accedi
            </button>
        </form>

    </div>

</body>

</html>