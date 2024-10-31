<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  
  <link rel="stylesheet" href="build/css/intlTelInput.css">
  <!--<link rel="stylesheet" href="build/css/demo.css">-->
  <script src="build/js/intlTelInput.js"></script>

  <title>International Phone Input</title>
</head>
<body>
  <h3>International Phone : </h3>

  <form action="" method="post">
    <input id="phone" type="text">
    <input type="submit" id="btn" name="btn" value="ok">
  </form>

  <script>
    // GET COUNTRY JSON :
    //var countryData = $.fn.intlTelInput.getCountryData();
    //console.info( JSON.stringify(countryData) );

    //$("form").submit(function() {
    //  $("#phone").val($("#phone").intlTelInput("getNumber"));
    //  console.log("getNumber : "+$("#phone").intlTelInput("getNumber"))
    //});

    $("#phone").intlTelInput({
      utilsScript: "/build/js/utils.js",
      hiddenInput: "full_phone",
      autoPlaceholder: true,
      setCountry: "fr",
      setNumber: "0511223344",
      setPlaceholderNumberType: "FIXED_LINE",
    });   
    
    $("#btn").click(function(){
      var isValid = $("#phone").intlTelInput("isValidNumber");
      if (isValid) {
        console.log("OK");
      } else {
        console.log("NOKKKKKKKKK");
        event.preventDefault();
      }
    });

    // FOR DEMO :
    //$("#phone").intlTelInput("setPlaceholderNumberType", "FIXED_LINE");
    //$("#phone").intlTelInput("setNumber", "+33511223344");
  </script>
</body>
</html>