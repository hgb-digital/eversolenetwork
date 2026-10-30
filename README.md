# Eversole Network

**Hudson Brackeen**  
*Uploaded for the MIT Maker Portfolio*

> **For reviewers:** This is an extensive document that outlines the majority of the systems housed in this project. I understand your time is limited, so I would recommend reviewing the bullet points under **"Client Side"** and **"Server Side"**, only reading elsewhere if you'd like to know more information.

## Description

This is a game high-score database created using PHP, MySQL, HTML, CSS, and JS that includes a client designed for the Godot game engine. Games running the client can log scores through the web server, earn XP, and reward tokens. Users who log in on the web can buy items from the shop, view high-scores, and see other players' progress. This project was started to replace a former teacher's old arcade database system which relied on `.txt` files and manual Dropbox syncing. It was his students' games I was creating this for.

## Development

To develop this web app, I ran a XAMPP development web server (including Apache and a MySQL database) and coded using Visual Studio Code. I used the Godot game engine and its GDScript (Python-based language) to develop the client-side program. I used AI tools such as Claude and ChatGPT to replace my traditional workflow (plowing through YouTube tutorials and Stack Overflow entries) with a system that could give me advice tailored to my project. AI helped me quickly learn syntax and brainstorm solutions to more complex issues.

A MySQL configuration file for the Eversole Network database exported from phpMyAdmin (a database management application) is also included in this repository. Database files for both version 1 and version 2 are included in their respective folders. The `Version 1` and `Version 2` folders contain 2 different versions of the web server code; see the technical description for information on which version contains what. The `godot` folder is also included and contains the client code used for the system. `godot/empty_game` includes just the client loader, while `godot/with_client` contains the actual client program; again, see the technical description to understand what this means.

Throughout the process, I met Mr. Eversole (hence Eversole Network) multiple times to discuss progress and help understand features he'd like me to add. He is the former teacher I was creating this for.

For version 1, the full server was hosted on `eversole.network` but is currently disconnected due to it being out of use (see technical description).

## Features

This is a general overview of the features of the game. For an in-depth technical review on how specific components function, see below.

Eversole Network was built for Godot, the primary game engine his students used to create their games, and utilizes a full-stack web server to store user data and game scores.

### Client Side

#### Dynamic Runtime Download
* **Automated Client Fetch:** On game startup, an ENET (Eversole Network) loader script pings the web server to request the latest client package over HTTP (see `godot/empty_game/ENET/enet_loader.gd`).
* **Runtime Execution:** The game downloads and runs the latest client build on the fly rather than storing the client code in the game build.
* **Seamless Updates:** Allows central client updates across the network without requiring students to recompile or re-export their games.

#### Client Interface & User Experience
* **In-Game Overlay:** Pressing `SHIFT + TAB` opens the interactive ENET interface where players can log in (или choose to continue as a guest).
* **Score & Leaderboard Access:** Displays game-specific high scores to all players, alongside personal score histories for logged-in users.
* **Progress & Rewards:** Displays player XP (gained through playtime) and coin balances in the UI.

#### Developer Integration
* **Score Submission:** Games log high scores simply by calling the `post_score()` function (see `godot/with_client/ENET/scripts/enet_functions.gd`).
* **In-Game Economy:** Games can award or deduct player coins dynamically based on gameplay achievements and shop purchases.

### Server Side

#### User Features
* **Global Leaderboards:** Logged-in users can view high scores across all games connected to the platform.
* **Developer Dashboard:** Game creators can view the unique Game ID and password required by their Godot client to enable score logging.
* **Game & Version Registration:** Creators can submit requests to admins to register new games or request new credentials for updated game versions (ideal for resetting scoreboards when scoring mechanics change).
* **Player Profiles:** Users can inspect public profiles showing player XP, earned coins, unlocked achievements, and live online status.
* **Web Shop:** Players can spend earned coins on rewards, such as custom 3D-printed gadgets.

#### Administrator Controls
* **Game & Request Management:** Approve or deny game/version registration requests, manually add new game entries (automatically notifying owners), and delete obsolete games.
* **Score Moderation & User Safety:** Audit leaderboards to remove malicious or cheated scores, with the ability to ban accounts when necessary.
* **Platform Communications:** Broadcast global server messages across the home screen.
* **Client Distribution:** Access and download the official client loader script package for distribution to developers.
* **Economy Analytics:** Admins can track system-wide and per-game metrics, including total playtime, total coins earned/spent, and coin earn rates (coins/min) to maintain balance.
* **Session Management:** View real-time active connections (showing who is playing what) with the ability to manually disconnect specific sessions.

## Technical Description

Most features were first introduced in version 1 (hosted on `eversole.network`), but a few features were added in version 2. When each of these features was first introduced is denoted by either **(v1)** or **(v2)**. This project is still in development, but version 1 represents the first functional prototype.

### Version 1 Hosting (v1)
For version 1, the web server was run on a Ubuntu Linux Apache web server on my home network (see VLAN project in portfolio for how I made this secure) and connected to a Cloudflare tunnel. The Cloudflare tunnel allowed traffic from `eversole.network` to connect to my home server without having to directly expose my WAN IP (internet IP) to the public or use port forwarding. Using PHPMailer and SMTP2GO, I sent account verification/password reset emails from `account@eversole.network`, an email I set up through Cloudflare (see `Version 2/private/scripts/login/email_verification.php`).

