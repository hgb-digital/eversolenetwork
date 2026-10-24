<h1>Eversole Network</h1>
<p><strong>Hudson Brackeen</strong><br>
<em>Uploaded for the MIT Maker Portfolio</em></p>

<hr>

<h2>Description</h2>
<p>This is a game high-score database created using PHP, MySQL, HTML, CSS, and JS that includes a client designed for Godot game engine. Games running the client can log scores through the web server, earn XP, and reward tokens. Users who log in on the web can buy items from the shop, view high-scores, and see other players' progress. This project was started to replace a former teacher's old arcade database system which relied on <code>.txt</code> files and manual Dropbox syncing. It was his students' games I was creating this for.</p>

<hr>

<h2>Development</h2>
<p>To develop this web app, I ran a XAMPP development web server (including Apache and a MySQL database) and coded using Visual Studio Code. I used Godot game engine and its GDScript (Python based) to develop the client-side program. I used AI tools such as Claude and ChatGPT to replace my traditional workflow (plowing through Youtube tutorials and Stack Overflow entries) with a system that could give me advice tailored to my project. AI helped me quickly learn syntax and brainstorm solutions to more complex issues.</p>

<p>A MySQL configuration file for the Eversole Network database exported from PHPMyAdmin (a database management application) is also included in this repository. Database files for both version 1 and version 2 are included in their respective folders. The version 1 and version 2 folders contain 2 different versions of the web server code; see technical description for information on which version contains what. The godot folder (only 1 version) is also included and contains the client code used for the system. "empty_game" includes just the client loader while "with_client" contains the actual client program; again, see technical description to understand what this means. </p>

<p>Throughout the process, I met Mr. Eversole (hence Eversole Network) multiple times to discuss progress and help understand features he'd like me to add. He is the former teacher I was creating this for.</p>

<p>For version 1, the full server was hosted on <code>eversole.network</code> but is currently disconnected due to it being out of use (see technical description).</p>

<hr>

<h2>Features</h2>
<p>This is a general overview of the features of the game. For an in-depth technical review on how specific components function, see below.</p>

<p>Eversole Network was built for Godot, the primary game engine his students used to create their games, and utilizes a full-stack web server to store the user data and game scores.</p>

<h3>Client Side</h3>
<h4>Dynamic Runtime Download</h4>
<ul>
  <li><strong>Automated Client Fetch:</strong> On game startup, an ENET (Eversole Network) loader script pings the web server to request the latest client package over HTTP.</li>
  <li><strong>Runtime Execution:</strong> The game downloads and runs the latest client build on the fly rather than storing the client code in the game build.</li>
  <li><strong>Seamless Updates:</strong> Allows central client updates across the network without requiring students to recompile or re-export their games.</li>
</ul>

<h4>Client Interface & User Experience</h4>
<ul>
  <li><strong>In-Game Overlay:</strong> Pressing SHIFT + TAB opens the interactive ENET interface where players can log in (or choose to continue as a guest).</li>
  <li><strong>Score &amp; Leaderboard Access:</strong> Displays game-specific high scores to all players, alongside personal score histories for logged in users.</li>
  <li><strong>Progress &amp; Rewards:</strong> Displays player XP (gained through playtime) and coin balances in the UI.</li>
</ul>

<h4>Developer Integration</h4>
<ul>
  <li><strong>Score Submission:</strong> Games log high scores simply by calling the <code>post_score()</code> function.</li>
  <li><strong>In-Game Economy:</strong> Games can award or deduct player coins dynamically based on gameplay achievements and shop purchases.</li>
</ul>

<h3>Server Side</h3>
<h4>User Features</h4>
<ul>
  <li><strong>Global Leaderboards:</strong> Logged-in users can view high scores across all games connected to the platform.</li>
  <li><strong>Developer Dashboard:</strong> Game creators can view the unique Game ID and password required by their Godot client to enable score logging.</li>
  <li><strong>Game &amp; Version Registration:</strong> Creators can submit requests to admins to register new games or request new credentials for updated game versions (ideal for resetting scoreboards when scoring mechanics change).</li>
  <li><strong>Player Profiles:</strong> Users can inspect public profiles showing player XP, earned coins, unlocked achievements, and live online status.</li>
  <li><strong>Web Shop:</strong> Players can spend earned coins on rewards, such as custom 3D-printed gadgets.</li>
</ul>

