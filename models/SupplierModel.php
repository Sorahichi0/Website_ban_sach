<?php
require_once "BaseModel.php";

class SupplierModel extends BaseModel {
    private $table = "tbl_suppliers";

    public function getAll($searchTerm = '') {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " WHERE name LIKE :searchTerm OR email LIKE :searchTerm OR phone LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql .= " ORDER BY id DESC";
        return $this->select($sql, $params);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->select($sql, [$id]);
        return $result[0] ?? null;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, contact_person, email, phone, address, contract_info) 
                VALUES (:name, :contact_person, :email, :phone, :address, :contract_info)";
        return $this->query($sql, $data);
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                name = :name, 
                contact_person = :contact_person, 
                email = :email, 
                phone = :phone, 
                address = :address, 
                contract_info = :contract_info
                WHERE id = :id";
        return $this->query($sql, $data);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id]);
    }
}
?>