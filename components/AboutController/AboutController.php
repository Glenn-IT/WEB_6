<?php

class AboutController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db   = $db;
        $this->view = "AboutController";
    }

    public function index() {
        $data = [];
        $rows = $this->db->Select("SELECT * FROM site_about ORDER BY about_id ASC", []);
        // key by section_key for easy access
        $data["sections"] = [];
        foreach ($rows as $row) {
            $data["sections"][$row["section_key"]] = $row;
        }
        $data["list"] = $rows;
        return ["content" => loadView('components/' . $this->view . '/views/custom', $data)];
    }

    public function js() {
        return [ $this->view . '/js/custom.js' ];
    }

    public function css() {
        return [];
    }

    /* -------------------------------------------------------
     * afterSubmit – update all about sections in one POST
     * ------------------------------------------------------- */
    public function afterSubmit() {
        $data = getRequestAll();

        // Expect: sections[section_key] = content
        if (isset($data['sections']) && is_array($data['sections'])) {
            foreach ($data['sections'] as $key => $value) {
                $this->db->Update(
                    "UPDATE site_about SET content = ? WHERE section_key = ?",
                    [$value, $key]
                );
            }
        }

        header('Location: index?type=success&message=About page updated successfully!');
        exit();
    }
}
