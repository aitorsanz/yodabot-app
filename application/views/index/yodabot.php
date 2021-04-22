<html>
<head>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body>
<div class="text-center">
    <img src="application/files/baby-yoda-2.png" class="img-fluid" alt="Baby Yoda">
    <h2>YodaBot</h2>
    <div id="dialog">

    </div>
    <div id="writing" class="d-none">
        <p>YodaBot is writing...</p>
    </div>
    <div id="starting" class="d-none">
        <p>Starting conversation...</p>
    </div>
    <form>
        <div class="form-row">
            <div class="form-group col-md-3">
            </div>
            <div class="form-group col-md-6">
                <input type="text" class="form-control" id="message" placeholder="Enter message" required>
            </div>
        </div>
        <button type="button" id="startConversation" class="btn btn-primary">Start</button>
        <button type="button" id="sendMessage" class="btn btn-primary">Send</button>
    </form>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sendMessage').click(function(e) {
            $('#writing').removeClass('d-none');
            e.preventDefault();
            message = $('#message').val();
            $.ajax({
                type: "POST",
                url: "/index/conversation",
                data: {'message' : message},
                success: function(data, textStatus)
                {
                    inicio = data.indexOf("div") - 1;
                    fin = data.lastIndexOf("div") + 1;
                    html = data.substr(inicio, fin);
                    $('#dialog').append(html);
                    $('#writing').addClass('d-none');
                },
                error: function(data, textStatus){
                }
            });
        });
        $('#startConversation').click(function(e) {
            $('#starting').removeClass('d-none');
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/index/start",
                success: function(data, textStatus)
                {
                    $('#starting').addClass('d-none');
                },
                error: function(data, textStatus){
                }
            });
        });
    });
</script>
</html>