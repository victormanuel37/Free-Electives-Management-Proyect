<?php
require_once '../config/database.php';

class TermsModel {
    private $db;

    // Constructor
    // Inicializa la conexion con la base de datos
    public function __construct() {
        $this->db = DataBase::getDB();
    }

    // Obtiene todos los terminos ordenados por id
    // Retorna una lista de terminos
    public function getAllTerms() {
        $query = "SELECT * FROM terms ORDER BY term_id";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    // Obtiene detalles de un termino especifico por id
    // Retorna un termino especifico o null
    public function getTermById($term_id) {
        $query = "SELECT * FROM terms WHERE term_id = :term_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->execute();
        return $statement->fetch();
    }

    // Agrega un nuevo termino a la base de datos
    public function addTerm($term_id, $term_desc, $is_active) {
        $query = "
            INSERT INTO terms (term_id, term_desc, term_is_active)
            VALUES (:term_id, :term_desc, :is_active)
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->bindValue(':term_desc', $term_desc);
        $statement->bindValue(':is_active', $is_active, PDO::PARAM_INT);
        $statement->execute();
    }

    // Actualiza detalles de un termino existente
    public function editTerm($term_id, $term_desc, $is_active) {
        // Desactiva otros terminos si el actual es activado
        if ($is_active) {
            $this->deactivateAllTerms();
        }

        $query = "
            UPDATE terms
            SET term_desc = :term_desc, term_is_active = :is_active
            WHERE term_id = :term_id
        ";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->bindValue(':term_desc', $term_desc);
        $statement->bindValue(':is_active', $is_active, PDO::PARAM_INT);
        $statement->execute();
    }

    // Elimina un termino por id
    public function deleteTerm($term_id) {
        $query = "DELETE FROM terms WHERE term_id = :term_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->execute();
    }

    // Desactiva todos los terminos en la base de datos
    public function deactivateAllTerms() {
        $query = "UPDATE terms SET term_is_active = 0";
        $this->db->exec($query);
    }

    // Activa un termino especifico
    public function activateTerm($term_id) {
        $query = "UPDATE terms SET term_is_active = 1 WHERE term_id = :term_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->execute();
    }

    // Verifica si un termino tiene ofertas activas vinculadas
    // Retorna true si tiene ofertas activas, si no false
    public function isTermLinkedToOffering($term_id) {
        $query = "SELECT COUNT(*) FROM term_offering WHERE term_id = :term_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':term_id', $term_id);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }
}
?>