<h4>Administrator Controls</h4>
<ul>
  <li><strong>Game &amp; Request Management:</strong> Approve or deny game/version registration requests, manually add new game entries (automatically notifying owners), and delete obsolete games.</li>
  <li><strong>Score Moderation &amp; User Safety:</strong> Audit leaderboards to remove malicious or cheated scores, with the ability to ban accounts when necessary.</li>
  <li><strong>Platform Communications:</strong> Broadcast global server messages across the home screen.</li>
  <li><strong>Client Distribution:</strong> Access and download the official client loader script package for distribution to developers.</li>
  <li><strong>Economy Analytics:</strong> Admins can track system-wide and per-game metrics, including total playtime, total coins earned/spent, and coin earn rates (coins/min) to maintain balance.</li>
  <li><strong>Session Management:</strong> View real-time active connections (showing who is playing what) with the ability to manually disconnect specific sessions.</li>
</ul>

<hr>

<h2>Technical Description</h2>
<p>Most features were first introduced in version 1 (the one that was hosted on <code>eversole.network</code>) but a few features were added in version 2. When each of these features were first introduced is denoted by either (v1) or (v2). This project is still in development but version 1 represents the first functional prototype.</p>

<h3>Version 1 Hosting (v1)</h3>
<p>For version 1, the web server was run on a Ubuntu Linux Apache web server on my home network (see VLAN project in portfolio for how I made this secure) and connected to a Cloudflare tunnel. The Cloudflare tunnel allowed traffic from <code>eversole.network</code> to connect to my home server without having to directly expose my WAN IP (internet IP) to the public or use port forwarding. Using PHPMailer and SMTP2GO, I sent account verification/password reset emails from <code>account@eversole.network</code>, an email I setup through Cloudflare.</p>

<h3>Email Verification / Password reset (v2)</h3>
<p>When a user needs to verify their account (required for use) or reset their password, the server can send an message to the user's email. This works by generating a link with a token and storing it in the database. When the user clicks on the link, the page evaluates the token and its type then verifies the account or helps the user reset their password. I used Claude to help me write this portion due to my limited knowledge of the API.</p>

<h3>Password Hashing (v1)</h3>
<p>When users signup, the password entered into the database is hashed using Blowfish's hashing algorithm in the <code>$2y$</code> format (native to PHP).</p>

<h3>Logging Anonymous/Offline Scores (v1)</h3>
<p>In some cases, a user may earn a high-score without being logged in. In this case, the client stores the score and will post it if the user decides to log in. The same is true for earning scores while offline. The scores are saved and will automatically post when reconnected to the server (assuming the user doesn't close the game).</p>

<h3>Client Loading in Godot (v1)</h3>
<p>Each game will put a client loader scene into their project and make it a singleton, a scene that is always loaded no matter what part of the game you're in, like a globally accessible class. On running the game, the loader will contact <code>eversole.network</code> and attempt to download <code>current.pck</code> using the HTTP Request node in Godot. If successful, the game then unloads the <code>.pck</code> file, which contains the most recent client program, and runs it. There are 2 main benefits to using this system: 1. you don't need to recompile each game when you make an update to the client software and 2. this helps prevent users from being able to decompile a game and post their own fake scores (see Score Authenticity Measures).</p>

<h3>Score Authenticity Measures (v1)</h3>
<p>There are 2 main measures put into place to ensure the authenticity of scores posted. First, the actual client program that posts scores is only loaded on runtime (no actual client code is stored with the game files). This makes it a little more difficult for "hackers" to view the client program's code (which would show them how to post fake scores). Second, each game is assigned a unique ID and password that they must send to the server before being able to create a valid session and post scores (see Game Pinging). This helps ensure that only the valid game build can post scores (assuming the owner doesn't leak the password). However, there is one fallback to this approach. If users find a way to decompile the game, they could theoretically access the password and ID along with the client program which would allow them to post fake scores and reward themselves coins. Currently, I have not found a viable solution to mitigate this situation. The game economy is the one way in which admins can help track hackers (too many coins in little playtime, see Game Economy Tracking).</p>

<h3>How to keep track of active sessions, Game Pinging (v1)</h3>
<p>When a client successfully connects to the server, the server creates an entry in the <code>sessions</code> table in the database which tracks data such as when the user first logged on, when the last ping was, what game they're playing, who's playing the game, and scores logged. When the admin logs onto the connections page, they can see all of these active sessions. Every 5 seconds the game pings the sever. If 10 seconds have passed without a ping, the session is disconnected. If the game loses connection, a new session is started upon reconnect. If the admin disconnects the session manually, the client will not retry to connect. The time between last ping and when the session was first created helps the server track playtime of each session (helping track coins earned / min, see Game Economy Tracking). This feature also allows the server to display what users are online (and what game) on the users page of the website. Additionally, the number of online players for a particular game will display in the client of that game.</p>

