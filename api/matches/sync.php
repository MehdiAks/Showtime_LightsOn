<?php
/*
 * Endpoint API: api/matches/sync.php
 * Rôle: synchroniser une liste de matchs (JSON) vers la base de données.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration et les helpers nécessaires.
 * 2) Vérifie le token de synchronisation (MATCHES_SYNC_TOKEN) si défini.
 * 3) Récupère le JSON soit depuis le body POST, soit depuis un flux distant (FFBB_MATCHES_FEED).
 * 4) Normalise chaque match et l'associe à la nouvelle structure BLOGART26.
 * 5) Insère le match (ou l'ignore si déjà présent), puis retourne un bilan JSON (importés/sautés).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

header('Content-Type: application/json; charset=utf-8');

$expectedToken = getenv('MATCHES_SYNC_TOKEN');
$providedToken = $_GET['token'] ?? '';
if (!empty($expectedToken) && !hash_equals($expectedToken, $providedToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Token invalide.']);
    exit;
}

function fetch_remote_payload(string $url): ?string
{
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
            'header' => "User-Agent: Mozilla/5.0 (compatible; BECBot/1.0)\r\n",
        ],
    ]);

    $payload = @file_get_contents($url, false, $context);
    if ($payload !== false) {
        return $payload;
    }

    return null;
}

function parse_date_and_time(?string $dateRaw, ?string $timeRaw): array
{
    $dateRaw = trim((string) $dateRaw);
    $timeRaw = trim((string) $timeRaw);

    if ($dateRaw === '') {
        return [null, null];
    }

    $hasTime = $timeRaw !== '' || preg_match('/\d{1,2}:\d{2}/', $dateRaw) === 1;
    $timestamp = strtotime(trim($dateRaw . ' ' . $timeRaw));
    if ($timestamp === false) {
        return [null, null];
    }

    $date = date('Y-m-d', $timestamp);
    $time = $hasTime ? date('H:i:s', $timestamp) : null;

    return [$date, $time];
}

function slugify_code(string $value): string
{
    $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($normalized === false) {
        $normalized = $value;
    }
    $normalized = preg_replace('/[^a-zA-Z0-9]+/', '-', (string) $normalized);
    $normalized = strtoupper(trim((string) $normalized, '-'));
    return $normalized !== '' ? $normalized : 'EQUIPE';
}

function normalize_match(array $item): ?array
{
    $home = trim((string) ($item['home'] ?? $item['homeTeam'] ?? $item['teamHome'] ?? $item['domicile'] ?? ''));
    $away = trim((string) ($item['away'] ?? $item['awayTeam'] ?? $item['teamAway'] ?? $item['exterieur'] ?? ''));
    $location = trim((string) ($item['location'] ?? $item['gym'] ?? $item['salle'] ?? ''));
    $status = trim((string) ($item['phase'] ?? $item['status'] ?? $item['etat'] ?? ''));
    $scoreHome = isset($item['scoreHome']) ? (int) $item['scoreHome'] : (isset($item['score_dom']) ? (int) $item['score_dom'] : null);
    $scoreAway = isset($item['scoreAway']) ? (int) $item['scoreAway'] : (isset($item['score_ext']) ? (int) $item['score_ext'] : null);
    $journee = trim((string) ($item['journee'] ?? $item['matchDay'] ?? $item['day'] ?? ''));

    [$matchDate, $matchTime] = parse_date_and_time($item['date'] ?? $item['matchDate'] ?? null, $item['time'] ?? $item['matchTime'] ?? null);

    if ($home === '' || $away === '' || $matchDate === null) {
        return null;
    }

    $journeeValue = $journee;
    if ($journeeValue !== '' && preg_match('/^j/i', $journeeValue) !== 1) {
        $journeeValue = 'J' . $journeeValue;
    }

    return [
        'home' => $home,
        'away' => $away,
        'phase' => $status !== '' ? $status : 'Saison régulière',
        'journee' => $journeeValue !== '' ? $journeeValue : 'J1',
        'matchDate' => $matchDate,
        'matchTime' => $matchTime,
        'location' => $location,
        'scoreHome' => $scoreHome,
        'scoreAway' => $scoreAway,
    ];
}

function is_bec_team(string $teamName): bool
{
    $teamName = strtolower($teamName);
    return str_contains($teamName, 'bec') || str_contains($teamName, 'bordeaux') || str_contains($teamName, 'etudiant');
}

function build_saison_from_date(string $matchDate): string
{
    $year = (int) date('Y', strtotime($matchDate));
    return $year . '-' . ($year + 1);
}

sql_connect();

$payload = null;
if (!empty($_POST['payload'])) {
    $payload = (string) $_POST['payload'];
} else {
    $feedUrl = getenv('FFBB_MATCHES_FEED');
    if (!empty($feedUrl)) {
        $payload = fetch_remote_payload($feedUrl);
    }
}

if ($payload === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Aucun payload trouvé.']);
    exit;
}

$data = json_decode($payload, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Payload JSON invalide.']);
    exit;
}

$imported = 0;
$skipped = 0;
$errors = [];

foreach ($data as $item) {
    if (!is_array($item)) {
        $skipped++;
        continue;
    }

    $normalized = normalize_match($item);
    if (!$normalized) {
        $skipped++;
        continue;
    }

    $homeIsBec = is_bec_team($normalized['home']);
    $awayIsBec = is_bec_team($normalized['away']);
    if (!$homeIsBec && !$awayIsBec) {
        $skipped++;
        continue;
    }

    $becTeamName = $homeIsBec ? $normalized['home'] : $normalized['away'];
    $opponentName = $homeIsBec ? $normalized['away'] : $normalized['home'];
    $codeEquipe = slugify_code($becTeamName);

    $teamStmt = $DB->prepare('SELECT codeEquipe FROM EQUIPE WHERE codeEquipe = :codeEquipe OR nomEquipe = :nomEquipe LIMIT 1');
    $teamStmt->execute([
        ':codeEquipe' => $codeEquipe,
        ':nomEquipe' => $becTeamName,
    ]);
    $existingCode = $teamStmt->fetchColumn();
    if ($existingCode === false) {
        $insertTeam = $DB->prepare(
            'INSERT INTO EQUIPE (codeEquipe, nomEquipe, club, categorie, section, niveau)
             VALUES (:codeEquipe, :nomEquipe, :club, :categorie, :section, :niveau)'
        );
        $insertTeam->execute([
            ':codeEquipe' => $codeEquipe,
            ':nomEquipe' => $becTeamName,
            ':club' => 'Bordeaux étudiant club',
            ':categorie' => 'Non renseigné',
            ':section' => 'Non renseigné',
            ':niveau' => 'Non renseigné',
        ]);
    } else {
        $codeEquipe = (string) $existingCode;
    }

    $saison = build_saison_from_date($normalized['matchDate']);
    $scoreBec = $homeIsBec ? $normalized['scoreHome'] : $normalized['scoreAway'];
    $scoreAdversaire = $homeIsBec ? $normalized['scoreAway'] : $normalized['scoreHome'];

    $existingMatch = $DB->prepare(
        'SELECT numMatch FROM `MATCH` WHERE codeEquipe = :codeEquipe AND clubAdversaire = :clubAdversaire AND dateMatch = :dateMatch LIMIT 1'
    );
    $existingMatch->execute([
        ':codeEquipe' => $codeEquipe,
        ':clubAdversaire' => $opponentName,
        ':dateMatch' => $normalized['matchDate'],
    ]);
    if ($existingMatch->fetchColumn() !== false) {
        $skipped++;
        continue;
    }

    $insertMatch = $DB->prepare(
        'INSERT INTO `MATCH` (codeEquipe, clubAdversaire, saison, phase, journee, dateMatch, heureMatch, lieuMatch, scoreBec, scoreAdversaire)
         VALUES (:codeEquipe, :clubAdversaire, :saison, :phase, :journee, :dateMatch, :heureMatch, :lieuMatch, :scoreBec, :scoreAdversaire)'
    );
    try {
        $insertMatch->execute([
            ':codeEquipe' => $codeEquipe,
            ':clubAdversaire' => $opponentName,
            ':saison' => $saison,
            ':phase' => $normalized['phase'],
            ':journee' => $normalized['journee'],
            ':dateMatch' => $normalized['matchDate'],
            ':heureMatch' => $normalized['matchTime'],
            ':lieuMatch' => $normalized['location'] !== '' ? $normalized['location'] : ($homeIsBec ? 'Domicile' : 'Extérieur'),
            ':scoreBec' => $scoreBec,
            ':scoreAdversaire' => $scoreAdversaire,
        ]);
        $imported++;
    } catch (PDOException $exception) {
        $errors[] = $exception->getMessage();
        $skipped++;
    }
}

echo json_encode([
    'imported' => $imported,
    'skipped' => $skipped,
    'errors' => $errors,
]);
