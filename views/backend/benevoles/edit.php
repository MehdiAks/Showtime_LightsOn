<?php
/*
 * Vue d'administration (édition) pour le module benevoles.
 * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
 * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
 * - L'action du formulaire cible la route de mise à jour correspondante.
 * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
 * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (!isset($_GET['numPersonnel'])) {
    header('Location: ' . ROOT_URL . '/views/backend/benevoles/list.php');
    exit();
}

$ba_bec_numPersonnel = $_GET['numPersonnel'];
$ba_bec_benevole = sql_select('PERSONNEL', '*', "numPersonnel = '$ba_bec_numPersonnel'");
$ba_bec_benevole = $ba_bec_benevole[0] ?? null;
$ba_bec_teams = sql_select('EQUIPE', 'codeEquipe, nomEquipe', null, null, 'nomEquipe ASC');

if (!$ba_bec_benevole) {
    header('Location: ' . ROOT_URL . '/views/backend/benevoles/list.php');
    exit();
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/benevoles/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Modifier un bénévole</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/benevoles/update.php'; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="numPersonnel" value="<?php echo htmlspecialchars($ba_bec_benevole['numPersonnel']); ?>" />
                <div class="form-group">
                    <label for="prenomPersonnel">Prénom</label>
                    <input id="prenomPersonnel" name="prenomPersonnel" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_benevole['prenomPersonnel']); ?>"
                        placeholder="Prénom (ex: Léa)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomPersonnel">Nom</label>
                    <input id="nomPersonnel" name="nomPersonnel" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_benevole['nomPersonnel']); ?>"
                        placeholder="Nom (ex: Martin)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="photoPersonnel">Photo</label>
                    <input id="photoPersonnel" name="photoPersonnel" class="form-control" type="file" accept="image/*" />
                </div>
                <div class="form-group mt-3">
                    <label class="form-label d-block">Rôles</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="estStaffEquipe" name="estStaffEquipe" value="1" <?php echo !empty($ba_bec_benevole['estStaffEquipe']) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="estStaffEquipe">Staff équipe</label>
                    </div>
                    <div class="mt-2">
                        <label for="numEquipeStaff" class="form-label">Équipe rattachée</label>
                        <select id="numEquipeStaff" name="numEquipeStaff" class="form-select">
                            <option value="">Sélectionner une équipe</option>
                            <?php foreach ($ba_bec_teams as $ba_bec_team): ?>
                                <option value="<?php echo htmlspecialchars($ba_bec_team['codeEquipe']); ?>" <?php echo ((string) ($ba_bec_benevole['numEquipeStaff'] ?? '') === (string) $ba_bec_team['codeEquipe']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ba_bec_team['nomEquipe']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="roleStaffEquipe" class="form-label">Rôle staff équipe</label>
                        <input id="roleStaffEquipe" name="roleStaffEquipe" class="form-control" type="text"
                            value="<?php echo htmlspecialchars($ba_bec_benevole['roleStaffEquipe'] ?? ''); ?>"
                            placeholder="Ex: Coach, assistant coach, analyste vidéo" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estDirection" name="estDirection" value="1" <?php echo !empty($ba_bec_benevole['estDirection']) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="estDirection">Direction</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteDirection" class="form-label">Poste en direction</label>
                        <input id="posteDirection" name="posteDirection" class="form-control" type="text"
                            value="<?php echo htmlspecialchars($ba_bec_benevole['posteDirection'] ?? ''); ?>"
                            placeholder="Ex: Président, Trésorier" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionTechnique" name="estCommissionTechnique" value="1" <?php echo !empty($ba_bec_benevole['estCommissionTechnique']) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="estCommissionTechnique">Commission technique</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionTechnique" class="form-label">Poste commission technique</label>
                        <input id="posteCommissionTechnique" name="posteCommissionTechnique" class="form-control" type="text"
                            value="<?php echo htmlspecialchars($ba_bec_benevole['posteCommissionTechnique'] ?? ''); ?>"
                            placeholder="Ex: Responsable technique" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionAnimation" name="estCommissionAnimation" value="1" <?php echo !empty($ba_bec_benevole['estCommissionAnimation']) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="estCommissionAnimation">Commission animation</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionAnimation" class="form-label">Poste commission animation</label>
                        <input id="posteCommissionAnimation" name="posteCommissionAnimation" class="form-control" type="text"
                            value="<?php echo htmlspecialchars($ba_bec_benevole['posteCommissionAnimation'] ?? ''); ?>"
                            placeholder="Ex: Responsable animations" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionCommunication" name="estCommissionCommunication" value="1" <?php echo !empty($ba_bec_benevole['estCommissionCommunication']) ? 'checked' : ''; ?> />
                        <label class="form-check-label" for="estCommissionCommunication">Commission communication</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionCommunication" class="form-label">Poste commission communication</label>
                        <input id="posteCommissionCommunication" name="posteCommissionCommunication" class="form-control" type="text"
                            value="<?php echo htmlspecialchars($ba_bec_benevole['posteCommissionCommunication'] ?? ''); ?>"
                            placeholder="Ex: Responsable communication" />
                    </div>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
