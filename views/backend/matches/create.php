<?php
/*
 * Vue d'administration (création) pour le module matches.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
$pageStyles = [ROOT_URL . '/src/css/match-create.css'];
include '../../../header.php';

sql_connect();

$ba_bec_equipes = sql_select('EQUIPE', 'codeEquipe, nomEquipe', null, null, 'nomEquipe ASC');
$ba_bec_clubs = array_column(
    sql_select('`MATCH`', 'DISTINCT clubAdversaire', "clubAdversaire <> ''", null, 'clubAdversaire ASC'),
    'clubAdversaire'
);
$ba_bec_defaultSaison = '2025-2026';
$ba_bec_form = [
    'saison' => $_GET['saison'] ?? $ba_bec_defaultSaison,
    'phase' => $_GET['phase'] ?? '',
    'journee' => $_GET['journee'] ?? '',
    'dateMatch' => $_GET['dateMatch'] ?? '',
    'heureMatch' => $_GET['heureMatch'] ?? '',
    'lieuMatch' => $_GET['lieuMatch'] ?? 'Domicile',
    'codeEquipe' => $_GET['codeEquipe'] ?? '',
    'clubAdversaire' => $_GET['clubAdversaire'] ?? '',
    'numeroEquipeAdverse' => $_GET['numeroEquipeAdverse'] ?? '',
    'scoreBec' => $_GET['scoreBec'] ?? '',
    'scoreAdversaire' => $_GET['scoreAdversaire'] ?? '',
];

$ba_bec_saisons = [$ba_bec_defaultSaison];
$ba_bec_phases = ['Saison régulière', 'Play-off', 'Play-down', 'Coupe'];
$ba_bec_lieux = ['Domicile', 'Extérieur'];

function ba_bec_team_label(array $team): string
{
    $label = $team['nomEquipe'] ?? '';
    $code = $team['codeEquipe'] ?? '';
    return $code !== '' ? $label . ' (' . $code . ')' : $label;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création d'un match</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/matches/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="saison">Saison</label>
                    <select id="saison" name="saison" class="form-control" required>
                        <?php foreach ($ba_bec_saisons as $ba_bec_saison): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_saison); ?>"
                                <?php echo $ba_bec_form['saison'] === $ba_bec_saison ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ba_bec_saison); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="phase">Phase</label>
                    <select id="phase" name="phase" class="form-control" required>
                        <option value="" disabled <?php echo $ba_bec_form['phase'] === '' ? 'selected' : ''; ?>>Sélectionner une phase</option>
                        <?php foreach ($ba_bec_phases as $ba_bec_phase): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_phase); ?>"
                                <?php echo $ba_bec_form['phase'] === $ba_bec_phase ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ba_bec_phase); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="journee">Journée</label>
                    <input id="journee" name="journee" class="form-control" type="text" placeholder="Ex: J3" value="<?php echo htmlspecialchars($ba_bec_form['journee']); ?>" required />
                </div>
                <div class="form-group mt-2 row">
                    <div class="col-md-4">
                        <label for="dateMatch">Date</label>
                        <input id="dateMatch" name="dateMatch" class="form-control" type="date" placeholder="JJ/MM/AAAA" value="<?php echo htmlspecialchars($ba_bec_form['dateMatch']); ?>" required />
                    </div>
                    <div class="col-md-4">
                        <label for="heureMatch">Heure</label>
                        <input id="heureMatch" name="heureMatch" class="form-control" type="time" placeholder="HH:MM" value="<?php echo htmlspecialchars($ba_bec_form['heureMatch']); ?>" />
                    </div>
                    <div class="col-md-4">
                        <label for="lieuMatch">Lieu</label>
                        <select id="lieuMatch" name="lieuMatch" class="form-control" required>
                            <?php foreach ($ba_bec_lieux as $ba_bec_lieu): ?>
                                <option value="<?php echo htmlspecialchars($ba_bec_lieu); ?>"
                                    <?php echo $ba_bec_form['lieuMatch'] === $ba_bec_lieu ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ba_bec_lieu); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="codeEquipe">Équipe du club (BEC)</label>
                    <select id="codeEquipe" name="codeEquipe" class="form-control" required>
                        <option value="" disabled <?php echo $ba_bec_form['codeEquipe'] === '' ? 'selected' : ''; ?>>Sélectionner l'équipe BEC</option>
                        <?php foreach ($ba_bec_equipes as $ba_bec_equipe): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_equipe['codeEquipe']); ?>"
                                <?php echo $ba_bec_form['codeEquipe'] === $ba_bec_equipe['codeEquipe'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ba_bec_team_label($ba_bec_equipe)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="clubAdversaire">Club adverse</label>
                    <input id="clubAdversaire" name="clubAdversaire" class="form-control" type="text" placeholder="Nom du club adverse" value="<?php echo htmlspecialchars($ba_bec_form['clubAdversaire']); ?>" list="clubAdversaireSuggestions" required />
                    <datalist id="clubAdversaireSuggestions">
                        <?php foreach ($ba_bec_clubs as $ba_bec_club): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_club); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="numeroEquipeAdverse">Équipe adverse (1/2/3/4…)</label>
                    <input id="numeroEquipeAdverse" name="numeroEquipeAdverse" class="form-control" type="number" min="1" step="1" placeholder="1" value="<?php echo htmlspecialchars($ba_bec_form['numeroEquipeAdverse']); ?>" />
                </div>
                <div class="form-group mt-2 row">
                    <div class="col-md-6">
                        <label for="scoreBec">Score BEC</label>
                        <input id="scoreBec" name="scoreBec" class="form-control" type="number" min="0" placeholder="Score (ex: 75)" value="<?php echo htmlspecialchars($ba_bec_form['scoreBec']); ?>" />
                    </div>
                    <div class="col-md-6">
                        <label for="scoreAdversaire">Score adverse</label>
                        <input id="scoreAdversaire" name="scoreAdversaire" class="form-control" type="number" min="0" placeholder="Score (ex: 68)" value="<?php echo htmlspecialchars($ba_bec_form['scoreAdversaire']); ?>" />
                    </div>
                    <small class="form-text text-muted">Laisser vide si le match n'a pas encore eu lieu.</small>
                </div>
                <div class="form-group mt-3">
                    <div class="form-check">
                        <input id="createRetour" name="createRetour" class="form-check-input" type="checkbox" value="1" />
                        <label class="form-check-label" for="createRetour">Créer le match retour</label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <a href="list.php" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-success">Créer le match</button>
                </div>
            </form>
        </div>
    </div>
</div>
