<?php
require 'database/db_connect.php';

// // List of allowed IP addresses
// $allowed_ips = ['99.251.232.162', '::1'];  // Add your allowed IPs here

// // Get the user's IP address
// $user_ip = $_SERVER['REMOTE_ADDR'];

// // Check if the user's IP is in the allowed list
// if (!in_array($user_ip, $allowed_ips)) {
//     // If the user is not allowed, you can redirect them or show an error
//     // header('HTTP/1.1 403 Forbidden');
//     // exit('Access Denied: You are not allowed to visit this page.');
// }

// Fetch autosend value from the autoemail table
$sql = "SELECT autosend FROM autoemail WHERE id = 1"; // Assuming id is always 1
$result = $conn->query($sql);

// Check if there is a result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $autosend = $row['autosend'];
} else {
    // Default value if no result found
    $autosend = 'false';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="admin-panel">
        <!-- Menu Panel -->
        <div class="menu-panel">
            <ul>
                <li><a href="#" onclick="showWinners()">Winners</a></li>
                <li><a href="#" onclick="showFlagged()">Flagged</a></li>
                <li><a href="#" onclick="showBanned()">Banned</a></li>
                <li><a href="#" onclick="showSettings()">Settings</a></li>
            </ul>
        </div>

    <!-- Settings Panel -->
    <div class="settings-panel">
        <h2>Settings</h2>
        <div class="setting">
            <label for="autoSendToggle">Automatically send gift card codes</label>
            <div class="toggle">
                <input type="checkbox" id="autoSendToggle" <?php echo ($autosend == 'true') ? 'checked' : ''; ?> onclick="toggleAutoSend()">
                <label for="autoSendToggle" class="toggle-switch"></label>
            </div>
        </div>
    </div>


        <!-- Results Panel -->
        <div class="results-panel">
            <!-- Winners Table -->
            <h2 id="panelTitle">All</h2>
            <table id="winnersTable">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>IP Address</th>
                        <th>Time Won</th>
                        <th>Suspicion Level</th>
                        <th>Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM winners WHERE banned = false AND suspicion_level = 'Low'"; // Only select entries where banned is false
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $rowStyle = '';
                            $textColor = '';
                            $textColorApproval = ''; // Initialize text color for approval status
                        
                            if ($row['suspicion_level'] == 'Medium') {
                                $rowStyle = 'background-color: #5d1414;';
                                $textColor = 'color: #fff;';
                            }
                        
                            // Set text color for approval status based on its value
                            if ($row['approval_status'] == 'Code Sent') {
                                $textColorApproval = 'color: green;';
                            } else {
                                $textColorApproval = 'color: orange;';
                            }
                        
                            echo "<tr style='$rowStyle'>
                                    <td id='firstName_{$row['id']}' style='$textColor'>" . $row['first_name'] . "</td>
                                    <td>" . $row['last_name'] . "</td>
                                    <td>" . $row['email'] . "</td>
                                    <td>" . $row['ip_address'] . "</td>
                                    <td>" . $row['time_won'] . "</td>
                                    <td style='$textColor'>" . $row['suspicion_level'] . "</td>
                                    <td style='$textColorApproval'>" . $row['approval_status'] . "</td> <!-- Use $textColorApproval here -->
                                    <td>
                                        <button onclick='flag(\"{$row['id']}\")'>Flag</button>
                                        <button onclick='sendCode(\"{$row['id']}\")'>Send Code</button>
                                        <button onclick='ban(\"{$row['id']}\")'>Ban</button>
                                    </td>
                                </tr>";
                        }
                        
                    } else {
                        echo "<tr><td colspan='9'>No winners found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
function toggleAutoSend() {
    var autoSendValue = document.getElementById("autoSendToggle").checked ? "true" : "false";

    $.ajax({
        url: 'server/update_autosend.php',
        type: 'POST',
        data: { autosend: autoSendValue },
        dataType: 'json',  // Explicitly expect JSON response
        success: function(response) {
            console.log(response);
            if(response.success) {
                console.log(response.message);
            } else {
                console.error(response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error: " + error);
        }
    });
}





        // JavaScript functions to load data into the table based on menu selection
        function showWinners() {
            $("#panelTitle").text("Winners");
            $.ajax({
                url: 'server/adminpanel/get_winners.php',
                type: 'GET',
                dataType: 'json',  // Expect JSON response
                success: function(data) {
                    console.log(data);  // Output data to console for debugging
                    populateTable(data, "Winners");
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);
                }
            });
            $('.settings-panel').removeClass("showSettingsPanel");
            $('.results-panel').show();
        }


        function showFlagged() {
            $("#panelTitle").text("Flagged");

            $.get('server/adminpanel/get_flagged.php', function (data) {
                populateTable(data, "Flagged Users");
            });
            $('.settings-panel').removeClass("showSettingsPanel");
            $('.results-panel').show();
        }

        function showBanned() {
            $("#panelTitle").text("Banned");

            $.get('server/adminpanel/get_banned.php', function (data) {
                populateTable(data, "Banned Users");
            });
            $('.settings-panel').removeClass("showSettingsPanel");
            $('.results-panel').show();
        }

        function showSettings() {
            $("#panelTitle").text("Settings");

            console.log("Settings Panel.");
            $('.settings-panel').addClass("showSettingsPanel");
            $('.results-panel').hide();
        }

        // Function to populate the table with data
        function populateTable(data, tableName) {
            $('tbody').empty();
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(row) {
                    // Check if row contains necessary properties
                    console.log(row);  // Log each row to verify its structure
                    $('tbody').append('<tr>' +
                        '<td>' + (row.first_name || '') + '</td>' +
                        '<td>' + (row.last_name || '') + '</td>' +
                        '<td>' + (row.email || '') + '</td>' +
                        '<td>' + (row.ip_address || '') + '</td>' +
                        '<td>' + (row.time_won || '') + '</td>' +
                        '<td>' + (row.suspicion_level || '') + '</td>' +
                        '<td style="color: orange;">' + (row.approval_status || '') + '</td>' +
                        '<td>' +
                        '<button onclick="flag(' + row.id + ')">Flag</button>' +
                        '<button onclick="sendCode(' + row.id + ')">Send Code</button>' +
                        '<button onclick="ban(' + row.id + ')">Ban</button>' +
                        '</td>' +
                        '</tr>');
                });
            } else {
                $('tbody').append('<tr><td colspan="9">No ' + tableName + ' found.</td></tr>');
            }
        }



        // JavaScript functions for user actions
        function flag(userId) {
            $.post('server/update_suspicion_level.php', { userId: userId, suspicionLevel: 'Medium' }, function (response) {
                if (response.success) {
                    alert('User flagged successfully.');
                    var element = document.getElementById('suspicionLevel_' + userId);
                    if (element) {
                        element.innerText = 'Medium';
                    } else {
                        // console.log('Element not found: suspicionLevel_' + userId);
                    }
                } else {
                    alert('Failed to flag user. Server-side error: ' + response.error);
                }
            }, 'json').fail(function (xhr, status, error) {
                console.log(error);
                alert('Failed to flag user. Error: ' + error);
            });
        }

        function sendCode(userId) {
    $.ajax({
        url: 'server/send_code.php',
        type: 'POST',
        data: { userId: userId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Code sent successfully.');
                var approvalCell = $('#approvalStatus_' + userId);
                approvalCell.text('Code Sent').css('color', 'green');

                console.log("successss");
                console.log(response.email);
                // Now call the prizeEmail.php with the returned email
                sendPrizeEmail(response.email); // Ensure response.email is valid and exists
            } else {
                alert('Failed to send code. Error: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
            console.log("Status: " + status);
            console.log("Response: " + xhr.responseText);
            alert('Failed to send code. Please try again.');
        }
    });
}


        function sendPrizeEmail(email) {
            console.log(email);  // This should not be null, ensure it logs a valid email

            $.ajax({
                url: 'mail/prizeEmail.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    cEmail: email  // Make sure this is the correct variable
                },
                success: function(data) {
                    console.log(data);
                    if (data.status === 'success') {
                        console.log("Prize sent:", data.message);
                    } else {
                        console.log("Error:", data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    console.error("Detailed error:", xhr.responseText);
                }
            });
        }





        function ban(userId) {
            $.post('server/ban_user.php', { userId: userId }, 'json')
                .done(function (response) {
                    if (response.success) {
                        alert('User banned successfully.');
                        // Update the row in the table to show "Banned" next to the user's name
                        var nameElement = document.getElementById('firstName_' + userId);
                        if (nameElement) {
                            nameElement.innerText += ' (Banned)';
                        } else {
                            console.log('Element not found: firstName_' + userId);
                        }
                    } else {
                        alert('Failed to ban user. Error: ' + response.error);
                    }
                })
                .fail(function (xhr, status, error) {
                    alert('Failed to ban user. Network or server error: ' + error);
                });
        }
    </script>

</body>

</html>

