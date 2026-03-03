<?php
/*
 * Vue d'administration (création) pour le module equipes.
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

$ba_bec_clubs = [];
$ba_bec_categories = [];
$ba_bec_sections = [];
$ba_bec_niveaux = [];
$ba_bec_clubs = array_column(
    sql_select('EQUIPE', 'DISTINCT club', "club <> ''", null, 'club ASC'),
    'club'
);
$ba_bec_categories = array_column(
    sql_select('EQUIPE', 'DISTINCT categorie', "categorie <> ''", null, 'categorie ASC'),
    'categorie'
);
$ba_bec_sections = array_column(
    sql_select('EQUIPE', 'DISTINCT section', "section <> ''", null, 'section ASC'),
    'section'
);
$ba_bec_niveaux = array_column(
    sql_select('EQUIPE', 'DISTINCT niveau', "niveau <> ''", null, 'niveau ASC'),
    'niveau'
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/equipes/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Ajouter une équipe</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/equipes/create.php'; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="codeEquipe">Code équipe</label>
                    <input id="codeEquipe" name="codeEquipe" class="form-control" type="text"
                        placeholder="Code équipe (ex: U18F)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomEquipe">Nom de l'équipe</label>
                    <input id="nomEquipe" name="nomEquipe" class="form-control" type="text"
                        placeholder="Nom de l'équipe..." required />
                </div>
                <div class="form-group mt-2">
                    <label for="club">Club</label>
                    <input id="club" name="club" class="form-control" type="text" list="clubList"
                        placeholder="Club (ex: Bordeaux étudiant club)" required />
                    <datalist id="clubList">
                        <?php foreach ($ba_bec_clubs as $ba_bec_club): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_club); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="categorie">Catégorie</label>
                    <input id="categorie" name="categorie" class="form-control" type="text" list="categorieList"
                        placeholder="Catégorie (ex: Seniors)" />
                    <datalist id="categorieList">
                        <?php foreach ($ba_bec_categories as $ba_bec_categorie): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_categorie); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="section">Section</label>
                    <input id="section" name="section" class="form-control" type="text" list="sectionList"
                        placeholder="Section (ex: Féminine)" />
                    <datalist id="sectionList">
                        <?php foreach ($ba_bec_sections as $ba_bec_section): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_section); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="niveau">Niveau</label>
                    <input id="niveau" name="niveau" class="form-control" type="text" list="niveauList"
                        placeholder="Niveau (ex: Régional)" />
                    <datalist id="niveauList">
                        <?php foreach ($ba_bec_niveaux as $ba_bec_niveau): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_niveau); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="descriptionEquipe">Description</label>
                    <textarea id="descriptionEquipe" name="descriptionEquipe" class="form-control" rows="4"
                        placeholder="Description de l'équipe..."></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="photoDLequipe">Photo de l'équipe (upload)</label>
                    <input id="photoDLequipe" name="photoDLequipe" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg, .webp, .gif" />
                </div>
                <div class="form-group mt-2">
                    <label for="photoStaff">Photo staff (upload)</label>
                    <input id="photoStaff" name="photoStaff" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg, .webp, .gif" />
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
