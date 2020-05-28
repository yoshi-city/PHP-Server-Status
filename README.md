# PHP-Server-Status
Adds a server status to your website, as featured on https://yoshi.city

Saves results to a cache file to avoid querying servers too often, individually configured with the interval parameter

Displays results as a table, styled by css/tables.css

# Dependencies

Requires timeago.class.php (bundled in repo)

# Usage

1. Include PHPNetworkStatus.php
2. Create new instance of PHPNetworkStatus
3. Call GetStatus() function and echo it to the page, this can be done as many time as you like

# Code example

See example.php for full code examples and required parameters
