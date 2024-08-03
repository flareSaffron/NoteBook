<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(".toggleForm").click(function() {
        $("#signUpForm").toggle();
        $("#logInForm").toggle();
    });

    $("#diary").bind('input propertychange', function(){
        // alert("change");
        $.ajax({
            method: "POST",
            url: "updatedatabase.php",
            data: {content: $("#diary").val()}
        })
        // .done(function(msg){
        //     alert("Data Saved:"+ msg);
        // });
    });
</script>
</body>

</html>