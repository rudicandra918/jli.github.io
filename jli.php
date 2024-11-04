<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function scrapeData($url) {
    $data = [];
    $html = file_get_contents($url);
    
    if ($html === FALSE) {
        die("Error fetching data from the URL.");
    }

    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    // Target the div with the specified class
    $contentDiv = $xpath->query('//div[contains(@class, "col-md-8 main-content")]')->item(0);
    
    // Extract table rows from within this div
    if ($contentDiv) {
        $rows = $xpath->query('.//table/tbody/tr', $contentDiv);

        foreach ($rows as $row) {
            $columns = $row->getElementsByTagName('td');
            $rowData = [];
            foreach ($columns as $column) {
                $rowData[] = trim($column->textContent);
            }
            if (!empty($rowData)) {
                $data[] = $rowData;
            }
        }
    }

    return $data;
}

$url = "https://fiskal.kemenkeu.go.id/informasi-publik/kurs-pajak";
$data = scrapeData($url);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker();
        });
    </script>
</head>
<body>

<h1>Scraped Data from Kurs Pajak</h1>

<!-- Date Picker -->
<label for="datepicker">Select Date:</label>
<input type="text" id="datepicker">

<!-- Data Table -->
<table border="1">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
            <th>Column 3</th>
            <!-- Add more headers based on the actual data -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <?php foreach ($row as $column): ?>
                    <td><?php echo htmlspecialchars($column); ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
