<?php
session_start();

require __DIR__ . '/../goat/config/database.php';

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    die('Forbidden');
}

$matchId = (int) ($_POST['match_id'] ?? 0);
if ($matchId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("
  SELECT
    m.id,
    m.status,
    a.credits,
    a.created_by AS tutor_id,
    m.participant_id AS student_id
  FROM announcement_matches m
  JOIN announcements a ON a.id = m.announcement_id
  WHERE m.id = ?
    AND m.status = 'pending'
  LIMIT 1
");
$stmt->execute([$matchId]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    header('Location: dashboard.php');
    exit;
}

$pdo->beginTransaction();

try {
    $pdo->prepare("
      UPDATE announcement_matches
      SET status = 'confirmed', validated_at = NOW()
      WHERE id = ?
    ")->execute([$matchId]);

    $credits = (int) $match['credits'];

    $pdo->prepare("
      UPDATE users SET goat_coins = goat_coins + ?
      WHERE id = ?
    ")->execute([$credits, $match['tutor_id']]);

    $pdo->prepare("
      UPDATE users SET goat_coins = goat_coins - ?
      WHERE id = ?
    ")->execute([$credits, $match['student_id']]);

    $pdo->commit();

} catch (Throwable $e) {
    $pdo->rollBack();
}

header('Location: dashboard.php');
exit;