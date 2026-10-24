<?php

// Economy visualization admin page
// Usage: visit this page in the admin area. Add `?json=1` to return JSON stats.

# get transactions + session playtime
$transactions = run_query('SELECT t.value, t.vendor_id, t.user_id, t.session_id, s.playtime FROM transactions t JOIN sessions s ON s.id = t.session_id;');

function analyzeEconomy(array $transactions)
{
    $global = [
        'total_rewarded' => 0,
        'total_spent'    => 0,
        'net_flow'       => 0,
        'total_playtime' => 0,
    ];

    $games = [];   // vendor_id → stats
    $players = []; // user_id → stats

    $seenSessionsGlobal = [];
    $seenSessionsByGame = [];
    $seenSessionsByPlayer = [];

    foreach ($transactions as $t) {
        $value     = $t['value'];

        $gameId    = $t['vendor_id'];
        $game_temp = run_query("SELECT game_id, version_number FROM game_versions WHERE id = :id;", [":id" => $gameId])[0];
        $game_name = run_query("SELECT name FROM games WHERE id = :id;", [":id" => $game_temp['game_id']])[0]['name']; 
        $game = $game_name . " (v" . $game_temp['version_number'] . ")";

        $userId    = $t['user_id'];
        $user = run_query("SELECT username FROM users WHERE id = :id;", [":id" => $userId])[0]['username'];

        $sessionId = $t['session_id'];
        $playtime  = $t['playtime'];

        // GLOBAL
        if ($value > 0) {
            $global['total_rewarded'] += $value;
        } else {
            $global['total_spent'] += abs($value);
        }
        $global['net_flow'] += $value;
        if (!isset($seenSessionsGlobal[$sessionId])) {
            $seenSessionsGlobal[$sessionId] = true;
            $global['total_playtime'] += $playtime;
        }

        // GAME
        if (!isset($games[$game])) {
            $games[$game] = [
                'total_rewarded' => 0,
                'total_spent'    => 0,
                'net_flow'       => 0,
                'total_playtime' => 0,
                'reward_per_min' => 0,
                'spend_per_min'  => 0,
                '_name' => $game_name,
                '_version' => $game_temp['version_number']
            ];
        }
        if ($value > 0) {
            $games[$game]['total_rewarded'] += $value;
        } else {
            $games[$game]['total_spent'] += abs($value);
        }
        $games[$game]['net_flow'] += $value;
        if (!isset($seenSessionsByGame[$game])) {
            $seenSessionsByGame[$game] = [];
        }
        if (!isset($seenSessionsByGame[$game][$sessionId])) {
            $seenSessionsByGame[$game][$sessionId] = true;
            $games[$game]['total_playtime'] += $playtime;
        }

        // PLAYER
        if (!isset($players[$user])) {
            $players[$user] = [
                'total_rewarded' => 0,
                'total_spent'    => 0,
                'net_wealth'     => null,
                'sessions'       => 0,
                'total_playtime' => 0,
            ];
        }
        if ($value > 0) {
            $players[$user]['total_rewarded'] += $value;
        } else {
            $players[$user]['total_spent'] += abs($value);
        }
        if (!isset($seenSessionsByPlayer[$user])) {
            $seenSessionsByPlayer[$user] = [];
        }
        if (!isset($seenSessionsByPlayer[$user][$sessionId])) {
            $seenSessionsByPlayer[$user][$sessionId] = true;
            $players[$user]['sessions']++;
            $players[$user]['total_playtime'] += $playtime;
        }
        if (!isset($players[$user]['net_wealth'])) {
            $coins = run_query("SELECT coins FROM users WHERE id = :id;", [":id" => $userId])[0]['coins'];
            $players[$user]['net_wealth'] = $coins;
        }
    }

    foreach ($games as $id => &$g) {
        if ($g['total_playtime'] > 0) {
            $g['reward_per_min'] = $g['total_rewarded'] / $g['total_playtime'];
            $g['spend_per_min']  = $g['total_spent'] / $g['total_playtime'];
        }
    }

    foreach ($players as $id => &$p) {
        if ($p['total_playtime'] > 0) {
            $p['reward_per_min'] = $p['total_rewarded'] / $p['total_playtime'];
            $p['spend_per_min']  = $p['total_spent'] / $p['total_playtime'];
        } else {
            $p['reward_per_min'] = 0;
            $p['spend_per_min']  = 0;
        }
    }

    // compute global ratios (coins per minute)
    if ($global['total_playtime'] > 0) {
        $global['reward_per_min'] = $global['total_rewarded'] / $global['total_playtime'];
        $global['spend_per_min']  = $global['total_spent'] / $global['total_playtime'];
        $global['coins_per_min']  = $global['net_flow'] / $global['total_playtime'];
    } else {
        $global['reward_per_min'] = 0;
        $global['spend_per_min']  = 0;
        $global['coins_per_min']  = 0;
    }

    return [
        'global'  => $global,
        'games'   => $games,
        'players' => $players,
    ];
}

