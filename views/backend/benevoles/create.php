<?php
/*
 * Vue d'administration (création) pour le module benevoles.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

$ba_bec_teams = sql_select('EQUIPE', 'codeEquipe, nomEquipe', null, null, 'nomEquipe ASC');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/benevoles/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Ajouter un bénévole</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/benevoles/create.php'; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="prenomPersonnel">Prénom</label>
                    <input id="prenomPersonnel" name="prenomPersonnel" class="form-control" type="text"
                        placeholder="Prénom (ex: Léa)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomPersonnel">Nom</label>
                    <input id="nomPersonnel" name="nomPersonnel" class="form-control" type="text"
                        placeholder="Nom (ex: Martin)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="photoPersonnel">Photo</label>
                    <input id="photoPersonnel" name="photoPersonnel" class="form-control" type="file" accept="image/*" />
                </div>
                <div class="form-group mt-3">
                    <label class="form-label d-block">Rôles</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="estStaffEquipe" name="estStaffEquipe" value="1" />
                        <label class="form-check-label" for="estStaffEquipe">Staff équipe</label>
                    </div>
                    <div class="mt-2">
                        <label for="numEquipeStaff" class="form-label">Équipe rattachée</label>
                        <select id="numEquipeStaff" name="numEquipeStaff" class="form-select">
                            <option value="">Sélectionner une équipe</option>
                            <?php foreach ($ba_bec_teams as $ba_bec_team): ?>
                                <option value="<?php echo htmlspecialchars($ba_bec_team['codeEquipe']); ?>">
                                    <?php echo htmlspecialchars($ba_bec_team['nomEquipe']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="roleStaffEquipe" class="form-label">Rôle staff équipe</label>
                        <input id="roleStaffEquipe" name="roleStaffEquipe" class="form-control" type="text"
                            placeholder="Ex: Coach, assistant coach, analyste vidéo" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estDirection" name="estDirection" value="1" />
                        <label class="form-check-label" for="estDirection">Direction</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteDirection" class="form-label">Poste en direction</label>
                        <input id="posteDirection" name="posteDirection" class="form-control" type="text"
                            placeholder="Ex: Président, Trésorier" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionTechnique" name="estCommissionTechnique" value="1" />
                        <label class="form-check-label" for="estCommissionTechnique">Commission technique</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionTechnique" class="form-label">Poste commission technique</label>
                        <input id="posteCommissionTechnique" name="posteCommissionTechnique" class="form-control" type="text"
                            placeholder="Ex: Responsable technique" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionAnimation" name="estCommissionAnimation" value="1" />
                        <label class="form-check-label" for="estCommissionAnimation">Commission animation</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionAnimation" class="form-label">Poste commission animation</label>
                        <input id="posteCommissionAnimation" name="posteCommissionAnimation" class="form-control" type="text"
                            placeholder="Ex: Responsable animations" />
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="estCommissionCommunication" name="estCommissionCommunication" value="1" />
                        <label class="form-check-label" for="estCommissionCommunication">Commission communication</label>
                    </div>
                    <div class="mt-2">
                        <label for="posteCommissionCommunication" class="form-label">Poste commission communication</label>
                        <input id="posteCommissionCommunication" name="posteCommissionCommunication" class="form-control" type="text"
                            placeholder="Ex: Responsable communication" />
                    </div>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
