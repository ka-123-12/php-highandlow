<?php
session_start();

$cards = array("Jk.png", "01.png", "02.png", "03.png", "04.png", "05.png", "06.png", 
    "07.png", "08.png", "09.png", "10.png", "11.png", "12.png", "13.png");
$backImage = "bg.png";

// 最初のカードをランダムで決める（まだセッションにない場合）
if (!isset($_SESSION['initial_number'])) {
    $_SESSION['initial_number'] = rand(0, 13); // 1から13の範囲でランダム
}

$currentNumber = $_SESSION['initial_number'];

$message = '';
$choiceMessage = '';
$nextNumber = null;
$gameFinished = false;

if (isset($_POST['choice'])) {
    $choice = $_POST['choice'];
    
    if ($choice === 'high') {
        $choiceMessage = "Highを選択しました。";
    } elseif ($choice === 'low') {
        $choiceMessage = "Lowを選択しました。";
    }
    
    // 次のカードをランダムで決める
    $nextNumber = rand(0, 13); // 1から13の範囲でランダム
    
    // ハイローの判定
    if (($choice === 'high' && $nextNumber >= $currentNumber) ||
        ($choice === 'low' && $nextNumber <= $currentNumber)) {
            $message = "You Win!";
        } else {
            $message = "You Lose...";
        }
        
        // 次のターンのために現在の数字を更新
        $_SESSION['current_number'] = $nextNumber;
        $gameFinished = true;
}

// リセット処理
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>High＆Lowゲーム</title>
    <style>
        /* 全体を中央揃えにするためのスタイル */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
            padding: 20px;
        }

        .card-area {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card-area img {
            width: 100px;
        }

        button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        label {
            margin: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>High＆Lowゲーム</h1>
        
        <div class="card-area">
            <!-- 最初のカード -->
            <div>
                <img src="../cards/<?php echo $cards[$currentNumber]; ?>" alt="現在のカード">
            </div>

            <!-- 次のカード -->
            <div>
                <?php if ($gameFinished): ?>
                    <!-- 次のカード -->
                    <img src="../cards/<?php echo $cards[$nextNumber]; ?>" alt="次のカード">
                <?php else: ?>
                    <!-- 裏向きカード -->
                    <img src="../cards/<?php echo $backImage; ?>" alt="裏向きのカード">
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$gameFinished): ?>
        <form method="post">
            <!-- ハイロー選択 -->
            <label>
                <input type="radio" name="choice" value="high"> High
            </label>
            <label>
                <input type="radio" name="choice" value="low"> Low
            </label>
            <br><br>
            <button type="submit" name="decide" value="1">決定</button>
        </form>
        <?php endif; ?>

        <p><?php echo $choiceMessage; ?></p>
        <p><?php echo $message; ?></p>

        <?php if ($gameFinished): ?>
        <!-- ハイロー選択後だけ表示 -->
        <form method="post">
            <button type="submit" name="reset" value="1">もう一度挑戦</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
