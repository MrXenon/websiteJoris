


<?php
  require 'vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\IOFactory;
// Load the Excel file
$spreadsheet = IOFactory::load('INHOUD/schapendrijven/agendaJohanna.xlsx');

// Get the sheet names
$sheetNames = $spreadsheet->getSheetNames();

// Determine the number of sheets
$sheetCount = count($sheetNames);

// Define the number of items to display per page
$itemsPerPage = 1;

// Get the current page number from the query string
$pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;

// Determine the index of the first item on the current page
$firstIndex = ($pageNumber - 1) * $itemsPerPage;

// Determine the index of the last item on the current page
$lastIndex = $firstIndex + $itemsPerPage - 1;

// Iterate through each worksheet
for ($i = 0; $i < $sheetCount; $i++) {
    // Check if the current sheet is within the range of the current page
    if ($i >= $firstIndex && $i <= $lastIndex) {
        // Get the worksheet object
        $worksheet = $spreadsheet->getSheet($i);

        // Get the sheet name
        $sheetName = $sheetNames[$i];

        // Get the highest row and column indexes to determine the size of the table
        $highestRow = $worksheet->getHighestDataRow();
        $highestColumn = $worksheet->getHighestDataColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Start creating the HTML table
        echo '<table class="agenda-table">';
        
        // Output the pagination links
echo '<div class="pagination-holder" style="overflow-x: scroll; white-space: nowrap; height: 70px; overflow-y: hidden;"><div class="pagination" style="margin-top:20px;">';

// // Output the "previous" link if necessary
// if ($pageNumber > 1) {
//     $previousPage = $pageNumber - 1;
//     echo '<a href="?page=' . $previousPage . '">Vorige</a> ';
// }

// Output the numbered page links
for ($i = 1; $i < 15; $i++) {
    if ($i == $pageNumber) {
        echo '<strong class="pageNumber">' . $sheetNames[$i - 1] . '</strong> ';
    } else {
        echo '<a href="?page=' . $i . '">' . $sheetNames[$i - 1] . '</a> ';
    }
}


// // Output the "next" link if necessary
// if ($pageNumber < ceil($sheetCount / $itemsPerPage)) {
//     $nextPage = $pageNumber + 1;
//     echo '<a href="?page=' . $nextPage . '">Volgende</a>';
// }

echo '</div></div>';

        // Output the sheet name as the pagination label on mobile
        echo '<div class="mobile-pagination" style="font-weight:bold; text-align:center; font-size:30px;">' . $sheetName . '</div>';
        
        // Iterate through each row
        for ($row = 1; $row <= $highestRow; $row++) {
            echo '<tr>';

            // Iterate through each column
            for ($col = 1; $col <= 4; $col++) {
                // Get the value of the cell
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                // Output the cell value in a table cell
                echo '<td>' . $cellValue . '</td>';
            }

            echo '</tr>';
        }

        echo '</table>';
    }
}

?>