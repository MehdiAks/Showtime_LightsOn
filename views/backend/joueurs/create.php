<?php
/*
 * Vue d'administration (création) pour le module joueurs.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

sql_connect();

$ba_bec_equipes = sql_select('EQUIPE', 'codeEquipe, nomEquipe', null, null, 'nomEquipe ASC');

$ba_bec_posteChoices = [
    'Poste 1 : meneur (point guard)',
    'Poste 2 : arrière (shooting guard)',
    'Poste 3 : ailier (small forward)',
    'Poste 4 : ailier fort (power forward)',
    'Poste 5 : pivot (center)',
];

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
                <a href="<?php echo ROOT_URL . '/views/backend/joueurs/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Ajouter un joueur</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/joueurs/create.php'; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="surnomJoueur">Surnom</label>
                    <input id="surnomJoueur" name="surnomJoueur" class="form-control" type="text"
                        placeholder="Surnom (ex: Ace)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="prenomJoueur">Prénom</label>
                    <input id="prenomJoueur" name="prenomJoueur" class="form-control" type="text"
                        placeholder="Prénom (ex: Léa)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomJoueur">Nom</label>
                    <input id="nomJoueur" name="nomJoueur" class="form-control" type="text"
                        placeholder="Nom (ex: Martin)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="photoJoueur">Photo (upload)</label>
                    <input id="photoJoueur" name="photoJoueur" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg" />
                </div>
                <div class="form-group mt-2">
                    <label for="dateNaissance">Date de naissance</label>
                    <input id="dateNaissance" name="dateNaissance" class="form-control" type="date"
                        placeholder="JJ/MM/AAAA" />
                </div>
                <div class="form-group mt-2">
                    <label for="numEquipe">Équipe</label>
                    <select id="numEquipe" name="codeEquipe" class="form-control" required>
                        <option value="">Sélectionnez une équipe</option>
                        <?php foreach ($ba_bec_equipes as $ba_bec_equipe): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_equipe['codeEquipe']); ?>">
                                <?php echo htmlspecialchars(ba_bec_formatEquipeLabel($ba_bec_equipe)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="posteJoueur">Poste</label>
                    <select id="posteJoueur" name="posteJoueur" class="form-control" required>
                        <option value="">Sélectionnez un poste</option>
                        <?php foreach ($ba_bec_posteChoices as $index => $ba_bec_posteChoice): ?>
                            <option value="<?php echo htmlspecialchars((string) ($index + 1)); ?>">
                                <?php echo htmlspecialchars($ba_bec_posteChoice); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="numeroMaillot">Numéro de maillot (saison)</label>
                    <input id="numeroMaillot" name="numeroMaillot" class="form-control" type="number" min="0" max="99"
                        placeholder="Numéro (0-99)" />
                </div>
                <div class="form-group mt-2">
                    <label for="dateRecrutement">Date de recrutement</label>
                    <input id="dateRecrutement" name="dateRecrutement" class="form-control" type="date"
                        placeholder="JJ/MM/AAAA" />
                </div>
                <div class="form-group mt-2">
                    <label for="clubsPrecedents">Clubs précédents</label>
                    <div id="clubsPrecedentsList" class="d-grid gap-2">
                        <input name="clubsPrecedents[]" class="form-control" type="text"
                            placeholder="Nom du club (ex: BEC Basket)" />
                    </div>
                    <button type="button" id="addClubButton" class="btn btn-outline-secondary btn-sm mt-2">
                        Ajouter un club
                    </button>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Créer</button>
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
