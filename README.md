# Distributed Systems Exercise using Sockets in PHP

This project was developed as an exercise for the Distributed Systems course at the Universidad Nacional del Sur.

The goal of the project is to implement a client-server architecture where the client sends a request to three servers to obtain information about a user: one server stores user information, another stores messages, and the third stores the names of photo files for each user.

To test the project, you need to initialize the servers first. To do so, navigate to the root directory of the project and run the following commands in separate terminal windows:

* Start Server 1: `php server/name/NameServer.php`
* Start Server 2: `php server/name/MessageServer.php`
* Start Server 3: `php server/name/PhotoFilenameServer.php`

Once the servers are running, you can execute the client by running the following command:

`php client.php PersonName`

This will send a request to Server 1 to check if the user exists, then Server 1 will query Server 2 and Server 3 for messages and photo file names, respectively. Finally, Server 1 will send the requested information back to the client, which will display it on the screen.

## Technologies Used

This project was developed using PHP and sockets to create a client-server architecture.

## Installation

To run this project, you will need to have PHP 8+ installed on your machine.

## Usage

To use this project, first initialize the servers as described above, then run the client with a person's name as a parameter.