$stats = analyzeEconomy($transactions);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Economy</title>
    <style>
        .sections {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: .25em;
        }
        .section {
            flex: 1 1 300px;
            padding: 1rem;
            border: .25rem solid black;
            box-sizing: border-box;
        }
        .full-section {
            flex: 1 1 100%;
        }
        table { border-collapse: collapse; margin-top: 1rem; }
        table td, table th { border: 1px solid #999; padding: 6px 8px; }
    </style>
</head>
<body>
    <h1>Economy</h1>
    <div class="sections">
        <div class="section full-section">
            <h2>Globals</h2>
            <p>Total playtime: <?php echo $stats['global']['total_playtime']; ?> minutes</p>
            <p>Total rewarded: <?php echo $stats['global']['total_rewarded']; ?> coins</p>
            <p>Total spent: <?php echo $stats['global']['total_spent']; ?> coins</p>
            <p>Net flow: <?php echo $stats['global']['net_flow']; ?> coins</p>
            <br>
            <p>Reward / min: <?php echo number_format($stats['global']['reward_per_min'],6); ?></p>
            <p>Spend / min: <?php echo number_format($stats['global']['spend_per_min'],6); ?></p>
            <p>Net / min: <?php echo number_format($stats['global']['coins_per_min'],6); ?></p>

        </div>
    </div>

    <?php

    // Games table sorted by net_flow desc
    $games = $stats['games'];
    uasort($games, function($a,$b){ return $b['net_flow'] <=> $a['net_flow']; });
    echo "<div class='section'>";
    echo "<h2>Games (sorted by net flow)</h2>";
    echo "<table>",
         "<tr><th>Game</th><th>Total Rewarded</th><th>Total Spent</th><th>Net Flow</th><th>Total Playtime (min)</th><th>Reward/min</th><th>Spend/min</th></tr>";
    foreach ($games as $id => $g) {
        $link = '<a href = "/?url=game&game=' . urlencode($g['_name']) . '&version=' . urlencode($g['_version']) . '">' . htmlspecialchars($id) . '</a>';
        echo "<tr>";
        echo "<td>".$link."</td>";
        echo "<td>".htmlspecialchars($g['total_rewarded'])."</td>";
        echo "<td>".htmlspecialchars($g['total_spent'])."</td>";
        echo "<td>".htmlspecialchars($g['net_flow'])."</td>";
        echo "<td>".htmlspecialchars($g['total_playtime'])."</td>";
        echo "<td>".number_format($g['reward_per_min'],6)."</td>";
        echo "<td>".number_format($g['spend_per_min'],6)."</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";

    // Players table sorted by total_playtime desc
    $players = $stats['players'];
    uasort($players, function($a,$b){ return $b['total_playtime'] <=> $a['total_playtime']; });
    echo "<div class='section'>";
    echo "<h2>Players (sorted by total playtime)</h2>";
    echo "<table>",
         "<tr><th>User</th><th>Total Rewarded</th><th>Total Spent</th><th>Net Wealth</th><th>Sessions</th><th>Total Playtime</th><th>Reward/min</th><th>Spend/min</th></tr>";
    foreach ($players as $id => $p) {
        $link = '<a href = "/?url=users&user=' . urlencode($id) . '">' . htmlspecialchars($id) . '</a>';
        echo "<tr>";
        echo "<td>".$link."</td>";
        echo "<td>".htmlspecialchars($p['total_rewarded'])."</td>";
        echo "<td>".htmlspecialchars($p['total_spent'])."</td>";
        echo "<td>".htmlspecialchars($p['net_wealth'])."</td>";
        echo "<td>".htmlspecialchars($p['sessions'])."</td>";
        echo "<td>".htmlspecialchars($p['total_playtime'])."</td>";
        echo "<td>".number_format($p['reward_per_min'],6)."</td>";
        echo "<td>".number_format($p['spend_per_min'],6)."</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    ?>