### Email Verification / Password Reset (v2)
When a user needs to verify their account (required for use) or reset their password, the server can send a message to the user's email. This works by generating a link with a token and storing it in the database (see `Version 2/private/scripts/login/email_verification.php`). When the user clicks on the link, the page evaluates the token and its type, then verifies the account or helps the user reset their password. I used Claude to help me write this portion due to my limited knowledge of the API.

### Password Hashing (v1)
When users sign up, the password entered into the database is hashed using Blowfish's hashing algorithm in the `$2y$` format (native to PHP).

### Logging Anonymous/Offline Scores (v1)
In some cases, a user may earn a high score without being logged in. In this case, the client stores the score and will post it if the user decides to log in. The same is true for earning scores while offline: the scores are saved and will automatically post when reconnected to the server (assuming the user doesn't close the game).

### Client Loading in Godot (v1)
Each game puts a client loader scene into their project and makes it a singleton—a scene that is always loaded no matter what part of the game you're in, acting like a globally accessible class. On running the game, the loader contacts `eversole.network` and attempts to download `current.pck` using the HTTP Request node in Godot. If successful, the game unloads the `.pck` file (which contains the most recent client program) and runs it. There are 2 main benefits to using this system: 
1. You don't need to recompile each game when you make an update to the client software.
2. This helps prevent users from being able to decompile a game and post their own fake scores (see **Score Authenticity Measures**).

### Score Authenticity Measures (v1)
There are 2 main measures put into place to ensure the authenticity of posted scores:
1. The actual client program that posts scores is only loaded at runtime (no actual client code is stored with the game files), making it harder for "hackers" to view the client program's code.
2. Each game is assigned a unique ID and password that they must send to the server before being able to create a valid session and post scores (see **Game Pinging**). 

*Note:* If users find a way to decompile the game, they could theoretically access the password, ID, and client program to post fake scores. Currently, I have not found a viable solution to mitigate this situation entirely; instead, the game economy tracking system acts as a safeguard to flag abnormal activity (e.g., too many coins in little playtime, see **Game Economy Tracking**).

### Active Sessions & Game Pinging (v1)
When a client successfully connects to the server, the server creates an entry in the `sessions` table in the database tracking data such as login time, last ping timestamp, game being played, player ID, and logged scores (see `Version 2/private/scripts/game_managment/ping.php`). When admins log onto the connections page, they can view all active sessions. Every 5 seconds, the game pings the server. If 10 seconds pass without a ping, the session is disconnected. If the game loses connection, a new session is started upon reconnect. If the admin disconnects a session manually, the client will not retry connecting. This data also allows the server to display online player counts on the website and within game clients.

### Game Economy Tracking (v2)
Because the system allowed games to reward users with currency, I wanted a way to track how much each game was rewarding to ensure fairness across games. I used Claude to generate a list of statistics I could track and adapted it to fit my database infrastructure. Coins per playtime minute also help admins price items in the shop appropriately.

### The Great Database Restructure (v1)
This was my first project using web server architecture and databases. Originally, every new game created its own table in the database (e.g., `flappy.1` for Flappy version 1). However, I realized having the server create new tables dynamically was inefficient and posed SQL injection risks. I restructured the database to rely on **Foreign Keys**. Instead of a separate table per game score log, there is a single `scores` table referencing each game's `game_id`. When querying scores, the server uses `SELECT WHERE game_id = {id}`. Additionally, if a game is deleted, the deletion cascades to automatically remove associated scores.

### File Segmentation (v1)
Project files are split into two main folders: `htdocs` and `private`. The `htdocs` folder serves as the public web root accessible from the internet. To ensure security, the majority of project files are stored in the `private` folder. The `htdocs/index.php` checks user permissions from login before serving any underlying file. Admin-only pages use a separate `index.php` inside `/htdocs/admin/` to verify administrator privileges.

### File Organization (v1)
Inside the private folder, files are organized by function:
* `pages/all_access`: Publicly navigable pages (routed through `index.php`).
* `pages/admin`: Admin-only pages.
* `scripts/features`: PHP `$_SESSION` config and miscellaneous shared functions.
* `scripts/login` & `scripts/signup`: Authentication scripts.
* `scripts/view`: Tables, images, messages, and CSS handling (see `serve_resources.php`).
* `scripts/web_management`: Scripts accessible via web POST requests.
* `scripts/game_management`: Scripts accessible via Godot game POST requests (score logging, server pinging, etc.).

### Database Queries (v1)
The `dbh_manager.php` file (in `/private`) handles all database queries using the `run_query()` function. Crucially, it uses **prepared statements with bound variables** rather than raw concatenation, preventing SQL injection vulnerabilities.

### handle_POST.php (v1)
Because hidden backend scripts cannot be directly accessed by web browsers or Godot HTTP requests, `handle_POST.php` in the public `htdocs` directory acts as a secure gateway for incoming POST requests. It evaluates user permissions (e.g., verifying logged-in status before posting game scores) before routing requests to the appropriate backend scripts.

### serve_resources.php (v1)
This file allows public HTML pages to securely reference CSS, images, and JavaScript assets stored in the restricted private folder (specifically `/private/view/resources`).
