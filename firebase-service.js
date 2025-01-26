// firebase-service.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-app.js";
import { getDatabase, ref, get } from "https://www.gstatic.com/firebasejs/10.10.0/firebase-database.js";

export class FirebaseService {
    constructor(firebaseConfig) {
        this.app = initializeApp(firebaseConfig);
        this.db = getDatabase(this.app);
    }

    async fetchLogs() {
        const logsRef = ref(this.db, "logs");
        const snapshot = await get(logsRef);
        if (snapshot.exists()) {
            const data = [];
            snapshot.forEach((childSnapshot) => {
                data.push(childSnapshot.val());
            });
            return data;
        }
        return [];
    }
}