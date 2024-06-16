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
    <meta name="description"
        content="Interview other people about certain topics and question them using AI generated questions">
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
                    <input type="text" id="form-topic-input-id" name="form-topic-input-name" placeholder="Enter a topic"
                        required>
                    <input type="url" id="form-calendar-input-id" name="form-calendar-input-name"
                        placeholder="Enter your full calendar link" required>
                    <button type="button" onclick="validateAndSubmitForm()">Submit</button>
                </form>
            </section>
            <div class="board-div">
                board
                <?php
                require_once 'vendor/autoload.php'; // Adjust the path as necessary
                
                use SQLiteCloud\SQLiteCloudClient;
                use SQLiteCloud\SQLiteCloudRowset;

                // Connect to SQLite Cloud
                $sqlitecloud = new SQLiteCloudClient();
                $sqlitecloud->connectWithString('process.env.SQLITECLOUD_CONNECTION_STRING');


                $db_name = 'database.sqlite';
                $sqlitecloud->execute("USE DATABASE {$db_name}");
                // Create a table
                $sqlitecloud->execute('CREATE TABLE IF NOT EXISTS form_data_table (
                    topic_column TEXT,
                    calendar_column TEXT
                )');

                if ($_SERVER["REQUEST_METHOD"] === "POST") {

                    $form_topic_input = $_POST['form-topic-input-name'];
                    $form_calendar_input = $_POST['form-calendar-input-name'];

                    // Insert data into the table
                    $stmt = $sqlitecloud->execute("INSERT INTO form_data_table (topic_column, calendar_column) VALUES ('$form_topic_input', '$form_calendar_input')");

                }

                // Select all data from the table and input it into a variable
                $result = $sqlitecloud->execute("SELECT * FROM form_data_table");

                // Assuming $result is already populated with data from the SQLiteCloudRowset object
                $data = $result->data;
                $colname = $result->colname;

                echo "<table border='1'>";
                echo "<tr><th>{$colname[0]}</th><th>{$colname[1]}</th></tr>"; // Table headers
                
                // Loop through the data array and populate the table rows
                for ($i = 0; $i < count($data); $i += 2) {
                    echo "<tr>";
                    echo "<td>{$data[$i]}</td>"; // Topic
                    echo "<td><a href='{$data[$i + 1]}'>{$data[$i + 1]}</a></td>"; // Calendar link
                    echo "</tr>";
                }

                echo "</table>";

                // Close the connection
                $sqlitecloud->disconnect();
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