<h3>Game Economy Tracking (v2)</h3>
<p>Because the system allowed games to reward users with currency, I wanted to have a way of tracking how much each game was rewarding in an attempt to ensure equality between games. One game giving too much would be unfair. I used Claude to generate a list of statistics I could track and adapted it to fit with my database infrastructure. Coins per playtime minute could also help admins price items in the shop.</p>

<h3>The Great Database Restructure (v1)</h3>
<p>This was my first project that used a web server architecture and database system. With no prior experience in database structuring, I was learning as I went. Originally, every new game that was added to the system would create its own table in the database (like flappy.1 for flappy version 1). However, I quickly realized that having the server create new tables in the database was inefficient and, more importantly, dangerous. A user could easily place MySQL syntax in the game request form and inject malicious commands into the system. That's why I decided to restructure the database to instead rely on Foreign Keys. Foreign Keys are a way to connect entries in one table to entries of another. Instead of having 1 table for each game score log, I have 1 scores table that references which game ID each score belongs to. When I query for a game scores I just add SELECT WHERE game_id = {id} into the MySQL query. Additionally, if a game was deleted, the deletion would cascade and delete all the scores associated with that game in the scores table. This Foreign Key system was also used for connecting other various parts of the infrastructure. </p>

<h3>File Segmentation (similar to NHS hour logger) (v1)</h3>
<p>Project files are split into 2 folders: <code>htdocs</code> and <code>private</code>. The folder <code>htdocs</code> was the web root and was what web users physically had access to from the internet. However, in order to create a secure environment, I moved the majority of the project's files into a private folder (inaccessible to web users). The <code>index.php</code> in the <code>htdocs</code> folder would then check the URL the user inputted (or was directed to) and ensure the file they were trying to reach was accessible with the permissions they were assigned at login before loading the page. For admin-only pages, I had a separate <code>index.php</code> within <code>/htdocs/admin/</code> that checked admin permissions before loading the file.</p>

<h3>File Organization (v1)</h3>
<p>In the private folder, I split each file into different groups based on function. The <code>pages/all_access</code> folder simply contained all the pages I allowed users to navigate to through the URL (through <code>index.php</code>). <code>pages/admin</code> contained admin-only pages. The <code>scripts</code> folder contained everything else. The <code>features</code> folder contained PHP <code>$_SESSION</code> config (how login is stored) and a functions script which had miscellaneous functions that could be called by others scripts. The login and signup folders had scripts for user login and signup, respectively. The <code>view</code> folder contained scripts having to do with tables, images, messages, or CSS (see <code>serve_resources</code> section below). The <code>web management</code> folder contained scripts that could be accessed using POST requests from the web. The <code>game management</code> folder contained scripts that could be accessed using POST requests from Godot games such as logging scores or pinging the server (see <code>handle_POST</code> section).</p>

<h3>Database Queries (v1)</h3>
<p>The <code>dbh_manager.php</code> file (in <code>/private</code>) handled all database queries. Using this file, scripts could simply run the <code>run_query()</code> function in order to grab information from the database. An important security feature, this file used statement preparation with variables bound to the statement rather than injected. This prevented SQL injection by checking each user input (username, password, etc.) before attaching it to the SQL query.</p>

<h3>handle_POST.php (v1)</h3>
<p>There are a variety of forms that need to submit data through POST requests, however these scripts are hidden in the private folder and therefore HTML or HTTP request from Godot cannot access them. I created the <code>handle_POST.php</code> file in the <code>htdocs</code> (public) directory for handling POST requests. Any form attempting to make a post request (like logging in) will send data to this file and it will then evaluate whether or not the user has sufficient permissions to make the request (only logged in users can post game scores). Because there are ways to submit POST request without being on the actual webpage or in game, this file acts as a barrier for users attempting to access functions they are not authorized to access.</p>

<h3>serve_resources.php (v1)</h3>
<p>Similarly to POST requests, sometimes a file needs to reference CSS files, images, or Javascript files that are not available in the public <code>htdocs</code> directory. The file simply allows HTML to use resources found in the private folder (specifically <code>/private/view/resources</code>).</p>
