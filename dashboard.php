<?php include 'head.php'; ?>
<?php include 'navBar.php'; ?>

<section>
    <div class="container p-0">
        <div class="row g-0">
            <!-- Dashboard Column (Full width on mobile, 7/12 on desktop) -->
            <div class="col-12 col-lg-7 bg-light p-3">
                <div class="container-fluid">
                    <h2 class="mb-2">Dashboard</h2>
                    <div class="table-responsive">
                        <table class="table table-hover text-center rounded">
                            <thead class="table-transparent">
                                <tr>
                                    <th>ID</th>
                                    <th>User Image</th>
                                    <th>Status</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardTableBody">
                                <!-- Dashboard data rows will be rendered here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Map Column (Hidden on mobile, 5/12 on desktop) -->
            <div class="col-12 col-lg-5 p-3 d-none d-lg-block">
                <div id="map" style="height: 90vh; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Show map below the table on mobile -->
    <div class="d-block d-lg-none p-3">
        <div id="mobileMap" style="height: 50vh; width: 100%;"></div>
    </div>

    <script type="module">
        // Import the necessary Firebase SDKs
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-analytics.js";
        import { getDatabase, ref, onValue, push, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-database.js";

        // Import map functions
        import { initDesktopMap, initMobileMap, showMap } from './map2.js';

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAy_bQFynVXe_RflYLYgsU0skc8ThOKDYE",
            authDomain: "smarthardhat-22267.firebaseapp.com",
            databaseURL: "https://smarthardhat-22267-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "smarthardhat-22267",
            storageBucket: "smarthardhat-22267.appspot.com",
            messagingSenderId: "1001952473982",
            appId: "1:1001952473982:web:a309b046972d3602d5b92f",
            measurementId: "G-X155LG29H6"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const db = getDatabase();

        const dashRef = ref(db, 'hardHats');
        const logsRef = ref(db, "logs");

        function writeLogs(id, stat, lat, long) {
            push(logsRef, {
                timestamp: serverTimestamp(),
                hardHatId: id,
                latitude: lat,
                longitude: long,
                status: stat
            });
        }

        const dashboardContent = document.getElementById("dashboardTableBody");

        // Function to update the dashboard content
        function updateDashboardContent(data) {
            dashboardContent.innerHTML = ""; // Clear existing rows

            // Populate table rows with data
            data.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.idNumber}</td>
                    <td><img id="imageElement" src="${item.imageReturned}" alt="Image" width="100%" style="max-width: 180px;"></td>
                    <td>${item.isActive ? "Active" : "Inactive"}</td>
                    <td>${item.locLatitude}</td>
                    <td>${item.locLongitude}</td>
                    <td>
                        <button value="${item.locLongitude},${item.locLatitude}" class="showmap btn btn-success">
                            <i class="fas fa-map-marker-alt"></i> Map
                        </button>
                    </td>
                `;
                dashboardContent.appendChild(row); // Add the row to the table
            });
        }

        // Listen for changes in the "hardHats" node
        onValue(dashRef, (snapshot) => {
            const data = []; // Array to store retrieved data

            // Extract each child node value into the data array
            snapshot.forEach((childSnapshot) => {
                data.push(childSnapshot.val());
            });

            console.log("Retrieved Data:", data); // Log the final data array for debugging

            // Update the dashboard display with the new data
            updateDashboardContent(data);
        });

        // Initialize maps
        initDesktopMap();
        initMobileMap();

        // Event delegation to listen for clicks on buttons within the dashboardTableBody
        dashboardContent.addEventListener('click', function(event) {
            console.log("Button clicked");
            // Check if the clicked element is a button with class 'showmap'
            if (event.target && event.target.matches('button.showmap')) {
                const coordinates = event.target.value.split(',');
                const latitude = parseFloat(coordinates[1]);
                const longitude = parseFloat(coordinates[0]);

                // Call a function to handle the map action
                showMap(longitude, latitude);
            }
        });
    </script>
</section>

<?php include 'footer.php'; ?>