
<?php
session_start();


// Array of fitness facts with unique IDs
include 'dbh.inc.php'; // Database connection file
include './generate_facts_roll.php'; // The PHP file with the array of fitness facts

// Fetch all fact IDs that have already been displayed
$seenFacts = [];
$query = $pdo->query("SELECT fact_id FROM viewed_facts");
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $seenFacts[] = $row['fact_id'];
}

// Filter facts to only those that haven't been seen
$unseenFacts = array_filter($facts, function($fact) use ($seenFacts) {
    return !in_array($fact['id'], $seenFacts);
});

// Check if there are any unseen facts left
if (!empty($unseenFacts)) {
    // Pick a random unseen fact
    $randomFact = $unseenFacts[array_rand($unseenFacts)];

    // Display the fact
    echo "<p class='fact-text'>{$randomFact['text']}</p>";

    // Store the displayed fact in the database as "seen"
    $stmt = $pdo->prepare("INSERT INTO viewed_facts (fact_id) VALUES (:fact_id)");
    $stmt->execute([':fact_id' => $randomFact['id']]);
} else {
    // If all facts have been seen, reset the list
    $pdo->exec("DELETE FROM viewed_facts");
    echo "<p class='fact-text'>You've seen all the facts! Reload to start over.</p>";
}
?>

