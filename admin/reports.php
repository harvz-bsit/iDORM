<?php
$pageTitle = "Reports";
include 'includes/header.php';
?>

<div class="container py-5 min-vh-100">
    <h2 class="text-maroon fw-bold mb-4">📊 Reports</h2>

    <!-- Filters -->
    <div class="card shadow-sm p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Report Type</label>
                <select id="report_type" class="form-select">
                    <option value="students">Students</option>
                    <option value="contracts">Contracts</option>
                    <option value="applications">Applications</option>
                    <option value="passlips">Passlips</option>
                    <option value="rooms">Rooms</option>
                    <option value="payments">Payments</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status (optional)</label>
                <select id="status" class="form-select">
                    <option value="">All</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Month (Payments)</label>
                <input type="month" id="month" class="form-control">
            </div>
        </div>

        <button class="btn btn-maroon mt-3" onclick="generateReport()">
            Generate Report
        </button>
    </div>

    <div id="paymentExtras" class="d-none mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold text-maroon text-center">Payment Status Overview</h6>
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold text-maroon">Summary</h6>
                    <p class="mb-1">Paid: <span id="paidCount" class="fw-bold text-success">0</span></p>
                    <p>Unpaid: <span id="unpaidCount" class="fw-bold text-danger">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview -->
    <div class="card shadow-sm p-4 d-none" id="reportPreviewCard">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Report Preview</h5>
            <div>
                <button class="btn btn-outline-maroon btn-sm" onclick="downloadReport('pdf')">PDF</button>
                <button class="btn btn-outline-maroon btn-sm" onclick="downloadReport('csv')">Excel</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="reportTable">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let currentParams = {};

    function generateReport() {
        const type = document.getElementById('report_type').value;
        const statusVal = document.getElementById('status').value;
        const month = document.getElementById('month').value;

        currentParams = {
            type: type,
            status: statusVal,
            month: month
        };

        fetch('../includes/processes.php?action=preview_report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(currentParams)
            })
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    alert("No data found.");
                    return;
                }

                document.getElementById('reportPreviewCard').classList.remove('d-none');

                const headers = Object.keys(data[0]);
                let thead = "<tr>";
                headers.forEach(h => thead += `<th>${humanize(h)}</th>`);
                thead += "</tr>";

                let tbody = "";
                data.forEach(row => {
                    tbody += "<tr>";
                    headers.forEach(h => tbody += `<td>${row[h]}</td>`);
                    tbody += "</tr>";
                });

                reportTable.querySelector("thead").innerHTML = thead;
                reportTable.querySelector("tbody").innerHTML = tbody;
            });
    }

    function humanize(text) {
        return text
            .replace(/_/g, ' ') // replace _ with space
            .replace(/\w\S*/g, (w) => // capitalize each word
                w.charAt(0).toUpperCase() + w.substr(1).toLowerCase()
            );
    }


    function downloadReport(format) {
        const params = new URLSearchParams(currentParams);
        params.append("format", format);
        window.open("../includes/processes.php?action=download_report&" + params.toString());
    }
</script>

<?php include 'includes/footer.php'; ?>