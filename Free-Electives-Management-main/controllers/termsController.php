<?php
require_once '../models/termsModel.php';

class TermsController {
    private $model;

    // Constructor
    // Inicializa el modelo de términos
    public function __construct() {
        $this->model = new TermsModel();
    }

    // Procesa solicitudes POST y ejecuta acciones como activar o eliminar terminos
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['set_active'])) {
                    $this->setActiveTerm($_POST['term_id']);
                    header("Location: terms.php");
                    exit();
                }

                if (isset($_POST['delete'])) {
                    $this->deleteTerm($_POST['term_id']);
                    header("Location: terms.php");
                    exit();
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        return "";
    }

    // Retorna todos los terminos disponibles
    public function getAllTerms() {
        return $this->model->getAllTerms();
    }

    // Retorna detalles de un termino especifico
    public function getTermById($term_id) {
        return $this->model->getTermById($term_id);
    }

    // Agrega un nuevo termino validando el formato del id
    public function addTerm($term_id, $term_desc, $is_active) {
        if (!preg_match('/^[A-Z][0-9]{2}$/', $term_id)) {
            throw new Exception("Term ID must consist of an uppercase letter followed by two digits.");
        }
        $this->model->addTerm($term_id, $term_desc, $is_active);
    }

    // Actualiza un termino validando el formato del id
    public function editTerm($term_id, $term_desc, $is_active) {
        if (!preg_match('/^[A-Z][0-9]{2}$/', $term_id)) {
            throw new Exception("Term ID must consist of an uppercase letter followed by two digits.");
        }
        $this->model->editTerm($term_id, $term_desc, $is_active);
    }

    // Elimina un termino verificando que no tenga ofertas activas
    public function deleteTerm($term_id) {
        if ($this->model->isTermLinkedToOffering($term_id)) {
            throw new Exception("Cannot delete a term with active course offerings.");
        }
        $this->model->deleteTerm($term_id);
    }

    // Establece un termino como activo
    public function setActiveTerm($term_id) {
        $term = $this->model->getTermById($term_id);
        if (!$term) {
            throw new Exception("Term not found.");
        }
        $this->model->deactivateAllTerms();
        $this->model->activateTerm($term_id);
    }
}
?>
