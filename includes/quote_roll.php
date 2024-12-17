<?php
session_start();

include 'dbh.inc.php';

// Array of quotes
$quotes = [
    ["text" => "The only way to do great work is to love what you do.", "author" => "Steve Jobs"],
    ["text" => "Success is not final, failure is not fatal: It is the courage to continue that counts.", "author" => "Winston Churchill"],
    ["text" => "Believe you can and you are halfway there.", "author" => "Theodore Roosevelt"],
    ["text" => "Don’t watch the clock; do what it does. Keep going.", "author" => "Sam Levenson"],
    
    ["text" => "You miss 100% of the shots you don’t take.", "author" => "Wayne Gretzky"]
];

// Select a random quote from the array
$randomQuote = $quotes[array_rand($quotes)];

// Display the quote
echo "<div class='quote'> <section class='quotes'> <p class='quote-text'>\"{$randomQuote['text']}\"</p>";
echo "<p class='quote-author'>- {$randomQuote['author']}</p> </div> </section>" ;
//  echo "<button class='btn3' onclick=window.location.reload();'>New Quote</button>";
