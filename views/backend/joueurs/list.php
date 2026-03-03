<?php
/*
 * Vue d'administration (liste) pour le module joueurs.
 * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
 * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
 * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
 * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
 * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

sql_connect();

$ba_bec_is_missing_table = [
    'JOUEUR' => sql_is_missing_table('JOUEUR'),
    'EQUIPE' => sql_is_missing_table('EQUIPE'),
];

$ba_bec_missing_table_labels = [
    'JOUEUR' => 'JOUEUR',
    'EQUIPE' => 'EQUIPE',
];

$ba_bec_players = [];
$ba_bec_teams = [];
$ba_bec_selected_teams = $_GET['teams'] ?? [];
if (!is_array($ba_bec_selected_teams)) {
    $ba_bec_selected_teams = [$ba_bec_selected_teams];
}
$ba_bec_selected_teams = array_values(array_unique(array_filter(array_map('strval', $ba_bec_selected_teams), 'strlen')));

if (!in_array(true, $ba_bec_is_missing_table, true)) {
    $ba_bec_teams = $DB->query('SELECT codeEquipe, nomEquipe FROM EQUIPE ORDER BY nomEquipe ASC')->fetchAll(PDO::FETCH_ASSOC);

    $orderBy = 'e.nomEquipe IS NULL, e.nomEquipe ASC, j.nomJoueur ASC, j.prenomJoueur ASC';

    $whereClause = '';
    $queryParams = [];
    if (!empty($ba_bec_selected_teams)) {
        $placeholders = [];
        foreach ($ba_bec_selected_teams as $teamIndex => $teamCode) {
            $placeholder = ':team' . $teamIndex;
            $placeholders[] = $placeholder;
            $queryParams[$placeholder] = $teamCode;
        }
        $whereClause = 'WHERE j.codeEquipe IN (' . implode(', ', $placeholders) . ')';
    }

    $playersQuery = "SELECT
            j.numJoueur,
            j.prenomJoueur,
            j.nomJoueur,
            j.urlPhotoJoueur,
            j.dateNaissance,
            j.numeroMaillot,
            j.posteJoueur,
            j.codeEquipe,
            e.nomEquipe
        FROM JOUEUR j
        LEFT JOIN EQUIPE e ON j.codeEquipe = e.codeEquipe
        {$whereClause}
        ORDER BY {$orderBy}";
    $playersStmt = $DB->prepare($playersQuery);
    $playersStmt->execute($queryParams);
    $ba_bec_players = $playersStmt->fetchAll(PDO::FETCH_ASSOC);
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

$ba_bec_list_query_params = [];
if (!empty($ba_bec_selected_teams)) {
    $ba_bec_list_query_params['teams'] = $ba_bec_selected_teams;
}
$ba_bec_list_query = http_build_query($ba_bec_list_query_params);
$ba_bec_action_query = $ba_bec_list_query !== '' ? ('&' . $ba_bec_list_query) : '';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
                <a href="<?php echo ROOT_URL . '/views/backend/joueurs/create.php'; ?>" class="btn btn-success">
                    Ajouter un joueur
                </a>
            </div>

            <?php foreach ($ba_bec_is_missing_table as $ba_bec_table => $ba_bec_missing): ?>
                <?php if ($ba_bec_missing): ?>
                    <div class="alert alert-warning">
                        <div>La table <?php echo htmlspecialchars($ba_bec_missing_table_labels[$ba_bec_table]); ?> est manquante. Veuillez téléchargé la derniere base de donné fournis.</div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <h1>Liste des joueurs</h1>
            <form method="get" class="row g-3 align-items-end mb-3">
                <div class="col-md-8">
                    <label for="team-selector" class="form-label">Filtrer par équipes</label>
                    <div class="d-flex gap-2">
                        <select id="team-selector" class="form-select">
                            <option value="">Choisir une équipe</option>
                            <?php foreach ($ba_bec_teams as $ba_bec_team): ?>
                                <?php $ba_bec_team_code = (string) ($ba_bec_team['codeEquipe'] ?? ''); ?>
                                <?php if ($ba_bec_team_code === '') {
                                    continue;
                                } ?>
                                <option value="<?php echo htmlspecialchars($ba_bec_team_code); ?>">
                                    <?php echo htmlspecialchars($ba_bec_team['nomEquipe'] ?? $ba_bec_team_code); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-primary" id="add-team-filter">Ajouter</button>
                    </div>
                    <div class="form-text">Chaque équipe ajoutée devient un filtre. Vous pouvez en sélectionner plusieurs.</div>
                    <div id="selected-teams" class="d-flex flex-wrap gap-2 mt-2">
                        <?php foreach ($ba_bec_selected_teams as $ba_bec_team_code): ?>
                            <?php
                            $ba_bec_team_name = $ba_bec_team_code;
                            foreach ($ba_bec_teams as $ba_bec_team) {
                                if ((string) ($ba_bec_team['codeEquipe'] ?? '') === $ba_bec_team_code) {
                                    $ba_bec_team_name = (string) ($ba_bec_team['nomEquipe'] ?? $ba_bec_team_code);
                                    break;
                                }
                            }
                            ?>
                            <span class="badge bg-primary d-inline-flex align-items-center gap-2 team-pill" data-team-code="<?php echo htmlspecialchars($ba_bec_team_code); ?>">
                                <?php echo htmlspecialchars($ba_bec_team_name); ?>
                                <button type="button" class="btn-close btn-close-white" aria-label="Retirer l'équipe"></button>
                                <input type="hidden" name="teams[]" value="<?php echo htmlspecialchars($ba_bec_team_code); ?>">
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                </div>
            </form>
            <script>
                (function () {
                    const teamSelector = document.getElementById('team-selector');
                    const addTeamButton = document.getElementById('add-team-filter');
                    const selectedTeamsContainer = document.getElementById('selected-teams');

                    if (!teamSelector || !addTeamButton || !selectedTeamsContainer) {
                        return;
                    }

                    const addTeam = () => {
                        const teamCode = teamSelector.value;
                        const teamName = teamSelector.options[teamSelector.selectedIndex]?.text || teamCode;

                        if (!teamCode) {
                            return;
                        }

                        if (selectedTeamsContainer.querySelector('[data-team-code="' + CSS.escape(teamCode) + '"]')) {
                            return;
                        }

                        const pill = document.createElement('span');
                        pill.className = 'badge bg-primary d-inline-flex align-items-center gap-2 team-pill';
                        pill.dataset.teamCode = teamCode;

                        const label = document.createElement('span');
                        label.textContent = teamName;

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn-close btn-close-white';
                        removeBtn.setAttribute('aria-label', 'Retirer l\'équipe');

                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'teams[]';
                        hiddenInput.value = teamCode;

                        removeBtn.addEventListener('click', () => {
                            pill.remove();
                        });

                        pill.appendChild(label);
                        pill.appendChild(removeBtn);
                        pill.appendChild(hiddenInput);
                        selectedTeamsContainer.appendChild(pill);

                        teamSelector.value = '';
                    };

                    addTeamButton.addEventListener('click', addTeam);

                    selectedTeamsContainer.querySelectorAll('.team-pill .btn-close').forEach((removeBtn) => {
                        removeBtn.addEventListener('click', () => {
                            const pill = removeBtn.closest('.team-pill');
                            if (pill) {
                                pill.remove();
                            }
                        });
                    });
                })();
            </script>
            <?php if (empty($ba_bec_players)) : ?>
                <div class="alert alert-info">Aucun joueur trouvé.</div>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Âge</th>
                            <th>Équipe</th>
                            <th>Poste</th>
                            <th>Maillot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ba_bec_current_team = null; ?>
                        <?php foreach ($ba_bec_players as $ba_bec_player): ?>
                            <?php $ba_bec_team_label = $ba_bec_player['nomEquipe'] ?? 'Non affecté'; ?>
                            <?php if ($ba_bec_team_label !== $ba_bec_current_team) : ?>
                                <tr class="table-secondary">
                                    <td colspan="7">
                                        <strong><?php echo htmlspecialchars($ba_bec_team_label); ?></strong>
                                    </td>
                                </tr>
                                <?php $ba_bec_current_team = $ba_bec_team_label; ?>
                            <?php endif; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ba_bec_player['numJoueur']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_player['prenomJoueur'] . ' ' . $ba_bec_player['nomJoueur']); ?></td>
                                <td><?php echo htmlspecialchars(format_age($ba_bec_player['dateNaissance'] ?? null)); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_player['nomEquipe'] ?? 'Non affecté'); ?></td>
                                <td><?php echo htmlspecialchars(format_poste($ba_bec_player['posteJoueur'] ?? null)); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_player['numeroMaillot'] ?? ''); ?></td>
                                <td>
                                    <a href="edit.php?numJoueur=<?php echo $ba_bec_player['numJoueur']; ?><?php echo htmlspecialchars($ba_bec_action_query); ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete.php?numJoueur=<?php echo $ba_bec_player['numJoueur']; ?><?php echo htmlspecialchars($ba_bec_action_query); ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
