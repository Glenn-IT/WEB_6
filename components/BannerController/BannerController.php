<?php

class BannerController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db  = $db;
        $this->view = "BannerController";
    }

    public function index() {
        $data = [];
        $data["list"] = $this->db->Select(
            "SELECT * FROM site_banners WHERE deleted = 0 ORDER BY sort_order ASC",
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

        $d["details"] = false;

        if (isset($action) && $action == "edit" && !empty($id)) {
            $result = $this->db->Select(
                "SELECT * FROM site_banners WHERE banner_id = ?", [$id]
            );
            if (count($result) > 0) {
                $d["details"] = $result[0];
            }
        }

        $res = [
            'header' => (isset($action) && $action == "add") ? "Add Banner" : "Edit Banner",
            "html"   => loadView('components/' . $this->view . '/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Save Banner</button>',
            'action' => 'afterSubmit',
        ];

        echo json_encode($res);
    }

    /* -------------------------------------------------------
     * afterSubmit – insert or update a banner record
     * ------------------------------------------------------- */
    public function afterSubmit() {
        $data = getRequestAll();
        extract($data);

        $folder = 'src/images/banner/uploads/';

        if (isset($id) && !empty($id)) {
            // ---- EDIT ----
            $update = [
                'title'      => isset($title) ? $title : '',
                'sort_order' => isset($sort_order) ? (int)$sort_order : 0,
                'is_active'  => isset($is_active) ? (int)$is_active : 1,
            ];

            if (isset($image_path) && is_array($image_path) && !empty($image_path['name'][0])) {
                $uploaded = $this->db->handleMultipleFileUpload($image_path, $folder);
                if (!empty($uploaded)) {
                    $update['image_path'] = $uploaded[0];
                }
            }

            $setClauses = [];
            $params     = [];
            foreach ($update as $col => $val) {
                $setClauses[] = "`$col` = ?";
                $params[]     = $val;
            }
            $params[] = $id;
            $this->db->Update(
                "UPDATE site_banners SET " . implode(', ', $setClauses) . " WHERE banner_id = ?",
                $params
            );

            header('Location: index?type=success&message=Banner updated successfully!');
            exit();
        } else {
            // ---- ADD ----
            if (!isset($image_path) || !is_array($image_path) || empty($image_path['name'][0])) {
                header('Location: index?type=warning&message=Please upload an image!');
                exit();
            }

            $uploaded = $this->db->handleMultipleFileUpload($image_path, $folder);
            if (empty($uploaded)) {
                header('Location: index?type=warning&message=Image upload failed!');
                exit();
            }

            $this->db->Insert(
                "INSERT INTO site_banners (title, image_path, sort_order, is_active) VALUES (?, ?, ?, ?)",
                [
                    isset($title) ? $title : '',
                    $uploaded[0],
                    isset($sort_order) ? (int)$sort_order : 0,
                    isset($is_active) ? (int)$is_active : 1,
                ]
            );

            header('Location: index?type=success&message=Banner added successfully!');
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
            "SELECT is_active FROM site_banners WHERE banner_id = ?", [$id]
        );

        if (empty($current)) {
            echo json_encode(['status' => false, 'msg' => 'Record not found']);
            return;
        }

        $newStatus = $current[0]['is_active'] == 1 ? 0 : 1;
        $this->db->Update(
            "UPDATE site_banners SET is_active = ? WHERE banner_id = ?",
            [$newStatus, $id]
        );

        echo json_encode([
            'status'    => true,
            'msg'       => $newStatus ? 'Banner is now visible.' : 'Banner is now hidden.',
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
            "UPDATE site_banners SET deleted = 1 WHERE banner_id = ?", [$id]
        );

        echo json_encode(['status' => true, 'msg' => 'Banner deleted successfully!']);
    }
}
