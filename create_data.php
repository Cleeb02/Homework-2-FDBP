<?php
$firstNamesFile = fopen("data/first_names.csv", "r");
$firstNames = fgetcsv($firstNamesFile, 0, ",");
//Open File -> Separate each name into an index in an array

$lastNames = file("data/last_names.txt");
//Goes line by line luckily. Which results in an array

$streetNamesFile = file("data/street_names.txt");
$allStreetNames = [];
//Open File and initialize an empty array

foreach ($streetNamesFile as $names) {
    $lineNames = explode(":", $names);
    $trimmedNames = array_map("trim", $lineNames);
    $allStreetNames = array_merge($allStreetNames, $trimmedNames);
    //for each line from the array returned from file() do this:
    //every line separate the words into an array
    //trim any white space
    //merge the final trimmed array with the allStreetNames Array
}

$streetTypesFile = file("data/street_types.txt");
$allStreetTypes = preg_split('/\.\.;/', $streetTypesFile[0]);
//Street types are all on one line
//use a regular expression and preg_split to look for a pattern of ..;
//seperate words based off that criteria 

$domainNamesFile = file("data/domains.txt");
$allDomains = preg_split('/(?<=\.com|\.edu)\./', $domainNamesFile[0]);
//domains are all one one line so again we can use a regular expression to get domains
//look back for a .com or edu. then look for a period following this 
function generateCustomerData()
{
    global $firstNames, $lastNames, $allStreetNames, $allStreetTypes, $allDomains;

    $randomCustomerData = array();

    for ($i = 0; $i < 25; $i++) {
        $currentLine = array($i, $firstNames[rand(0, count($firstNames) - 1)], $lastNames[rand(0, count($lastNames) - 1)], rand(10, 999) . " " . $allStreetNames[rand(0, count($allStreetNames) - 1)] . " " . $allStreetTypes[rand(0, count($allStreetTypes) - 1)]);
        $fullEmail = $currentLine[1] . "." . trim($currentLine[2] . " ") . "@" . $allDomains[rand(0, count($allDomains) - 1)];
        //create a random email with the random first and last name then push to the end of the arrray
        //Data format is always the same so the email is always the last index of the array
        array_push($currentLine, $fullEmail);
        array_push($randomCustomerData, $currentLine);
    }
    return $randomCustomerData;
    //Returns a 2D array of random customer data
    // Each subaray follows this format [index0:CustomerID, index1:customerFirstName, index2 CustomerLastName, index3 Customer Full Address, index4: Customer Email]
}
function displayCustomerData($data_Arr)
{
    //First look at the table and headings before reading the loop logic
    //Keep in mind the format of the data from the generateCustomerData function above
    for ($i = 0; $i < count($data_Arr); $i++) {
        print "<tr>";
        //all this line does is open the row tag
        for ($j = 0; $j < count($data_Arr[$i]); $j++) {
            $currValue = $data_Arr[$i][$j];
            //store current value of the subarray
            print ("<td>$currValue</td>");
            //for every subarray or "customer" print a new column for each value in current subarray
            //It will print colums in this order : id, firstName, LastName, Full Address, Full email
        }
        print "</tr>";
        //close the row tag
    }
}

function writeDataToFile($data_Arr)
{
    $dataFile = fopen("data/customers.txt", "a");

    for ($i = 0; $i < count($data_Arr); $i++) {

        $trimmedValues = array_map('trim', $data_Arr[$i]);
        $currLineValues = implode(":", $trimmedValues);
        fwrite($dataFile, $currLineValues . "\n");
    }

    fclose($dataFile);

}
?>

<h1
    style="text-align: center; margin-top: 10px; background-color:black; color: white; padding:10px; border-radius: 5px">
    Files Assignment
</h1>
<div class="container" style="display:flex; justify-content:space-evenly; border: 2px solid black;">

    <div class="container" style="margin-top: 10px;">
        <h3>First Names Array</h3>
        <pre style="border: 1px solid black; padding: 5px;"><?php
        print_r($firstNames);
        ?></pre>
    </div>
    <div class="container">
        <h3>Last Names Array</h3>
        <pre style="border: 1px solid black; padding: 5px; "><?php
        print_r($lastNames);

        ?></pre>
    </div>
    <div class="container">
        <h3>Street Names Array</h3>
        <pre style="border: 1px solid black; padding: 5px;"><?php

        print_r($allStreetNames);
        ?></pre>
    </div>
    <div class="container">
        <h3>Street Types Array</h3>
        <pre style="border: 1px solid black; padding: 5px;"><?php
        print_r($allStreetTypes);

        ?></pre>
    </div>
    <div class="container">
        <h3>Email Domain Array</h3>
        <pre style="border: 1px solid black; padding: 5px;"><?php
        print_r($allDomains);

        ?></pre>

    </div>

</div>
<h2
    style="text-align: center; margin-top: 10px; background-color:black; color: white; padding:10px; border-radius: 5px">
    Random Customer Data Table</h2>
<div class="container"
    style="display:flex; justify-content: center; border: 2px solid black; padding: 25px; margin: 5px">

    <table border="3">
        <tr>
            <!-- Notice the headings are in the same order as the subarray values in CustomerData -->
            <th>Customer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Email</th>
        </tr>
        <?php
        $tableData = generateCustomerData();
        displayCustomerData($tableData);
        writeDataToFile($tableData);
        // Simply call function to generate data then print. Finally write to file.
        ?>
    </table>
    <div class="container" style="display: flex; justify-content:center">

    </div>
</div>

<?php

?>