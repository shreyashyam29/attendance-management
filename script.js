let isAdminLoggedIn = false; // ✅ Global flag for admin status  

function setAdmin() {
    const pass = document.getElementById('adminPass').value;
    if (pass === 'admin123') {
        isAdminLoggedIn = true; // ✅ Set admin login state
        alert('✅ Admin login successful');
    } else {
        alert('❌ Incorrect password');
    }
}

function markAttendance() {
    const usn = document.getElementById("att_usn").value.trim();
    const status = document.getElementById("status").value;
    const login = document.getElementById("login").value;

    if (!usn || !login) {
        alert("⚠️ Please enter both USN and login time.");
        return;
    }

    const payload = {
        usn: usn,
        status: status,
        login: login
    };

    fetch("mark_attendance.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(res => {
        if (!res.ok) throw new Error("Network error");
        return res.json();
    })
    .then(data => {
        if (data.status === "marked") {
            alert("✅ Attendance marked successfully!");
        } else {
            alert("❌ Server error: " + data.message);
        }
    })
    .catch(err => {
        console.error("Mark attendance failed:", err);
        alert("❌ Failed to mark attendance.");
    });
}

function viewData() {
    const from = document.getElementById('from').value + " 00:00:00";
    const to = document.getElementById('to').value + " 23:59:59";
    const usn = document.getElementById('view_usn').value.trim();

    if (!from || !to) {
        alert("Please select both from and to dates.");
        return;
    }

    // ✅ Append isAdmin flag
    const adminFlag = isAdminLoggedIn ? "&isAdmin=true" : "";
    fetch(`view_attendance.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&usn=${encodeURIComponent(usn)}${adminFlag}`)
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            const output = document.getElementById("output");
            output.innerHTML = "";

            if (!Array.isArray(data) || data.length === 0) {
                output.innerHTML = "<p>No attendance records found.</p>";
                return;
            }

            let tableHTML = `
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USN</th>
                            <th>Status</th>
                            <th>Login Time</th>
                            <th>Photo</th>`;

            if (isAdminLoggedIn) {
                tableHTML += `<th>Contact</th>`; // ✅ Show contact column if admin
            }

            tableHTML += `</tr></thead><tbody>`;

            data.forEach(entry => {
                tableHTML += `
                    <tr>
                        <td>${entry.id}</td>
                        <td>${entry.usn}</td>
                        <td>${entry.status}</td>
                        <td>${entry.login_time}</td>
                        <td><img src="${entry.photo_path}" width="60" height="60" style="object-fit:cover; border-radius:6px;" alt="photo"></td>`;

                if (isAdminLoggedIn) {
                    tableHTML += `<td>${entry.contact}</td>`; // ✅ Show actual contact
                }

                tableHTML += `</tr>`;
            });

            tableHTML += `</tbody></table>`;

            output.innerHTML = tableHTML;
        })
        .catch(err => {
            console.error("Fetch error:", err);
            document.getElementById("output").innerHTML = "<p style='color:red;'>❌ Error fetching data</p>";
        });
}

function exportData() {
    const from = document.getElementById('from').value + " 00:00:00";
    const to = document.getElementById('to').value + " 23:59:59";
    const usn = document.getElementById('view_usn').value.trim();

    if (!from || !to) {
        alert("Please select both from and to dates.");
        return;
    }

    // ✅ Fixed line below
    window.location.href = `export_attendance.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&usn=${encodeURIComponent(usn)}`;
}
