Real-Time Chat Application
This project is a real-time chat application built from scratch using HTML, CSS, JavaScript, PHP, and MySQL. It leverages a custom WebSocket server to enable instant messaging between users.

Features
User Authentication: Users can register new accounts and log in to the application.

Real-Time Messaging: Instant message exchange between users using WebSocket technology.

Client-Side: A clean, modern user interface built with plain HTML, CSS, and JavaScript.

Server-Side: A robust backend powered by PHP and MySQL for user and message data storage.

Technologies Used
Frontend:

HTML5

CSS3

JavaScript (Vanilla JS)

Backend:

PHP

MySQL

Networking:

WebSockets

Getting Started
Follow these steps to set up and run the project on your local machine.

Prerequisites
You need a local server environment with PHP and MySQL. We recommend using a package like XAMPP or MAMP, which bundles Apache, MySQL, and PHP together.

1. Database Setup
Open your MySQL client (e.g., phpMyAdmin).

Create a new database named chat_app.

Execute the SQL code from the chat_app.sql file to create the necessary users and messages tables.

2. File Structure
Ensure your project directory is structured as follows:

chat_app/
├── api/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── includes/
│   ├── config.php
│   └── websocket_server.php
├── public/
│   ├── chat.html
│   ├── chat.js
│   ├── index.html
│   ├── script.js
│   └── style.css
└── chat_app.sql

Place the entire chat_app folder in your web server's root directory (e.g., htdocs for XAMPP).

3. Backend Configuration
Database Connection: Open includes/config.php and update the database credentials if they differ from the defaults (root with an empty password).

WebSocket Server: The WebSocket server is a standalone PHP script. You must run it manually from your terminal.

cd /path/to/your/chat_app/includes
php websocket_server.php

This will start the WebSocket server, which listens for connections on localhost:8080.

4. Running the Application
Ensure your Apache server and MySQL database are running.

Ensure your WebSocket server is running in a separate terminal.

Open your web browser and navigate to the following URL:

http://localhost/chat_app/public/index.html

You can now register a new account and start chatting in real-time.

Usage
Registration: On the main page, click the "Register here" link to create a new user account.

Login: Use your new credentials to log in.

Chatting: Once logged in, you can type messages in the input box and click "Send." Your message will be instantly broadcast to all other users connected to the WebSocket.

Logout: Click the "Logout" button to end your session.

Contributing
Feel free to fork the repository and contribute to the project. Any improvements to the code, security, or design are welcome.
