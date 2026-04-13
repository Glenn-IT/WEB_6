<?php

class PromoController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db   = $db;
        $this->view = "PromoController";
    }

    public function index() {
        $data = [];
        $data["list"] = $this->db->Select(
            "SELECT p.*, i.item_name
             FROM site_promos p
             LEFT JOIN items i ON i.item_id = p.link_service_id
             WHERE p.deleted = 0
             ORDER BY p.promo_id DESC",
            []
        );
        return ["content" => loadView('components/' . $this->view . '/views/custom', $data)];
    }

    public function js() {
        return [ $this->view . '/js/custom.js' ];
    }

    public function css() {
        return [];
    }

    /* -------------------------------------------------------
     * source – returns modal HTML for add / edit
     * ------------------------------------------------------- */
    public function source() {
        $data = getRequestAll();
        extract($data);

        $d["details"]  = false;
        $d["services"] = $this->db->Select(
            "SELECT item_id, item_name FROM items WHERE deleted = 0 AND status = 1 ORDER BY item_name ASC",
            []
        );

        if (isset($action) && $action == "edit" && !empty($id)) {
            $result = $this->db->Select(
                "SELECT * FROM site_promos WHERE promo_id = ?", [$id]
            );
            if (count($result) > 0) {
                $d["details"] = $result[0];
            }
        }

        $res = [
            'header' => (isset($action) && $action == "add") ? "Add Promo" : "Edit Promo",
            "html"   => loadView('components/' . $this->view . '/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Save Promo</button>',
            'action' => 'afterSubmit',
            'size'   => 'modal-lg',
        ];

        echo json_encode($res);
    }

    /* -------------------------------------------------------
     * afterSubmit – insert or update a promo
     * ------------------------------------------------------- */
    public function afterSubmit() {
        $data = getRequestAll();
        extract($data);

        $folder = 'src/images/promos/uploads/';

        $fields = [
            'title'           => isset($title)           ? $title           : '',
            'description'     => isset($description)     ? $description     : '',
            'discount_text'   => isset($discount_text)   ? $discount_text   : '',
            'link_service_id' => (isset($link_service_id) && $link_service_id !== '') ? (int)$link_service_id : null,
            'is_active'       => isset($is_active)       ? (int)$is_active  : 1,
        ];

        if (isset($id) && !empty($id)) {
            // ---- EDIT ----
            if (isset($image_path) && is_array($image_path) && !empty($image_path['name'][0])) {
                $uploaded = $this->db->handleMultipleFileUpload($image_path, $folder);
                if (!empty($uploaded)) {
                    $fields['image_path'] = $uploaded[0];
                }
            }

            $setClauses = [];
            $params     = [];
            foreach ($fields as $col => $val) {
                $setClauses[] = "`$col` = ?";
                $params[]     = $val;
            }
            $params[] = $id;
            $this->db->Update(
                "UPDATE site_promos SET " . implode(', ', $setClauses) . " WHERE promo_id = ?",
                $params
            );

            header('Location: index?type=success&message=Promo updated successfully!');
            exit();
        } else {
            // ---- ADD ----
            if (isset($image_path) && is_array($image_path) && !empty($image_path['name'][0])) {
                $uploaded = $this->db->handleMultipleFileUpload($image_path, $folder);
                if (!empty($uploaded)) {
                    $fields['image_path'] = $uploaded[0];
                }
            }

            $cols   = implode(', ', array_map(fn($c) => "`$c`", array_keys($fields)));
            $placeholders = implode(', ', array_fill(0, count($fields), '?'));
            $this->db->Insert(
                "INSERT INTO site_promos ($cols) VALUES ($placeholders)",
                array_values($fields)
            );

            header('Location: index?type=success&message=Promo added successfully!');
            exit();
        }
    }

    /* -------------------------------------------------------
     * toggleStatus – quick hide/show via AJAX
     * ------------------------------------------------------- */
    public function toggleStatus() {
        $data = getRequestAll();
        extract($data);

        $current = $this->db->Select(
            "SELECT is_active FROM site_promos WHERE promo_id = ?", [$id]
        );

        if (empty($current)) {
            echo json_encode(['status' => false, 'msg' => 'Record not found']);
            return;
        }

        $newStatus = $current[0]['is_active'] == 1 ? 0 : 1;
        $this->db->Update(
            "UPDATE site_promos SET is_active = ? WHERE promo_id = ?",
            [$newStatus, $id]
        );

        echo json_encode([
            'status'    => true,
            'msg'       => $newStatus ? 'Promo is now visible.' : 'Promo is now hidden.',
            'is_active' => $newStatus,
        ]);
    }

    /* -------------------------------------------------------
     * delete – soft delete
     * ------------------------------------------------------- */
    public function delete() {
        $data = getRequestAll();
        extract($data);

        $this->db->Update(
            "UPDATE site_promos SET deleted = 1 WHERE promo_id = ?", [$id]
        );

        echo json_encode(['status' => true, 'msg' => 'Promo deleted successfully!']);
    }
}
