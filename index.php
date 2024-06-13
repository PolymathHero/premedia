<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PXL0FYZPRS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-PXL0FYZPRS');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview People</title>
    <meta name="description" content="Interview other people about certain topics and question them using AI generated questions">
    <link rel="icon" href="/logo.svg">
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <section class="section-1">
        <img src="/logo.svg" alt="Premedia International Logo">
        <div class="main-div">
            <section class="hero-section">
                <h1>Interview other people about certain topics and question them using AI generated questions</h1>
                <form id="myForm" method="post" action="">
                    <input type="text" id="form-topic-input-id" name="form-topic-input-name" placeholder="Enter a topic" required>
                    <input type="url" id="form-calendar-input-id" name="form-calendar-input-name" placeholder="Enter your full calendar link" required>
                    <button type="button" onclick="validateAndSubmitForm()">Submit</button>
                </form>
            </section>
            <div class="board-div">
                board
                <?php
                // Connect to the database
                $conn = new SQLite3('database.sqlite');

                // Create a table
                $conn->exec('CREATE TABLE IF NOT EXISTS form_data (
                    topic_column TEXT,
                    calendar_column TEXT
                )');

                if ($_SERVER["REQUEST_METHOD"] === "POST") {

                    $form_topic_input = $_POST['form-topic-input-name'];
                    $form_calendar_input = $_POST['form-calendar-input-name'];

                    // Insert data into the table
                    $stmt = $conn->prepare("INSERT INTO form_data (topic_column, calendar_column) VALUES (:topic, :calendar)");
                    $stmt->bindValue(':topic', $form_topic_input, SQLITE3_TEXT);
                    $stmt->bindValue(':calendar', $form_calendar_input, SQLITE3_TEXT);
                    $stmt->execute();
                }

                // Execute a SELECT query
                $result = $conn->query("SELECT * FROM form_data");

                // Build HTML table
                echo "<table>";
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    echo "<td>I want to interview someone about " . $row['topic_column'] . "</td>";
                    echo "<td><a target=\"_blank\" href=\"" . $row['calendar_column'] . "\">Book</a></td>";
                    echo "</tr>";
                }
                echo "</table>";

                // Close the connection
                $conn->close();
                ?>
            </div>
        </div>
    </section>

    <script>
        /* use javascript to asynchronously submit the form without reloading the page */
        function validateAndSubmitForm() {
            const topicInput = document.getElementById('form-topic-input-id');
            const calendarInput = document.getElementById('form-calendar-input-id');

            // Check if both fields are valid
            if (!topicInput.checkValidity() || !calendarInput.checkValidity()) {
                // If any of the fields is invalid, the form will not submit
                return;
            }

            // If both fields are valid, proceed with form submission
            submitForm();
        }

        function submitForm() {
            const formData = new FormData(document.getElementById('myForm'));

            fetch('/', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Clear form fields
                        document.getElementById('myForm').reset();

                        // Form submitted successfully
                        return response.text(); // Return the response body as text
                    } else {
                        throw new Error('Failed to submit form');
                    }
                })
                .then(html => {
                    // Parse the HTML response and extract the content of .board-div
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const boardDivContent = doc.querySelector('.board-div').innerHTML;

                    // Update the board-div with the extracted content
                    document.querySelector('.board-div').innerHTML = boardDivContent;
                })
                .catch(error => {
                    console.error('Form submission error:', error);
                });

            // Prevent default form submission
            return false;
        }
    </script>

</body>

</html>