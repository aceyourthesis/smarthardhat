<?php require 'head.php'; ?>
<?php require 'navBar.php'; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container-fluid my-2">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="d-flex align-items-end flex-grow-1">
                            <h2>Logs</h2>
                        </div>

                        <div class="mx-2">
                            <label for="hardHatSelect" class="form-label">Filter by Hard Hat ID</label>
                            <select id="hardHatSelect" class="form-select border border-success">
                                <option value="0">All Hard Hats</option>
                                <option value="1">Hard Hat 1</option>
                                <option value="2">Hard Hat 2</option>
                            </select>
                        </div>

                        <div class="mx-2">
                            <label for="sortSelect" class="form-label">Sort by Time</label>
                            <select id="sortSelect" class="form-select border border-success">
                                <option value="1">Oldest first</option>
                                <option value="2">Latest first</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div id="loaderDiv" class="d-flex justify-content-center my-3" aria-live="polite" aria-busy="true">
                            <i class="fas fa-spinner fa-spin fa-3x text-success" aria-hidden="true"></i>
                        </div>
                        <table class="table table-bordered table-hover text-center d-none" id="logsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
    import { FirebaseService } from './firebase-service.js';
    import { firebaseConfig } from './firebase-config.js';

    const firebaseService = new FirebaseService(firebaseConfig);

    document.addEventListener("DOMContentLoaded", () => {
    const hardHatSelect = document.getElementById("hardHatSelect");
    const sortSelect = document.getElementById("sortSelect");
    const loaderDiv = document.getElementById("loaderDiv");
    const logsTable = document.getElementById("logsTable");

    // Check if elements exist
    if (!hardHatSelect || !sortSelect || !loaderDiv || !logsTable) {
        console.error("One or more required elements are missing in the DOM.");
        return;
    }

    function formatTimestamp(timestamp) {
        if (!timestamp || isNaN(new Date(timestamp))) {
            return "Invalid Date";
        }
        return new Date(timestamp).toLocaleString();
    }

    function renderLogs(data) {
        logsTableBody.innerHTML = ""; // Clear previous rows

        data.forEach((log) => {
            // Validate log data
            if (!log || typeof log !== "object") {
                console.warn("Invalid log entry:", log);
                return; // Skip invalid logs
            }

            if (
                log.timestamp === undefined ||
                log.hardHatId === undefined ||
                log.status === undefined ||
                log.latitude === undefined ||
                log.longitude === undefined
            ) {
                console.warn("Log with missing fields:", log);
                return; // Skip logs with missing fields
            }

            const timestamp = formatTimestamp(log.timestamp);
            const hardHatId = `Hard Hat ${log.hardHatId}`;
            const status = log.status ? "Active" : "Inactive";
            const latitude = log.latitude;
            const longitude = log.longitude;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${timestamp}</td>
                <td>${hardHatId}</td>
                <td>${status}</td>
                <td>${latitude}</td>
                <td>${longitude}</td>
            `;
            logsTableBody.appendChild(row);
        });
    }

    function filterAndSortLogs(data) {
        let filteredData = data;

        // Filter out invalid logs
        filteredData = filteredData.filter((log) => {
            return (
                log &&
                typeof log === "object" &&
                log.timestamp !== undefined &&
                log.hardHatId !== undefined &&
                log.status !== undefined &&
                log.latitude !== undefined &&
                log.longitude !== undefined
            );
        });

        // Filter by Hard Hat ID
        const selectedHardHatId = hardHatSelect.value;
        if (selectedHardHatId !== "0") {
            filteredData = filteredData.filter(
                (log) => log.hardHatId === parseInt(selectedHardHatId)
            );
        }

        // Sort by time
        const sortOrder = sortSelect.value === "1" ? 1 : -1;
        filteredData.sort((a, b) => (a.timestamp - b.timestamp) * sortOrder);

        return filteredData;
    }

    async function fetchAndRenderLogs() {
        try {
            const data = await firebaseService.fetchLogs();

            // Log the number of logs retrieved
            console.log(`Number of logs retrieved: ${data.length}`);

            renderLogs(filterAndSortLogs(data));

            hardHatSelect.addEventListener("change", () => {
                renderLogs(filterAndSortLogs(data));
            });
            sortSelect.addEventListener("change", () => {
                renderLogs(filterAndSortLogs(data));
            });
        } catch (error) {
            console.error("Error fetching logs:", error);
            logsTableBody.innerHTML = `<tr><td colspan="5" class="text-danger">Failed to load logs. Please try again later.</td></tr>`;
        } finally {
            loaderDiv.classList.add("d-none");
            logsTable.classList.remove("d-none");
        }
    }

    fetchAndRenderLogs();
});
    
    
</script>
</section>

<?php require 'footer.php'; ?>