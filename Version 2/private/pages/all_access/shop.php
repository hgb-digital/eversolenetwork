<?php
    $shop_items = run_query("SELECT * FROM shop_items");
    $balance = run_query("SELECT coins FROM users WHERE id = :id", [":id" => $_SESSION['user_id']])[0]['coins'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENET: Shop</title>
    
    <style>
        #achievement {
            padding: .5em;
            border: .1em;
            border-color: black;
            border-style: solid;
            width: fit-content;
            margin-bottom: .5em;
        }
        .sections {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .section {
            flex: 1 1 300px;
            padding: 1rem;
            border: .25rem solid black;
            box-sizing: border-box;
        }
        .item-image {
            max-width: 100%;
            height: auto;
            border-radius: .25em;
        }
    </style>

</head>
<body>
    <h1>ENET Shop</h1>
    <?php if (!isset($_SESSION['user_id'])){ ?>
        <p>You must be logged in to purchase items.</p>
    <?php } else { ?>
        <h2><img class="pixel-image" src="/serve_resources.php?type=img&file=coin.png" alt="coin" style="width: 2em; height: 2em; vertical-align: middle;"> <?php echo $balance; ?></h2>
    <?php } ?>
    <div class="sections">
        <?php
            foreach ($shop_items as $item) {
                echo("<div class='section'>");
                echo("<img class='item-image' src='/serve_resources.php?type=img&file=" . $item['img_path'] . "' alt='" . $item['title'] . "' >");
                echo("<h2>" . $item['title'] . "</h2>");
                echo("<p style='color: green;'>" . $item['cost'] . " coins</p>");
                echo("<p>" . $item['description'] . "</p>");
                if (isset($_SESSION['user_id'])) {
                    echo("<form method='POST' action='/purchase_item.php'>");
                    echo("<input type='hidden' name='item_id' value='" . $item['id'] . "'>");
                    echo("<button type='submit'>Purchase</button>");
                    echo("</form>");
                }
                echo("</div>");
            }
        ?>
    </div>

</body>
</html>