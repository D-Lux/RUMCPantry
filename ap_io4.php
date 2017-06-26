<!doctype html>
<html>

<head>

    <script src="js/utilities.js"></script>
    <script src="js/createItem.js"></script>
    <link rel="stylesheet" type="text/css" href="css/toolTip.css">
    <?php include 'php/utilities.php'; ?>


    <title>ap_io4</title>
</head>

<body>
    <button onclick="goBack()">Go Back</button>
    <h1>
        Add a category
    </h1>

    <form name="addCategory" action="php/itemOps.php" onSubmit="return validateCategoryAdd()" method="post">
        <!-- the function in the onsubmit is run when the form is submitted, if it returns false the form will not submit. -->
        <!--  action is where this will go after. for this I don't think we need to move to a different screen. The post method will feed to the php whatever variables are listed as post in the php-->
        <div id="name">
            Name:
            <?php 
            createDatalist("","names","category","name","name", false);
            ?>
        </div>
        <div id="small"> 1 to 2:
            <select name="small">
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
        <div id="walkIn">Walk-in:
            <select name="walkIn">
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
        <input type="submit" value="Create" name="createCategory">
    </form>
    <h2>
        <!--  <?php include 'php/createItem.php';?> -->
    </h2>
</body>

</html>