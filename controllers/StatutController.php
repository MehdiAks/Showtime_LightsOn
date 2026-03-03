<?php

class StatutController
{
    /**
     * Rend une vue back-end avec le layout commun (header/footer).
     * Les données sont extraites en variables accessibles dans la vue.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require_once __DIR__ . '/../config.php';
        include __DIR__ . '/../header.php';
        include __DIR__ . '/../' . $view;
        include __DIR__ . '/../footer.php';
    }

    public function list(): void
    {
        require_once __DIR__ . '/../config.php';
        // Charge tous les statuts pour l'affichage en liste.
        $ba_bec_statuts = sql_select('STATUT', '*');
        $this->render('views/backend/statuts/list.php', [
            'ba_bec_statuts' => $ba_bec_statuts,
        ]);
    }

    public function create(): void
    {
        // Affiche le formulaire de création vide.
        $this->render('views/backend/statuts/create.php');
    }

    public function store(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Nettoie le libellé saisi pour le nouveau statut.
        $ba_bec_libStat = ctrlSaisies($_POST['libStat'] ?? '');

        // Calcule le prochain id en partant du max existant.
        $ba_bec_currentMax = sql_select('STATUT', 'MAX(numStat) AS maxStat');
        $ba_bec_nextNumStat = 1;
        if (!empty($ba_bec_currentMax) && isset($ba_bec_currentMax[0]['maxStat'])) {
            $ba_bec_nextNumStat = (int) $ba_bec_currentMax[0]['maxStat'] + 1;
        }

        // Insère le nouveau statut.
        sql_insert('STATUT', 'numStat, libStat', "'$ba_bec_nextNumStat', '$ba_bec_libStat'");

        // Redirige vers la liste.
        header('Location: ' . ROOT_URL . '/public/index.php?controller=statut&action=list');
        exit;
    }

    public function edit(): void
    {
        require_once __DIR__ . '/../config.php';

        $ba_bec_numStat = $_GET['numStat'] ?? '';
        $ba_bec_libStat = '';

        // Charge le libellé si un id valide est fourni.
        if ($ba_bec_numStat !== '') {
            $ba_bec_libStat = sql_select('STATUT', 'libStat', "numStat = $ba_bec_numStat")[0]['libStat'] ?? '';
        }

        $this->render('views/backend/statuts/edit.php', [
            'ba_bec_numStat' => $ba_bec_numStat,
            'ba_bec_libStat' => $ba_bec_libStat,
        ]);
    }

    public function update(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Nettoie les champs entrants.
        $ba_bec_numStat = ctrlSaisies($_POST['numStat'] ?? '');
        $ba_bec_libStat = ctrlSaisies($_POST['libStat'] ?? '');

        // Sauvegarde le libellé mis à jour pour le statut sélectionné.
        sql_update(table: 'STATUT', attributs: 'libStat = "' . $ba_bec_libStat . '"', where: "numStat = $ba_bec_numStat");

        // Redirige vers la liste.
        header('Location: ' . ROOT_URL . '/public/index.php?controller=statut&action=list');
        exit;
    }

    public function delete(): void
    {
        require_once __DIR__ . '/../config.php';

        $ba_bec_numStat = $_GET['numStat'] ?? '';
        $ba_bec_libStat = '';

        // Récupère le libellé pour l'afficher dans la confirmation de suppression.
        if ($ba_bec_numStat !== '') {
            $ba_bec_libStat = sql_select('STATUT', 'libStat', "numStat = $ba_bec_numStat")[0]['libStat'] ?? '';
        }

        $this->render('views/backend/statuts/delete.php', [
            'ba_bec_numStat' => $ba_bec_numStat,
            'ba_bec_libStat' => $ba_bec_libStat,
        ]);
    }

    public function destroy(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Nettoie l'id et vérifie s'il est utilisé par un membre.
        $ba_bec_numStat = ctrlSaisies($_POST['numStat'] ?? '');

        $ba_bec_countnumStat = sql_select('MEMBRE', 'COUNT(*) AS total', "numStat = $ba_bec_numStat")[0]['total'] ?? 0;

        // Empêche la suppression s'il est référencé.
        if ($ba_bec_countnumStat > 0) {
            header('Location: ' . ROOT_URL . '/public/index.php?controller=statut&action=list&error=used');
            exit;
        }

        // Supprime le statut et redirige avec un indicateur de succès.
        sql_delete('STATUT', "numStat = $ba_bec_numStat");

        header('Location: ' . ROOT_URL . '/public/index.php?controller=statut&action=list&success=deleted');
        exit;
    }
}
