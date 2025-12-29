<?php
session_start();
require __DIR__ . '/../goat/config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("
SELECT
  m.id,
  m.status,
  m.validation_code,
  a.title,
  a.credits,
  u1.name AS creator,
  u2.name AS participant
FROM announcement_matches m
JOIN announcements a ON a.id = m.announcement_id
JOIN users u1 ON u1.id = m.creator_id
JOIN users u2 ON u2.id = m.participant_id
ORDER BY m.created_at DESC
");

$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>GOAT · Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">GOAT Admin Dashboard</span>

            <form method="post" action="logout.php">
                <button class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- ====== CONTENUTO ====== -->
    <div class="container">

        <h4 class="mb-3">
            Match
            <span class="badge bg-secondary"><?= count($matches) ?></span>
        </h4>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Annuncio</th>
                            <th>Creatore</th>
                            <th>Partecipante</th>
                            <th>Crediti</th>
                            <th>Codice</th>
                            <th>Stato</th>
                            <th class="text-end">Azione</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (empty($matches)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Nessun match presente
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($matches as $m): ?>
                            <tr>
                                <td><?= htmlspecialchars($m['title']) ?></td>
                                <td><?= htmlspecialchars($m['creator']) ?></td>
                                <td><?= htmlspecialchars($m['participant']) ?></td>

                                <td>
                                    <strong><?= (int) $m['credits'] ?></strong>
                                </td>

                                <td>
                                    <code><?= htmlspecialchars($m['validation_code']) ?></code>
                                </td>

                                <td>
                                    <?php if ($m['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">CONFIRMED</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <?php if ($m['status'] === 'pending'): ?>
                                        <form method="POST" action="validate_match.php" class="d-inline">
                                            <input type="hidden" name="match_id" value="<?= (int) $m['id'] ?>">
                                            <button class="btn btn-success btn-sm"
                                                onclick="return confirm('Confermare questo match?')">
                                                Convalida
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>
        </div>

    </div>

</body>

</html>