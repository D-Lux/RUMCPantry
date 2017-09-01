<!doctype html>
<html>

<head>

    <script src="js/utilities.js"></script>
    <script src="js/createItem.js"></script>
    <script src="js/destinationFunctions.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <?php include 'php/header.php'; ?>
    <?php include 'php/checkLogin.php';?>

    <title>ap_io2</title>
</head>

<body>
    <script src="js/destinationFunctions.js"></script>
    <input type="button" value="Go Back" onclick="ap_io2Back()">
    <h1>
        Add an item to the database:
    </h1>

    <form name="addItem" action="php/itemOps.php" onSubmit="return validateItemAdd()" method="post">
        <!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
        <!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->
        <div id="category">
            Category<span style="color:red;">*</span> 
            <?php 
            createDatalist("","categories","category","name","category", false);
            ?>
        </div>

        <div id="itemName">Item name (used for the database):<span style="color:red;">*</span> 
            <?php 
            createDatalist("","itemNames","item","itemName","itemName", true);
            
            ?>
        </div>
        <div id="displayName">Display name (what you want the item to be called):<span style="color:red;">*</span> 
            <?php 
            createDatalist("","displayNames","item","displayName","displayName", true);
            ?>
        </div>
        <div id="price">Price: <input type="number" min="0" max="100000" value="0.00" step="0.01" name="price" /></div>
        <div id="household">How many of each can a household take?</div>
        <div id="small"> 1 to 2:
            <select name="small">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select> </div>
        <div id="medium">3 to 4:
            <select name="medium">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select> </div>
        <div id="large">5+:
            <select name="large">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select> </div>
        

 
     
        </br>
        <input type="submit" value="Create" name="createItem">
    </form>
    <h2>
        <!--  <?php include 'php/createItem.php';?> -->
    </h2>
</body>

</html>