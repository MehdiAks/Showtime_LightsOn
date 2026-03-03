<?php
/*
 * Vue d'administration (édition) pour le module joueurs.
 * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
 * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
 * - L'action du formulaire cible la route de mise à jour correspondante.
 * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
 * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (!isset($_GET['numJoueur'])) {
    header('Location: ' . ROOT_URL . '/views/backend/joueurs/list.php');
    exit;
}

sql_connect();

$ba_bec_numJoueur = (int) $_GET['numJoueur'];
$ba_bec_joueur = sql_select('JOUEUR', '*', "numJoueur = '$ba_bec_numJoueur'");
$ba_bec_joueur = $ba_bec_joueur[0] ?? null;
$ba_bec_equipes = sql_select('EQUIPE', 'codeEquipe, nomEquipe', null, null, 'nomEquipe ASC');

$ba_bec_posteChoices = [
    'Poste 1 : meneur (point guard)',
    'Poste 2 : arrière (shooting guard)',
    'Poste 3 : ailier (small forward)',
    'Poste 4 : ailier fort (power forward)',
    'Poste 5 : pivot (center)',
];

$ba_bec_selectedPoste = null;
if (!empty($ba_bec_joueur['posteJoueur'])) {
    $ba_bec_selectedPoste = (int) $ba_bec_joueur['posteJoueur'];
}

$ba_bec_clubs = [];
if (!empty($ba_bec_joueur['clubsPrecedents'])) {
    $ba_bec_clubs = array_filter(array_map('trim', explode(',', (string) $ba_bec_joueur['clubsPrecedents'])));
}

if (!$ba_bec_joueur) {
    header('Location: ' . ROOT_URL . '/views/backend/joueurs/list.php');
    exit;
}

$ba_bec_return_teams = $_GET['teams'] ?? [];
if (!is_array($ba_bec_return_teams)) {
    $ba_bec_return_teams = [$ba_bec_return_teams];
}
$ba_bec_return_teams = array_values(array_unique(array_filter(array_map('strval', $ba_bec_return_teams), 'strlen')));
$ba_bec_return_query = '';
if (!empty($ba_bec_return_teams)) {
    $ba_bec_return_query = '?' . http_build_query(['teams' => $ba_bec_return_teams]);
}

function ba_bec_formatEquipeLabel(array $ba_bec_equipe): string
{
    $label = $ba_bec_equipe['nomEquipe'] ?? '';
    $code = $ba_bec_equipe['codeEquipe'] ?? '';
    return $code !== '' ? $label . ' (' . $code . ')' : $label;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/joueurs/list.php' . $ba_bec_return_query; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Modifier un joueur</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/joueurs/update.php'; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="numJoueur" value="<?php echo htmlspecialchars($ba_bec_joueur['numJoueur']); ?>" />
                <input type="hidden" name="photoActuelle" value="<?php echo htmlspecialchars($ba_bec_joueur['urlPhotoJoueur'] ?? ''); ?>" />
                <?php foreach ($ba_bec_return_teams as $ba_bec_return_team): ?>
                    <input type="hidden" name="teams[]" value="<?php echo htmlspecialchars($ba_bec_return_team); ?>" />
                <?php endforeach; ?>
                <div class="form-group">
                    <label for="surnomJoueur">Surnom</label>
                    <input id="surnomJoueur" name="surnomJoueur" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['surnomJoueur'] ?? ''); ?>"
                        placeholder="Surnom (ex: Ace)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="prenomJoueur">Prénom</label>
                    <input id="prenomJoueur" name="prenomJoueur" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['prenomJoueur']); ?>"
                        placeholder="Prénom (ex: Léa)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomJoueur">Nom</label>
                    <input id="nomJoueur" name="nomJoueur" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['nomJoueur']); ?>"
                        placeholder="Nom (ex: Martin)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="posteJoueur">Postes</label>
                    <select id="posteJoueur" name="posteJoueur" class="form-control" required>
                        <option value="">Sélectionnez un poste</option>
                        <?php foreach ($ba_bec_posteChoices as $index => $ba_bec_posteChoice): ?>
                            <?php $ba_bec_value = $index + 1; ?>
                            <option value="<?php echo htmlspecialchars((string) $ba_bec_value); ?>"
                                <?php echo ($ba_bec_selectedPoste === $ba_bec_value) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ba_bec_posteChoice); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="photoJoueur">Photo (upload)</label>
                    <input id="photoJoueur" name="photoJoueur" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg" />
                    <?php if (!empty($ba_bec_joueur['urlPhotoJoueur'])): ?>
                        <?php
                        $ba_bec_photo = $ba_bec_joueur['urlPhotoJoueur'];
                        $ba_bec_photoUrl = preg_match('/^(https?:\/\/|\/)/', $ba_bec_photo)
                            ? $ba_bec_photo
                            : ROOT_URL . '/src/uploads/' . $ba_bec_photo;
                        ?>
                        <div class="mt-2">
                            <img src="<?php echo htmlspecialchars($ba_bec_photoUrl); ?>" alt="Photo actuelle" style="max-width: 120px;" />
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-2">
                    <label for="numeroMaillot">Numéro de maillot (saison)</label>
                    <input id="numeroMaillot" name="numeroMaillot" class="form-control" type="number" min="0" max="99"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['numeroMaillot'] ?? ''); ?>"
                        placeholder="Numéro (0-99)" />
                </div>
                <div class="form-group mt-2">
                    <label for="numEquipe">Équipe</label>
                    <select id="numEquipe" name="codeEquipe" class="form-control" required>
                        <option value="">Sélectionnez une équipe</option>
                        <?php foreach ($ba_bec_equipes as $ba_bec_equipe): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_equipe['codeEquipe']); ?>"
                                <?php echo (($ba_bec_joueur['codeEquipe'] ?? '') === $ba_bec_equipe['codeEquipe']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ba_bec_formatEquipeLabel($ba_bec_equipe)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="dateRecrutement">Date de recrutement</label>
                    <input id="dateRecrutement" name="dateRecrutement" class="form-control" type="date"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['dateRecrutement'] ?? ''); ?>"
                        placeholder="JJ/MM/AAAA" />
                </div>
                <div class="form-group mt-2">
                    <label for="dateNaissance">Date de naissance</label>
                    <input id="dateNaissance" name="dateNaissance" class="form-control" type="date"
                        value="<?php echo htmlspecialchars($ba_bec_joueur['dateNaissance'] ?? ''); ?>"
                        placeholder="JJ/MM/AAAA" />
                </div>
                <div class="form-group mt-2">
                    <label for="clubsPrecedents">Clubs précédents</label>
                    <?php
                    $ba_bec_clubsList = $ba_bec_clubs;
                    if (empty($ba_bec_clubsList)) {
                        $ba_bec_clubsList = [''];
                    }
                    ?>
                    <div id="clubsPrecedentsList" class="d-grid gap-2">
                        <?php foreach ($ba_bec_clubsList as $ba_bec_club): ?>
                            <input name="clubsPrecedents[]" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($ba_bec_club); ?>"
                                placeholder="Nom du club (ex: BEC Basket)" />
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="addClubButton" class="btn btn-outline-secondary btn-sm mt-2">
                        Ajouter un club
                    </button>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const clubList = document.getElementById('clubsPrecedentsList');
        const addClubButton = document.getElementById('addClubButton');

        const addClubField = () => {
            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex gap-2';

            const input = document.createElement('input');
            input.name = 'clubsPrecedents[]';
            input.className = 'form-control';
            input.type = 'text';
            input.placeholder = 'Nom du club (ex: BEC Basket)';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-outline-danger';
            removeButton.textContent = 'Retirer';
            removeButton.addEventListener('click', () => wrapper.remove());

            wrapper.appendChild(input);
            wrapper.appendChild(removeButton);
            clubList.appendChild(wrapper);
        };

        addClubButton.addEventListener('click', addClubField);
    })();
</script>
