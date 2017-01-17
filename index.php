<!DOCTYPE HTML>
<html>
    <head>
        <title>Tip Calculator</title>
        <link rel="stylesheet" href="styles.css">
    </head>
<body>

<?php
// define variables and set to empty values
$billErr = $tipPercentageErr = $splitErr = "";
$bill = $tipPercentage = $customTipPercentage = "";
$split = 1;
$selectedTip = 10;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["bill"])) {
    $billErr = "Bill is required";
  } else {
    $bill = test_input($_POST["bill"]);
    // check if bill only contains numbers and decimal
    if (!preg_match("/^[0-9.]*$/",$bill)) {
      $billErr = "Only numbers and dot are allowed";
    }
  }

  if (empty($_POST["tipPercentage"]) && empty($_POST["customTipPercentage"])) {
    $tipPercentageErr = "Tip percentage is required";
    $selectedTip = 'custom';
  } else if (!empty($_POST["customTipPercentage"]) && $_POST["customTipPercentage"] <= 0) {
    $selectedTip = 'custom';
    $tipPercentageErr = "Tip percentage cannot be negative";
    $customTipPercentage = test_input($_POST["customTipPercentage"]);
  } else if ($_POST["tipPercentage"] == "custom") {
    $selectedTip = 'custom';
    $customTipPercentage = $tipPercentage = test_input($_POST["customTipPercentage"]);
  } else {
    $customTipPercentage = "";
    $selectedTip = test_input($_POST["tipPercentage"]);
    $tipPercentage = test_input($_POST["tipPercentage"]);
  }

  if (empty($_POST["split"])) {
      $splitErr = "Split is required";
  } else if ($_POST["split"] <= 0) {
      $splitErr = "Split should not be zero or negative";
  } else {
      $split = test_input($_POST["split"]);
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<div class="container">
<h1 class="center-text">Tip Calculator</h1>
<p class="center-text"><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Bill subtotal: $<input type="text" name="bill" value="<?php echo $bill;?>">
  <span class="error">* <br> <?php echo $billErr;?></span>
  <br><br>
  Tip percentage:
  <br><br>

  <?php
    for ($i = 1; $i < 4; $i++) {
        $tipPercent = 5 * ($i + 1);
        if ($tipPercent == $selectedTip) {
            echo "<input type='radio' name='tipPercentage' checked value='$tipPercent'> $tipPercent%  ";
        } else {
            echo "<input type='radio' name='tipPercentage' value='$tipPercent'> $tipPercent%  ";
        }
    }
    echo '<br>';
    if ($selectedTip == 'custom') {
        echo "<input type='radio' name='tipPercentage' checked value='custom'>";
    } else {
        echo "<input type='radio' name='tipPercentage' value='custom'>";
    }

    echo "Custom: <input type='number' step='any' name='customTipPercentage' value='$customTipPercentage'>%";
  ?>
  <span class="error">* <br> <?php echo $tipPercentageErr;?></span>
  <br><br>
  <?php
    echo "Split: <input type='number' name='split' value='$split'>";
  ?>
  <span class="error">* <br> <?php echo $splitErr;?></span>
  <br><br>
  <div class="submit">
    <input type="submit" name="submit" value="Submit">
  </div>
</form>

<div class="center-text result">
<?php
if (!$billErr && !$tipPercentageErr && $_SERVER["REQUEST_METHOD"] == "POST") {
    $tip = $bill * $tipPercentage / 100;
    echo "<br>";
    echo 'Tip: $' . $tip;
    echo "<br>";
    $total = $bill + $tip;
    echo 'Total: $' . $total;
    echo "<br>";
    if ($split > 1) {
        echo 'Tip each: $' . $tip / $split;
        echo "<br>";
        echo 'Total each: $' . $total / $split;
    }
}
?>
</div>
</div>
</body>
</html>
