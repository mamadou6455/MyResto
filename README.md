# ApplicationRestaurant

## Description
ApplicationRestaurant is a web application that allows users to manage their restaurant reservations. Users can view their existing reservations and modify them as needed.

## File Structure
- **db.php**: Establishes a connection to the database using PDO or MySQLi, containing the necessary credentials and settings for database access.
- **mes_reservations.php**: Displays the user's reservations by retrieving data from the database and presenting it in a user-friendly format.
- **modifier_reservation.php**: Responsible for modifying an existing reservation. It retrieves reservation details based on an ID passed via the URL, allows users to update the reservation information, and processes form submissions to save changes to the database.
- **style.css**: Contains styles for the application, defining the visual appearance of HTML elements across various pages.

## Setup Instructions
1. Clone the repository to your local machine.
2. Ensure you have a web server with PHP and a database server (MySQL) installed.
3. Configure the database connection in `db.php` with your database credentials.
4. Import the necessary database schema for reservations into your MySQL database.
5. Access the application through your web server.

## Usage
- Navigate to `mes_reservations.php` to view your current reservations.
- Click on a reservation to modify it, which will take you to `modifier_reservation.php`.
- Fill out the form to update your reservation details and submit to save changes.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.