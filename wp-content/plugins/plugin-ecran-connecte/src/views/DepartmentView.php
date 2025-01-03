<?php

namespace Views;

use Models\Department;

/**
 * Class DepartmentView
 *
 * @package Views
 */
class DepartmentView extends View
{
    /**
     * Display a creation form for a department and an admin account.
     *
     * @return string
     */
    public function displayFormDepartment(): string {
        return '
            <form method="post">
                <div class="form-group">
                    <label for="deptName">Nom du département</label>
                    <input class="form-control" type="text" name="deptName" placeholder="Nom du département" minlength="4" maxlength="25" required="">
                </div>
                <button type="submit" class="btn button_ecran" id="valid" name="submit">Créer</button>
            </form>
            <a href="' . esc_url(get_permalink(get_page_by_title_V2('Gestion des départements'))) . '">Voir les départements</a>' . $this->contextCreateDept();
    }

	/**
	 * Form for modify a department.
	 *
	 * @return string
	 */
    public function modifyForm(): string {
        $page = get_page_by_title_V2('Gestion des départements');
        $linkManageDept = get_permalink($page->ID);

        return '
         <a href="' . esc_url(get_permalink($page)) . '">< Retour</a>
         <form method="post">
         	<label for="deptName">Nom du département</label>
            <input class="form-control" type="text" name="deptName" placeholder="280 caractères maximum" required="">
         <button type="submit" class="btn button_ecran" name="modifDept">Modifier</button>
          <a href="'. $linkManageDept .'">Annuler</a>
        </form>';
    }


	private function contextCreateDept() {
		return '
		<hr class="half-rule">
		<div>
			<h2>Les départements</h2>
			<p class="lead">Créer un département permet à chaque département de l\'IUT de pouvoir gérer son propre personnel et ses propres télévisions.</p>
			<p class="lead">À la création d\'un département, un compte administrateur est alors créé. Veuillez opter pour un identifiant contenant entre 4 et 25 caractères.</p>
		</div>';
	}

    /**
     * Display a form for deleting a department
     *
     * @return string
     */
    public function displayDeleteDepartment(): string {
        return '
            <form method="post" id="deleteDepartment">
                <h2>Supprimer un département</h2>
                <label for="deptCode">Code du département</label>
                <input type="text" class="form-control text-center" name="deptCode" placeholder="Code du département" required="">
                <button type="submit" class="btn button_ecran" name="deleteDepartment">Supprimer</button>
            </form>';
    }

	/**
	 * @return string
	 */
	public function contextDisplayAll(): string{
		return '
		<div class="row">
			<div class="col-6 mx-auto col-md-6 order-md-2">
				<img src="' . TV_PLUG_PATH . 'public/img/info.png" alt="Logo information" class="img-fluid mb-3 mb-md-0">
			</div>
			<div class="col-md-6 order-md-1 text-center text-md-left pr-md-5">
				<p class="lead">Vous pouvez retrouver ici tous les départements qui ont été créés sur ce site.</p>
				<p class="lead">Les départements sont triés de la plus vieille à la plus récente.</p>
				<p class="lead">Vous pouvez modifier un département en cliquant sur "Modifier" à la ligne correspondante au département.</p>
				<p class="lead">Vous souhaitez supprimer un / plusieurs département(s) ? Cochez les cases des départements puis cliquez sur "Supprimer" le bouton se situant en bas du tableau.</p>
			</div>
		</div>
		<a href="' . esc_url(get_permalink(get_page_by_title_V2('Créer un département'))) . '">Créer un département</a>
		<hr class="half-rule">';
	}

	/**
	 * @return string
	 */
	public function noDepartment(): string{
		return '<a href="' . esc_url(get_permalink(get_page_by_title_V2('Gestion des départements'))) . '">< Retour</a>
		<div>
			<h3>Département non trouvé</h3>
			<p>Ce département n\'existe pas</p>
			<a href="' . esc_url(get_permalink(get_page_by_title_V2('Créer un département'))) . '">Créer une département</a>
		</div>';
	}

    /**
     * Display a list of departments
     *
     * @param $departments Department[]
     *
     * @return string
     */
   public function displayAllDept($departments): string {
	   $page = get_page_by_title_V2('Modifier un département');
	   $linkManageDept = get_permalink($page->ID);

	    $title = 'Départements';
	    $name = 'Dept';
	    $header = ['Nom', 'Modifier'];
	    $row = [];
	    $count = 1;
	    foreach ($departments as $dept) {
		    $row[] = [ $count,
			    $this->buildCheckbox($name, $dept->getIdDept()),
			    htmlspecialchars($dept->getName()),
			    $this->buildLinkForModify($linkManageDept . '?id=' . $dept->getIdDept())
		    ];
		    ++$count;
		}

	    return $this->displayAll($name, $title, $header, $row);
    }

    /**
     * Display a success message for department creation
     */
    public function displayCreationSuccess(): void {
        $this->buildModal('Création réussie', '<div class="alert alert-success">Le département a été créé avec succès !</div>');
    }

    /**
     * Display an error message for department creation failure
     */
    public function displayCreationError(): void {
        $this->buildModal('Échec de la création', '<div class="alert alert-danger">Une erreur s\'est produite lors de la création du département. Veuillez réessayer.</div>');
    }

    /**
     * Display a success message for department deletion
     */
    public function displayDeletionSuccess(): void {
        $this->buildModal('Suppression réussie', '<div class="alert alert-success">Le département a été supprimé avec succès.</div>');
    }

    /**
     * Display an error message for department deletion failure
     */
    public function displayDeletionError(): void {
        $this->buildModal('Échec de la suppression', '<div class="alert alert-danger">Impossible de supprimer le département. Veuillez réessayer.</div>');
    }

	/**
	 * Display an error message for department modification failure
	 **/
	public function displayModificationSucces(): void {
		$this->buildModal('Modification réussie', '<div class="alert alert-danger">Le département a été modifié avec succès.</div>');
	}

	/**
	 * Display an error message for department modification failure
	 **/
	public function displayModificationError(): void {
		$this->buildModal('Échec de la modification', '<div class="alert alert-danger">Impossible de modifier le département. Veuillez réessayer.</div>');
	}

	/**
	 * Error message if name exits
	 */
	public function displayErrorDoubleName(): void {
		echo '<p class="alert alert-danger"> Ce nom de département existe déjà</p>';
	}

}