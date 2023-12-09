<?php 
function generateRandomEmail() {
    $username = 'user' . rand(100, 999); // Generate a random username
    $domain = ['example.com', 'test.com', 'domain.com']; // Add more domains as needed
    $randomDomain = $domain[array_rand($domain)]; // Choose a random domain from the list

    $email = $username . '@' . $randomDomain; // Combine username and domain to form the email address
    return $email;
}
?>