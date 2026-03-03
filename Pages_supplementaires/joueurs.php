<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [ROOT_URL . '/src/css/club-structure.css'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

sql_connect();

$ba_bec_players = [];
try {
    $playersQuery = "SELECT
            j.numJoueur,
            j.prenomJoueur,
            j.nomJoueur,
            j.urlPhotoJoueur,
            j.dateNaissance,
            j.numeroMaillot,
            j.posteJoueur,
            j.clubsPrecedents,
            e.nomEquipe
        FROM JOUEUR j
        LEFT JOIN EQUIPE e ON j.codeEquipe = e.codeEquipe
        ORDER BY j.nomJoueur ASC";

    $playersStmt = $DB->prepare($playersQuery);
    $playersStmt->execute();
    $ba_bec_players = $playersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    $ba_bec_players = [];
}

$defaultPhoto = ROOT_URL . '/src/images/image-defaut.jpeg';

function player_photo_url(?string $photo, string $defaultPhoto): string
{
    if (!$photo) {
        return $defaultPhoto;
    }

    if (preg_match('/^(https?:\/\/|\/)/', $photo)) {
        return $photo;
    }

    return ROOT_URL . '/src/uploads/' . $photo;
}

function format_age(?string $birthDate): string
{
    if (!$birthDate) {
        return 'Non renseigné';
    }
    $date = DateTime::createFromFormat('Y-m-d', $birthDate);
    if (!$date) {
        return 'Non renseigné';
    }
    return (string) $date->diff(new DateTime())->y;
}

function format_clubs(?string $clubs): string
{
    if (!$clubs) {
        return 'Non renseigné';
    }
    return $clubs;
}

function format_poste(?int $poste): string
{
    $labels = [
        1 => 'Meneur',
        2 => 'Arrière',
        3 => 'Ailier',
        4 => 'Ailier fort',
        5 => 'Pivot',
    ];
    if (!$poste) {
        return 'Non renseigné';
    }
    return $labels[$poste] ?? ('Poste ' . $poste);
}

function normalize_team(?string $team): string
{
    if (!$team) {
        return '';
    }
    return trim($team);
}


$allTeams = [];
$allAges = [];
foreach ($ba_bec_players as $player) {
    $team = normalize_team($player['nomEquipe'] ?? null);
    if ($team !== '') {
        $allTeams[$team] = true;
    }
    $ageValue = format_age($player['dateNaissance'] ?? null);
    if (is_numeric($ageValue)) {
        $allAges[] = (int) $ageValue;
    }
}
$allTeams = array_keys($allTeams);
sort($allTeams, SORT_NATURAL | SORT_FLAG_CASE);
$minAvailableAge = !empty($allAges) ? min($allAges) : 16;
$maxAvailableAge = !empty($allAges) ? max($allAges) : 45;
?>

<section class="club-page">
    <header class="club-header">
        <h1>Joueurs du club</h1>
        <p>
            Retrouvez les joueurs du BEC, leurs postes, leurs parcours et l'équipe dans laquelle ils évoluent.
        </p>
    </header>

    <?php if (empty($ba_bec_players)) : ?>
        <p>Aucun joueur n'est encore enregistré. Les fiches seront ajoutées prochainement.</p>
    <?php else : ?>
        <section class="club-filters" aria-label="Filtres des joueurs">
            <form id="players-filter-form" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <p class="club-filter-label">Postes</p>
                    <div class="club-filter-checkboxes">
                        <?php foreach ([1 => 'Meneur', 2 => 'Arrière', 3 => 'Ailier', 4 => 'Ailier fort', 5 => 'Pivot'] as $posteValue => $posteLabel) : ?>
                            <label class="club-check-option">
                                <input type="checkbox" name="postes[]" value="<?php echo $posteValue; ?>">
                                <span><?php echo $posteLabel; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <label for="team-filter" class="club-filter-label">Équipe</label>
                    <select id="team-filter" class="form-select" name="team">
                        <option value="">Toutes les équipes</option>
                        <?php foreach ($allTeams as $teamName) : ?>
                            <option value="<?php echo htmlspecialchars($teamName, ENT_QUOTES); ?>"><?php echo htmlspecialchars($teamName); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-lg-4">
                    <p class="club-filter-label">Âge</p>
                    <div class="club-age-slider" data-min="<?php echo $minAvailableAge; ?>" data-max="<?php echo $maxAvailableAge; ?>">
                        <div class="club-age-values">
                            <span id="age-min-label"><?php echo $minAvailableAge; ?></span>
                            <span>à</span>
                            <span id="age-max-label"><?php echo $maxAvailableAge; ?></span>
                            <span>ans</span>
                        </div>
                        <input type="range" id="age-min" name="age_min" min="<?php echo $minAvailableAge; ?>" max="<?php echo $maxAvailableAge; ?>" value="<?php echo $minAvailableAge; ?>">
                        <input type="range" id="age-max" name="age_max" min="<?php echo $minAvailableAge; ?>" max="<?php echo $maxAvailableAge; ?>" value="<?php echo $maxAvailableAge; ?>">
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                    <button type="button" class="btn btn-outline-secondary" id="players-reset">Réinitialiser</button>
                    <p class="news-filters__count mb-0 ms-lg-auto" id="players-count"></p>
                </div>
            </form>
        </section>

        <div class="club-grid" id="players-grid">
            <?php foreach ($ba_bec_players as $player) : ?>
                <?php
                $playerAge = format_age($player['dateNaissance'] ?? null);
                $playerPoste = (int) ($player['posteJoueur'] ?? 0);
                $playerTeam = normalize_team($player['nomEquipe'] ?? null);
                ?>
                <article
                    class="club-card"
                    data-player-poste="<?php echo $playerPoste; ?>"
                    data-player-age="<?php echo is_numeric($playerAge) ? (int) $playerAge : ''; ?>"
                    data-player-team="<?php echo htmlspecialchars($playerTeam, ENT_QUOTES); ?>"
                >
                    <img src="<?php echo htmlspecialchars(player_photo_url($player['urlPhotoJoueur'], $defaultPhoto)); ?>" alt="<?php echo htmlspecialchars($player['prenomJoueur'] . ' ' . $player['nomJoueur']); ?>">
                    <div class="club-card-body">
                        <h2 class="club-card-title">
                            <?php echo htmlspecialchars($player['prenomJoueur'] . ' ' . $player['nomJoueur']); ?>
                        </h2>
                        <p class="club-card-meta">Numéro : <?php echo htmlspecialchars($player['numeroMaillot'] ?? 'Non renseigné'); ?></p>
                        <p class="club-card-meta">Poste : <?php echo htmlspecialchars(format_poste($player['posteJoueur'] ?? null)); ?></p>
                        <p class="club-card-meta">Âge : <?php echo htmlspecialchars($playerAge); ?></p>
                        <?php if (!empty($player['nomEquipe'])) : ?>
                            <p class="club-card-meta">Équipe : <?php echo htmlspecialchars($player['nomEquipe']); ?></p>
                        <?php endif; ?>
                        <p class="club-card-meta">Clubs précédents : <?php echo htmlspecialchars(format_clubs($player['clubsPrecedents'] ?? null)); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="club-empty-message" id="players-empty" hidden>Aucun joueur ne correspond aux filtres sélectionnés.</p>
    <?php endif; ?>
</section>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('players-filter-form');
    const cards = Array.from(document.querySelectorAll('#players-grid .club-card'));
    const teamFilter = document.getElementById('team-filter');
    const ageMinInput = document.getElementById('age-min');
    const ageMaxInput = document.getElementById('age-max');
    const ageMinLabel = document.getElementById('age-min-label');
    const ageMaxLabel = document.getElementById('age-max-label');
    const countLabel = document.getElementById('players-count');
    const emptyMessage = document.getElementById('players-empty');
    const resetButton = document.getElementById('players-reset');

    if (!form || cards.length === 0) {
        return;
    }

    const getSelectedPostes = () => Array.from(form.querySelectorAll('input[name="postes[]"]:checked')).map((input) => input.value);

    const updateAgeLabels = () => {
        ageMinLabel.textContent = ageMinInput.value;
        ageMaxLabel.textContent = ageMaxInput.value;
    };

    const normalizeAgeInputs = () => {
        let min = parseInt(ageMinInput.value, 10);
        let max = parseInt(ageMaxInput.value, 10);

        if (min > max) {
            if (document.activeElement === ageMinInput) {
                max = min;
                ageMaxInput.value = String(max);
            } else {
                min = max;
                ageMinInput.value = String(min);
            }
        }

        updateAgeLabels();
        return { min, max };
    };

    const updateCount = (visibleCount) => {
        countLabel.textContent = `${visibleCount} joueur${visibleCount > 1 ? 's' : ''} affiché${visibleCount > 1 ? 's' : ''}`;
        emptyMessage.hidden = visibleCount !== 0;
    };

    const applyFilters = () => {
        const selectedPostes = getSelectedPostes();
        const selectedTeam = teamFilter.value;
        const { min, max } = normalizeAgeInputs();

        let visibleCount = 0;

        cards.forEach((card) => {
            const cardPoste = card.dataset.playerPoste;
            const cardAge = parseInt(card.dataset.playerAge, 10);
            const cardTeam = card.dataset.playerTeam || '';

            const posteMatch = selectedPostes.length === 0 || selectedPostes.includes(cardPoste);
            const teamMatch = selectedTeam === '' || selectedTeam === cardTeam;
            const ageMatch = !Number.isNaN(cardAge) && cardAge >= min && cardAge <= max;

            const shouldShow = posteMatch && teamMatch && ageMatch;
            card.hidden = !shouldShow;

            if (shouldShow) {
                visibleCount += 1;
            }
        });

        updateCount(visibleCount);
    };

    form.addEventListener('change', applyFilters);
    form.addEventListener('input', (event) => {
        if (event.target === ageMinInput || event.target === ageMaxInput) {
            applyFilters();
        }
    });

    resetButton.addEventListener('click', () => {
        form.reset();
        applyFilters();
    });

    applyFilters();
});
</script>
