<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>Reports</h5>
            <div class="card-header-right">
                <div class="apexcharts-menu-icon" title="Menu" style="cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                    </svg>
                </div>

                <div class="apexcharts-menu">
                    <div class="apexcharts-menu-item exportCSV" title="Download CSV">Download CSV</div>
                    <div class="apexcharts-menu-item exportPDF" title="Download PDF">Download PDF</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="filter-section">
                <h6><i class="fa fa-filter"></i> Filter & Sort Options</h6>
                
                <form action="index" method="get">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="fromDate">From Date</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                name="from" 
                                id="fromDate" 
                                value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '' ?>" 
                                placeholder="From Date"
                            >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="toDate">To Date</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                name="to" 
                                id="toDate" 
                                value="<?= isset($_GET['to']) ? htmlspecialchars($_GET['to']) : '' ?>" 
                                placeholder="To Date"
                            >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="serviceType">Service Type</label>
                            <select class="form-control" name="service_type" id="serviceType">
                                <option value="">All Service Types</option>
                                <?php if(isset($service_types) && count($service_types) > 0): ?>
                                    <?php foreach($service_types as $service): ?>
                                        <option value="<?= htmlspecialchars($service['name']) ?>" 
                                            <?= (isset($_GET['service_type']) && $_GET['service_type'] == $service['name']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($service['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sortBy">Sort By</label>
                            <select class="form-control" name="sort_by" id="sortBy">
                                <option value="date_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'date_desc') ? 'selected' : '' ?>>Date (Newest First)</option>
                                <option value="date_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'date_asc') ? 'selected' : '' ?>>Date (Oldest First)</option>
                                <option value="service_type_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'service_type_asc') ? 'selected' : '' ?>>Service Type (A-Z)</option>
                                <option value="service_type_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'service_type_desc') ? 'selected' : '' ?>>Service Type (Z-A)</option>
                                <option value="customer_asc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'customer_asc') ? 'selected' : '' ?>>Customer Name (A-Z)</option>
                                <option value="customer_desc" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'customer_desc') ? 'selected' : '' ?>>Customer Name (Z-A)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-success btn-block" id="generateBtn">
                                <i class="fa fa-chart-line"></i> GENERATE REPORT
                            </button>
                        </div>
                        <div class="form-group col-md-6">
                            <a href="index" class="btn btn-outline-secondary btn-block">
                                <i class="fa fa-times"></i> CLEAR FILTERS
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if(isset($_GET['from']) || isset($_GET['to']) || isset($_GET['service_type']) || isset($_GET['sort_by'])): ?>
            <div class="alert alert-info">
                <strong><i class="fa fa-info-circle"></i> Active Filters:</strong>
                <?php if(isset($_GET['from']) && isset($_GET['to'])): ?>
                    <span class="badge badge-primary">Date: <?= htmlspecialchars($_GET['from']) ?> to <?= htmlspecialchars($_GET['to']) ?></span>
                <?php endif; ?>
                <?php if(isset($_GET['service_type']) && !empty($_GET['service_type'])): ?>
                    <span class="badge badge-success">Service Type: <?= htmlspecialchars($_GET['service_type']) ?></span>
                <?php endif; ?>
                <?php if(isset($_GET['sort_by']) && !empty($_GET['sort_by'])): ?>
                    <span class="badge badge-warning">Sort: 
                        <?php 
                        $sortLabels = [
                            'date_desc' => 'Date (Newest First)',
                            'date_asc' => 'Date (Oldest First)',
                            'service_type_asc' => 'Service Type (A-Z)',
                            'service_type_desc' => 'Service Type (Z-A)',
                            'customer_asc' => 'Customer Name (A-Z)',
                            'customer_desc' => 'Customer Name (Z-A)'
                        ];
                        echo $sortLabels[$_GET['sort_by']] ?? $_GET['sort_by'];
                        ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="card-block">
                <div class="table-responsive">
                    <table id="expiryTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Appointment Date</th>
                                <th>Customer Name</th>
                                <th>Service Type</th>
                                <th>Service Name</th>
                                <th>No. of Completed</th>
                                <th>No. of Pending</th>
                                <th>No. of Ongoing</th>
                                <th>No. of Declined</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($list) && count($list) > 0){
                                    foreach ($list as $key => $value) {
                                        echo "<tr>";
                                        echo "<td>".(!empty($value["details"]["date"]) ? date('M d, Y', strtotime($value["details"]["date"])) : 'N/A')."</td>";
                                        echo "<td>".(!empty($value["details"]["customer_name"]) ? $value["details"]["customer_name"] : 'N/A')."</td>";
                                        echo "<td>".$value["details"]["service_type"]."</td>";
                                        echo "<td>".$value["details"]["item_name"]."</td>";
                                        echo "<td>".(isset($value["COMPLETED"]) ? $value["COMPLETED"] : 0)."</td>";
                                        echo "<td>".(isset($value["PENDING"]) ? $value["PENDING"] : 0)."</td>";
                                        echo "<td>".(isset($value["PROCESSED"]) ? $value["PROCESSED"] : 0)."</td>";
                                        echo "<td>".(isset($value["DECLINED"]) ? $value["DECLINED"] : 0)."</td>";
                                        echo "</tr>";

                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td > NO RECORD FOUND!</td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>
                                        <td ></td>

                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
    </div>
</div>

<!-- ✅ DataTables CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
function downloadCSVFromTable(tableSelector, filename) {
  const table = document.querySelector(tableSelector);
  if (!table) {
    alert("Table not found!");
    return;
  }

  let csv = [];
  const rows = table.querySelectorAll("tr");

  rows.forEach((row, rowIndex) => {
    const cols = row.querySelectorAll("th, td");
    let rowData = [];

    cols.forEach((col, colIndex) => {
      // exclude last column (Status)
      if (colIndex < cols.length - 1) {
        let text = col.innerText.trim().replace(/"/g, '""');

        if (/^\d{2}\/\d{2}\/\d{4}$/.test(text) || /^\d{4}-\d{2}-\d{2}$/.test(text)) {
          rowData.push("'" + text);
        } else {
          rowData.push(`"${text}"`);
        }
      }
    });

    if (rowData.length > 0) {
      csv.push(rowData.join(","));
    }
  });

  if (csv.length === 0) {
    alert("No data found in the table!");
    return;
  }

  const blob = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

document.addEventListener("DOMContentLoaded", function() {
  const menuIcon = document.querySelector(".apexcharts-menu-icon");
  const menu = document.querySelector(".apexcharts-menu");

  // Toggle menu on icon click
  menuIcon.addEventListener("click", function(e) {
    e.stopPropagation(); // Prevent click from bubbling to document
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  });

  // Close menu when clicking outside
  document.addEventListener("click", function(e) {
    if (!menu.contains(e.target)) {
      menu.style.display = "none";
    }
  });

  // Prevent menu from closing when clicking inside
  menu.addEventListener("click", function(e) {
    e.stopPropagation();
  });

  // Attach CSV export
  document.querySelector(".exportCSV").addEventListener("click", function() {
    downloadCSVFromTable("#expiryTable", "Report.csv");
  });

  // Attach PDF export
  const btnPDF = document.querySelector(".exportPDF");
  if (btnPDF) {
    btnPDF.addEventListener("click", function () {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();

      // Title
      doc.setFontSize(14);
      doc.text("Reports", 14, 16);

      // Table
      doc.autoTable({
        html: '#expiryTable',
        startY: 22,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [183, 28, 28], textColor: [255, 255, 255] }, // red header
        alternateRowStyles: { fillColor: [245, 245, 245] }
      });

      doc.save("Report.pdf");
    });
  }

  // Initialize DataTable
  $('#expiryTable').DataTable({
    paging: true,
    searching: true,
    ordering: true,
    lengthMenu: [5, 10, 25, 50],
    language: {
      search: "Search:"
    }
  });
});
</script>

<style>
.card-header-right {
  position: relative;
  display: inline-block;
  z-index: 11000; /* Higher z-index so menu shows on top */
}

.apexcharts-menu {
  display: none;
  position: absolute;
  right: 0;
  top: 100%;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 5px 0;
  z-index: 11001; /* Higher than parent */
  min-width: 150px;
  pointer-events: auto;
}

.apexcharts-menu-item {
  padding: 8px 15px;
  cursor: pointer;
  font-size: 14px;
  user-select: none;
}

.apexcharts-menu-item:hover {
  background-color: #f0f0f0;
}

/* ✅ Search bar style */
.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
    margin-left: 5px;
}

/* Filter form styling */
.filter-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.filter-section h6 {
    color: #495057;
    margin-bottom: 15px;
    font-weight: 600;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-success:hover {
    background: linear-gradient(45deg, #218838, #1e9b7a);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.1);
}
</style>
